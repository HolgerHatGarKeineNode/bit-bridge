<?php

namespace App\Livewire;

use App\Models\EmailAddress;
use App\Models\Tag;
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
        /*$file = Dialog::new()
            ->filter('CSV', ['csv'])
            ->open();*/

        $fileContents = file($this->file->path());
        // check if csv file is valid, it should be separated by comma and the header should contain email and name
        if (!str_starts_with($fileContents[0], 'email,name')) {
            $this->isInvalidCSV = true;
            return;
        }
        $this->isInvalidCSV = false;
        $this->countImported = 0;
        foreach ($fileContents as $line) {
            // skip first since it's the header
            if (str_starts_with($line, 'email')) {
                continue;
            }
            $data = str_getcsv($line);
            [$email, $name] = $data;
            $email = EmailAddress::query()->firstOrCreate([
                'address' => $email,
            ], [
                'name' => $name,
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
    }

    public function render()
    {
        return view('livewire.email-lists');
    }
}
