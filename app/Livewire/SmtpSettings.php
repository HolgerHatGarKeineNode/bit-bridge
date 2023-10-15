<?php

namespace App\Livewire;

use App\Mail\MailTest;
use App\Models\Email;
use App\Models\EmailAddress;
use App\Models\Tag;
use App\Models\Task;
use App\Models\Text;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Native\Laravel\Facades\Notification;

class SmtpSettings extends Component
{
    #[Rule('required')]
    public $name = '';

    #[Rule('required')]
    public $username = 'Bit-Bridge';

    public $password = null;

    #[Rule('required')]
    public $server = '127.0.0.1';

    #[Rule('required')]
    public $encryption = 'none';

    #[Rule('required')]
    public $port = 2525;

    #[Rule('required')]
    public $type = '';

    #[Rule('required')]
    public $list = '';

    #[Rule('required')]
    public $recipients = '';

    public $count = 0;
    public $warning = false;
    public $testSent = false;

    public function mount()
    {
        $jsonFilePath = 'settings.json';
        $settingsFromJsonFile = Storage::get($jsonFilePath);
        if (!$settingsFromJsonFile) {
            $settingsJson = Crypt::encryptString(json_encode([
                'recipients' => '',
                'mail.mailers.own.transport' => 'smtp',
                'mail.mailers.own.host' => null,
                'mail.mailers.own.port' => 587,
                'mail.mailers.own.encryption' => 'none',
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
        $this->encryption = $settingsArray['mail.mailers.own.encryption'];
        $this->password = $settingsArray['mail.mailers.own.password'];
    }

    public function updated($property)
    {
        if ($property === 'type') {
            if ($this->list) {
                $this->count = EmailAddress::query()
                    ->whereDoesntHave('emails')
                    ->withAnyTags([$this->list])
                    ->count();
                if ($this->count < 1) {
                    $this->warning = true;
                } else {
                    $this->warning = false;
                }
            }
        }
        if ($property === 'list') {
            if ($this->type) {
                $this->count = EmailAddress::query()
                    ->whereDoesntHave('emails')
                    ->withAnyTags([$this->list])
                    ->count();
                if ($this->count < 1) {
                    $this->warning = true;
                } else {
                    $this->warning = false;
                }
            }
        }
    }

    public function test()
    {
        $this->validateOnly('recipients');
        $this->validateOnly('name');
        $this->validateOnly('username');
        $this->validateOnly('server');
        $this->validateOnly('port');
        $this->validateOnly('encryption');
        $this->validateOnly('password');

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
        $settingsArray = json_decode(Crypt::decryptString($settingsFromJsonFile), true, 512, JSON_THROW_ON_ERROR);
        $settingsArray['recipients'] = $this->recipients;
        $settingsArray['mail.from']['name'] = $this->name;
        $settingsArray['mail.mailers.own.username'] = $this->username;
        $settingsArray['mail.mailers.own.host'] = $this->server;
        $settingsArray['mail.mailers.own.port'] = $this->port;
        $settingsArray['mail.mailers.own.encryption'] = $this->encryption;
        $settingsArray['mail.mailers.own.password'] = $this->password;
        Storage::put($jsonFilePath, Crypt::encryptString((json_encode($settingsArray, JSON_THROW_ON_ERROR))));

        $this->testSent = true;
    }

    public function send()
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
        ]);

        $addresses = EmailAddress::query()
            ->withAnyTags([$this->list])
            ->whereDoesntHave('emails')
            ->get();
        $task = Task::query()->create([
            'text_type' => $this->type,
            'email_list' => $this->list,
            'started_at' => now(),
        ]);
        foreach ($addresses as $address) {
            $randomDateTime = now()->addSeconds(rand(0, 60 * 1));
            Email::query()->create([
                'task_id' => $task->id,
                'email_address_id' => $address->id,
                'send_at' => $randomDateTime,
                'sent_at' => null,
            ]);
        }

        Notification::title('Bit-Bridge')
            ->message('Kampagne wurde gestartet.')
            ->show();

        return to_route('task', $task->id);
    }

    public function render()
    {
        return view('livewire.smtp-settings', [
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
