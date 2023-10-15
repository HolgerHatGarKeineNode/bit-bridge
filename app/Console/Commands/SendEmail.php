<?php

namespace App\Console\Commands;

use App\Mail\MailTest;
use App\Models\Email;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Sending email: ' . now()->toDateTimeString());

        $tasks = Task::query()
            ->with([
                'emails' => fn($query) => $query
                    ->where('emails.send_at', '<=', now())
                    ->whereNull('emails.sent_at'),
                'emails.emailAddress',
            ])
            ->where('status', 'running')
            ->get();

        // configure mailer in config/mail.php
        config([
            'mail.mailers.own.transport' => 'smtp',
            'mail.mailers.own.host' => '127.0.0.1',
            'mail.mailers.own.port' => 2525,
            'mail.mailers.own.encryption' => null,
            'mail.mailers.own.username' => 'Bit-Bridge',
            'mail.mailers.own.password' => null,
            'mail.from' => [
                'address' => 'sender@test.de',
                'name' => 'Sender Name',
            ],
        ]);

        foreach ($tasks as $task) {
            foreach ($task->emails as $email) {
                $sendAt = Carbon::parse($email->send_at);
                if ($sendAt->diffInSeconds(now()) > 60) {
                    $email->update([
                        'send_at' => $sendAt->addHour(),
                    ]);
                    continue;
                }
                Mail::mailer('own')->to($email->emailAddress->address)->send(new MailTest());
                $email->update([
                    'sent_at' => now()
                ]);
            }
        }
    }
}
