<?php

namespace App\Livewire;

use App\Models\Flag;
use App\Models\Text;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Spatie\SimpleExcel\SimpleExcelReader;

class EmailTexts extends Component
{
    use WithFileUploads;

    #[Rule('file|mimes:csv')]
    public $file = null;

    public array $texts = [];

    #[Rule('string')]
    public string $name = '';

    public $isInvalidCSV = false;
    public $countImported = 0;

    public function mount()
    {
        $this->texts = Text::query()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('name')
            ->toArray();
    }

    public function updatedFile()
    {
        $textsWhereExistent = Text::query()->count() > 0;

        /*$file = Dialog::new()
            ->filter('CSV', ['csv'])
            ->open();*/

        $this->isInvalidCSV = false;
        $this->countImported = 0;
        $csv = SimpleExcelReader::create($this->file->path(), 'csv');
        $headers = $csv->getOriginalHeaders();
        if (
            !isset($headers[0], $headers[1])
            || $headers[0] !== 'text'
            || $headers[1] !== 'subject'
        ) {
            $this->isInvalidCSV = true;
            return;
        }
        $rows = $csv->getRows();
        $rows->each(function (array $rowProperties) {
            $rowProperties['text'] = trim($rowProperties['text']);
            if (empty($rowProperties['text'])) {
                return;
            }
            Text::query()->firstOrCreate([
                'text' => $rowProperties['text'],
            ], [
                'name' => $this->name,
                'subject' => $rowProperties['subject'],
            ]);
            $this->countImported++;
        });
        $this->poll();

        Flag::query()->firstOrCreate([
            'name' => 'texts_imported',
        ], [
            'value' => true,
        ]);

        if (!$textsWhereExistent) {
            return to_route('start', ['withoutDisclaimer' => true]);
        }
    }

    public function poll()
    {
        $this->texts = Text::query()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('name')
            ->toArray();
    }

    public function loadDemo()
    {
        $this->name = 'Demo';
        // load file form existing file in storage
        $this->file = new \Illuminate\Http\UploadedFile(
            config_path('demo_texts.csv'),
            'demo_texts.csv',
            'text/csv',
            null,
            true
        );
        $this->updatedFile();
    }

    public function render()
    {
        return view('livewire.email-texts');
    }
}
