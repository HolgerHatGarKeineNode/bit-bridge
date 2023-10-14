<?php

namespace App\Livewire;

use App\Mail\MailTest;
use App\Models\Email;
use App\Models\EmailAddress;
use App\Models\Tag;
use App\Models\Task;
use Illuminate\Support\Facades\Mail;
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
    public $recipients = 'test1@test.de';

    public $count = 0;
    public $warning = false;
    public $testSent = false;

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

        Mail::mailer('own')->to($this->recipients)->send(new MailTest());

        Notification::title('Bit-Bridge')
            ->message('Eine Test-E-Mail wurde an deine Adresse ' . $this->recipients . ' versendet.')
            ->show();

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
            'email_type' => $this->type,
            'email_list' => $this->list,
            'started_at' => now(),
            'finished_at' => null,
        ]);
        foreach ($addresses as $address) {
            $randomDateTimeWithinWithPrecisionOfMinutes = now()->addMinutes(rand(0, 60 * 72));
            Email::query()->create([
                'task_id' => $task->id,
                'email_address_id' => $address->id,
                'send_at' => $randomDateTimeWithinWithPrecisionOfMinutes,
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
            'emailTypeOptions' => [
                [
                    'label' => 'Orange Pill',
                    'value' => 'orange_pill',
                ],
                [
                    'label' => 'Bitcoin Advocacy Blast',
                    'value' => 'bitcoin_advocacy_blast',
                ],
            ],
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
