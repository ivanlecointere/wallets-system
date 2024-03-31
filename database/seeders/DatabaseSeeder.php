<?php

namespace Database\Seeders;

use App\Enums\UserType;
use App\Models\BankAccount;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        ])->assignRole('admin');

        User::factory()
            ->has(BankAccount::factory())
            ->create([
            'name' => 'Client',
            'email' => 'client@client.com',
            'type' => UserType::NATURAL->value,
        ])->assignRole('client');

        BankAccount::factory(30)
            ->create();
    }
}
