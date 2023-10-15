<?php

namespace App\Console\Commands;

use App\Mail\MailTest;
use App\Models\Email;
use App\Models\Task;
use App\Models\Text;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
        $jsonFilePath = 'settings.json';
        $settingsFromJsonFile = Storage::get($jsonFilePath);
        $settingsArray = json_decode(Crypt::decryptString($settingsFromJsonFile), true, 512, JSON_THROW_ON_ERROR);
        config([
            'mail.mailers.own.transport' => 'smtp',
            'mail.mailers.own.host' => $settingsArray['mail.mailers.own.host'],
            'mail.mailers.own.port' => $settingsArray['mail.mailers.own.port'],
            'mail.mailers.own.encryption' => $settingsArray['mail.mailers.own.encryption'],
            'mail.mailers.own.username' => $settingsArray['mail.mailers.own.username'],
            'mail.mailers.own.password' => $settingsArray['mail.mailers.own.password'],
            'mail.from' => [
                'address' => $settingsArray['recipients'],
                'name' => $settingsArray['mail.from']['name'],
            ],
        ]);
        foreach ($tasks as $task) {
            $type = $task->text_type;
            foreach ($task->emails as $email) {
                $sendAt = Carbon::parse($email->send_at);
                if ($sendAt->diffInSeconds(now()) > 60) {
                    $email->update([
                        'send_at' => $sendAt->addHour(),
                    ]);
                    continue;
                }
                $randomText = Text::query()
                    ->where('name', $type)
                    ->inRandomOrder()
                    ->first();
                if ($randomText) {
                    try {
                        Mail::mailer('own')
                            ->to($email->emailAddress->address)
                            ->send(new MailTest($email->emailAddress, $randomText));
                    } catch (\Exception $e) {
                        Log::error($e->getMessage());
                        continue;
                    }
                    $email->update([
                        'sent_at' => now()
                    ]);
                }
            }
        }
    }
}
