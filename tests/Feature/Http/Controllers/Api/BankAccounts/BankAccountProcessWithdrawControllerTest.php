<?php

use App\Enums\TransactionType;
use App\Models\BankAccount;
use App\Models\Transaction;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\postJson;
use function Pest\Laravel\withHeader;

beforeEach(function () {
    $this->account = BankAccount::factory()
        ->hasAddress(1)
        ->has(
            Transaction::factory()
                ->state([
                    'type' => TransactionType::DEPOSIT->value,
                    'amount' => 1000000,
                ])
        )
        ->create();
});

it('withdraws returns 403 when api_key is not passed', function () {
    postJson("/api/bank-account/{$this->account->account_number}/withdraw", ['amount' => rand(100000, 1000000)])
        ->assertStatus(403);
});

it('withdraws returns 403 when api_key is wrong', function () {
    withHeader('X-API-KEY', 'WRONG_KEY')
        ->postJson("/api/bank-account/{$this->account->account_number}/withdraw", ['amount' => rand(100000, 1000000)])
        ->assertStatus(403);
});

it('withdraws are successfully processed', function () {
    withHeader('X-API-KEY', config('app.wallets_api_key'))
        ->postJson("/api/bank-account/{$this->account->account_number}/withdraw", ['amount' => $amount = rand(100000, 1000000)])
        ->assertStatus(200);

    assertDatabaseHas('transactions', [
        'type' => TransactionType::WITHDRAW->value,
        'amount' => $amount * 100,
    ]);
});

it('withdraws fails when balance is not enough', function () {
    withHeader('X-API-KEY', config('app.wallets_api_key'))
        ->postJson("/api/bank-account/{$this->account->account_number}/withdraw", ['amount' => 2000000])
        ->assertServerError();
});
