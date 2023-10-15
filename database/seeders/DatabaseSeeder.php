<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Email;
use App\Models\EmailAddress;
use App\Models\Tag;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

//        Task::factory(1)
//            ->hasEmails(100)
//            ->create();

//        $tags = [
//            'FDP',
//            'SPD',
//            'CDU',
//        ];
//        foreach (EmailAddress::query()->get() as $item) {
//            $randomTag = $tags[array_rand($tags)];
//
//            $item->attachTag($randomTag);
//        }

        $tags = [
            'SPD',
            'CDU',
        ];
        // loop 40 times
        for ($i = 0; $i < 40; $i++) {
            $email = EmailAddress::query()->create([
                'address' => 'test' . $i . '@example.com',
                'name' => 'Test ' . $i,
            ]);
            if ($i % 2 === 0) {
                $email->attachTag('FDP');
            } else {
                $email->attachTag('SPD');
            }
        }
    }
}
