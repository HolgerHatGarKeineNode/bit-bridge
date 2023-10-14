<?php

namespace App\Livewire;

use App\Mail\MailTest;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Native\Laravel\Facades\Notification;

class StartScreen extends Component
{
    #[Rule('required')]
    public $username = 'Bit-Bridge';

    public $password = null;

    #[Rule('required')]
    public $server = '127.0.0.1';

    #[Rule('required')]
    public $port = 2525;

    #[Rule('required')]
    public $recipients = 'test1@test.de,test2@test.de';

    public function send()
    {
        $this->validate();

        // configure mailer in config/mail.php
        config([
            'mail.mailers.own.transport' => 'smtp',
            'mail.mailers.own.host' => $this->server,
            'mail.mailers.own.port' => $this->port,
            'mail.mailers.own.encryption' => null,
            'mail.mailers.own.username' => $this->username,
            'mail.mailers.own.password' => $this->password,
        ]);

        foreach (str($this->recipients)->explode(',') as $recipient) {
            Mail::mailer('own')->to($recipient)->send(new MailTest());
        }

        Notification::title('Hello from NativePHP')
            ->message('This is a detail message coming from your Laravel app.')
            ->show();
    }

    public function render()
    {
        return view('livewire.start-screen');
    }
}
