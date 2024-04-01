<?php

namespace Database\Seeders;

use App\Enums\TransactionType;
use App\Enums\UserType;
use App\Models\BankAccount;
use App\Models\Transaction;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'client']);
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@admin.com',
            'type' => UserType::NATURAL->value,
            'password' => '$2y$10$FdoXV.iGdbt.ssD3qJqjQ.1F1UGP1vZvvoMyx7qNrlLjYYh2z1D.O', // 12345678
        ])->assignRole('admin');

        $client = User::factory()
            ->has(
                BankAccount::factory()
                    ->state(['account_number' => '0000-0000-0000-0000'])
                    ->hasAddress(1)
            )
            ->create([
                'name' => 'Client',
                'email' => 'client@client.com',
                'type' => UserType::NATURAL->value,
                'password' => '$2y$10$FdoXV.iGdbt.ssD3qJqjQ.1F1UGP1vZvvoMyx7qNrlLjYYh2z1D.O', // 12345678
            ])->assignRole('client');

        $startDate = now()->subYear();
        $endDate = now();

        CarbonPeriod::between($startDate, $endDate)->interval(CarbonInterval::day(1))
            ->forEach(function ($date) use (
                $client
            ) {
                Transaction::factory()
                    ->state([
                        'bank_account_id' => $client->bankAccount->id,
                        'type' => TransactionType::DEPOSIT->value,
                        'amount' => rand(100000, 500000),
                        'created_at' => $date,
                        'updated_at' => $date,
                    ])
                    ->hasAddress(1)
                    ->create();

                Transaction::factory()
                    ->state([
                        'bank_account_id' => $client->bankAccount->id,
                        'type' => TransactionType::WITHDRAW->value,
                        'amount' => rand(5000, 100000),
                        'created_at' => $date,
                        'updated_at' => $date,
                    ])
                    ->hasAddress(1)
                    ->create();
            });

        BankAccount::factory(30)
            ->hasAddress(1)
            ->has(
                Transaction::factory()
                    ->state(['type' => TransactionType::DEPOSIT->value])
                    ->hasAddress(1)
                    ->count(30)
            )
            ->create();
    }
}
