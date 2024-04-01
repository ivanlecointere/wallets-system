<?php

namespace Database\Factories;

use App\Enums\BankAccountType;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankAccount>
 */
class BankAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create()->assignRole('client'),
            'account_number' => $this->faker->numerify('####-####-####-####'),
            'type' => function (array $attributes) {
                return User::find($attributes['user_id'])->type === UserType::NATURAL ?
                    BankAccountType::SAVING : BankAccountType::CHECKING;
            },
        ];
    }
}
