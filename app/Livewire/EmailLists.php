<?php

namespace App\Livewire;

use App\Models\EmailAddress;
use App\Models\Flag;
use App\Models\Tag;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class EmailLists extends Component
{
    use WithFileUploads;

    #[Rule('file|mimes:csv')]
    public $file = null;

    public array $lists = [];

    #[Rule('string')]
    public string $name = '';

    public $isInvalidCSV = false;
    public $countImported = 0;

    public function mount()
    {
        $this->lists = Tag::query()
            ->with(['email_addresses.emails'])
            ->withCount(['email_addresses'])
            ->orderBy('email_addresses_count', 'desc')
            ->get()->map(function ($list) {
                $list->emailsSent = $list->email_addresses()
                    ->whereHas('emails', fn($query) => $query->whereNotNull('sent_at'))
                    ->count();

                return $list;
            })->toArray();
    }

    public function poll()
    {
        $this->lists = Tag::query()
            ->with(['email_addresses.emails'])
            ->withCount(['email_addresses'])
            ->orderBy('email_addresses_count', 'desc')
            ->get()->map(function ($list) {
                $list->emailsSent = $list->email_addresses()
                    ->whereHas('emails', fn($query) => $query->whereNotNull('sent_at'))
                    ->count();

                return $list;
            })->toArray();
    }

    public function updatedFile()
    {
        $addressesWhereExistent = EmailAddress::query()->count() > 0;

        /*$file = Dialog::new()
            ->filter('CSV', ['csv'])
            ->open();*/

        $fileContents = file($this->file->path());
        // check if csv file is valid, it should be separated by comma and the header should contain email and name
        if (
            !str($fileContents[0])->contains('email')
            || !str($fileContents[0])->contains('name')
            || !str($fileContents[0])->contains('salutation')
        ) {
            $this->isInvalidCSV = true;
            return;
        }
        $this->isInvalidCSV = false;
        $this->countImported = 0;
        foreach ($fileContents as $line) {
            // skip first since it's the header
            if (str($line)->contains('email')) {
                continue;
            }
            $data = str_getcsv($line);
            [$email, $name, $salutation] = $data;
            $email = EmailAddress::query()->firstOrCreate([
                'address' => $email,
            ], [
                'name' => $name,
                'salutation' => $salutation,
            ]);
            // check if email already has a tag
            if ($email->tags()->count() > 0) {
                continue;
            }
            $this->countImported++;
            $email->attachTag($this->name);
        }
        $this->poll();
        $this->name = '';

        Flag::query()->firstOrCreate([
            'name' => 'lists_imported',
        ], [
            'value' => true,
        ]);

        if (!$addressesWhereExistent) {
            return to_route('start', ['withoutDisclaimer' => true]);
        }
    }

    public function loadDemo()
    {
        $jsonFilePath = 'settings.json';
        $settingsFromJsonFile = Storage::get($jsonFilePath);
        $settingsArray = json_decode(Crypt::decryptString($settingsFromJsonFile), true, 512, JSON_THROW_ON_ERROR);
        $email = $settingsArray['recipients'];
        $from = $settingsArray['mail.from']['name'];

        $this->name = 'Demo';
        // write headers to demo file
        $file = fopen(config_path('demo_emails.csv'), 'w');
        fputcsv($file, ['email', 'name', 'salutation']);
        // write one line to demo file
        fputcsv($file, [$email, $from, 'Hallo']);
        fclose($file);

        // load file form existing file in storage
        $this->file = new \Illuminate\Http\UploadedFile(
            config_path('demo_emails.csv'),
            'demo_emails.csv',
            'text/csv',
            null,
            true
        );
        $this->updatedFile();
    }


    public function render()
    {
        return view('livewire.email-lists');
    }
}
