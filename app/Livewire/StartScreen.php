<?php

namespace App\Livewire;

use App\Mail\MailTest;
use App\Models\Flag;
use App\Models\Tag;
use App\Models\Task;
use App\Models\Text;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Native\Laravel\Facades\Notification;

class StartScreen extends Component
{
    public array $campaigns = [];

    public string $currentStep = 'disclaimer';

    public array $steps = [
        'disclaimer' => 'Haftungsausschluss',
        'smtp' => 'SMTP-Einstellungen',
        'texts' => 'Texte importieren',
        'lists' => 'E-Mail Listen importieren',
    ];

    #[Rule('required')]
    public $name = '';

    #[Rule('required')]
    public $username = '';

    public $password = null;

    #[Rule('required')]
    public $server = '';

    #[Rule('required')]
    public $encryption = 'none';

    #[Rule('required')]
    public $port = null;

    #[Rule('required')]
    public $recipients = '';

    public $count = 0;
    public $warning = false;
    public $testSent = false;

    public function mount(Request $request)
    {
        if ($request->has('withoutDisclaimer')) {
            $this->continue();
        }
        $this->campaigns = Task::query()
            ->with(['emails'])
            ->get()
            ->toArray();
        $jsonFilePath = 'settings.json';
        $settingsFromJsonFile = Storage::get($jsonFilePath);
        if (!$settingsFromJsonFile) {
            $settingsJson = Crypt::encryptString(json_encode([
                'recipients' => '',
                'mail.mailers.own.transport' => 'smtp',
                'mail.mailers.own.host' => null,
                'mail.mailers.own.port' => 587,
                'mail.mailers.own.encryption' => null,
                'mail.mailers.own.username' => '',
                'mail.mailers.own.password' => null,
                'mail.from' => [
                    'address' => '',
                    'name' => '',
                ],
            ], JSON_THROW_ON_ERROR));
            Storage::put($jsonFilePath, $settingsJson);
        }
        $settingsFromJsonFile = Storage::get($jsonFilePath);
        $settingsArray = json_decode(Crypt::decryptString($settingsFromJsonFile), true, 512, JSON_THROW_ON_ERROR);
        $this->recipients = $settingsArray['recipients'];
        $this->name = $settingsArray['mail.from']['name'];
        $this->username = $settingsArray['mail.mailers.own.username'];
        $this->server = $settingsArray['mail.mailers.own.host'];
        $this->port = $settingsArray['mail.mailers.own.port'];
        $this->encryption = $settingsArray['mail.mailers.own.encryption'] ?? 'none';
        $this->password = $settingsArray['mail.mailers.own.password'];
    }

    public function continue()
    {
        // check if smtp settings are set
        $smptSettingsFlag = Flag::query()->where('name', 'smtp_settings')->first();
        $textsFlag = Flag::query()->where('name', 'texts_imported')->first();
        $listsFlag = Flag::query()->where('name', 'lists_imported')->first();
        if (!$smptSettingsFlag || !$smptSettingsFlag->value) {
            $this->currentStep = 'smtp';
        }
        if ($smptSettingsFlag && (!$textsFlag || !$textsFlag->value)) {
            return to_route('emailTexts');
        }
        if ($textsFlag && (!$listsFlag || !$listsFlag->value)) {
            return to_route('emailLists');
        }
        $this->currentStep = 'start';
    }

    public function poll()
    {
        $this->campaigns = Task::query()
            ->with(['emails'])
            ->get()
            ->toArray();
    }

    public function test()
    {
        $this->validate();

        // configure mailer in config/mail.php
        config([
            'mail.mailers.own.transport' => 'smtp',
            'mail.mailers.own.host' => $this->server,
            'mail.mailers.own.port' => $this->port,
            'mail.mailers.own.encryption' => $this->encryption === 'none' ? null : $this->encryption,
            'mail.mailers.own.username' => $this->username,
            'mail.mailers.own.password' => $this->password,
            'mail.from' => [
                'address' => $this->recipients,
                'name' => $this->name,
            ],
        ]);

        try {
            Mail::mailer('own')->to($this->recipients)->send(new MailTest());
        } catch (\Exception|\TypeError $e) {
            Notification::title('Bit-Bridge')
                ->message('E-Mail konnte nicht versendet werden.')
                ->show();
            $this->testSent = false;
            return;
        }

        Notification::title('Bit-Bridge')
            ->message('Eine Test-E-Mail wurde an deine Adresse ' . $this->recipients . ' versendet.')
            ->show();

        // save config in json file
        $jsonFilePath = 'settings.json';
        $settingsFromJsonFile = Storage::get($jsonFilePath);
        $settingsArray['recipients'] = $this->recipients;
        $settingsArray['mail.from']['name'] = $this->name;
        $settingsArray['mail.mailers.own.username'] = $this->username;
        $settingsArray['mail.mailers.own.host'] = $this->server;
        $settingsArray['mail.mailers.own.port'] = $this->port;
        $settingsArray['mail.mailers.own.encryption'] = $this->encryption === 'none' ? null : $this->encryption;
        $settingsArray['mail.mailers.own.password'] = $this->password;
        Storage::put($jsonFilePath, Crypt::encryptString((json_encode($settingsArray, JSON_THROW_ON_ERROR))));

        Flag::query()->firstOrCreate([
            'name' => 'smtp_settings',
        ], [
            'value' => true,
        ]);
    }

    public function render()
    {
        return view('livewire.start-screen', [
            'countLists' => Tag::query()->count(),
            'countTexts' => Text::query()->count(),
            'emailListsOptions' => Tag::query()->get()->map(fn(Tag $tag) => [
                'label' => $tag->name,
                'value' => $tag->name,
            ]),
            'emailTypeOptions' => Text::query()
                ->select('name')
                ->distinct()
                ->get()
                ->map(fn(Text $text) => [
                    'label' => $text->name,
                    'value' => $text->name,
                ]),
            'encryptionOptions' => [
                [
                    'label' => 'Keine',
                    'value' => 'none',
                ],
                [
                    'label' => 'SSL',
                    'value' => 'ssl',
                ],
                [
                    'label' => 'TLS',
                    'value' => 'tls',
                ],
            ],
        ]);
    }
}
