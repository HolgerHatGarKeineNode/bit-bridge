<?php

namespace Database\Factories;

use App\Models\EmailAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Email>
 */
class EmailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $email = EmailAddress::factory(1)->create();

        return [
            'task_id' => 1,
            'email_address_id' => $email->first()->id,
            'send_at' => now()->addSeconds(random_int(60 * 60, 60 * 60 * 24 * 7)),
            'sent_at' => null,
        ];
    }
}
