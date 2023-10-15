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
        // without faker
        $randomDateTimeWithinNextHourWithPrecisionInMinutes = now()->addMinutes(rand(0, 60));

        return [
            'task_id' => 1,
            'email_address_id' => $email->first()->id,
            'send_at' => $randomDateTimeWithinNextHourWithPrecisionInMinutes,
            'sent_at' => null,
        ];
    }
}
