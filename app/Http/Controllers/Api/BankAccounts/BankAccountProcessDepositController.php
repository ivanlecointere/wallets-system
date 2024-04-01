<?php

namespace App\Http\Controllers\Api\BankAccounts;

use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\BankAccounts\BankAccountProcessTransaction;
use App\Models\Address;
use App\Models\BankAccount;

class BankAccountProcessDepositController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(BankAccountProcessTransaction $request, BankAccount $bankAccount)
    {
        $transaction = $bankAccount->transactions()->create([
            'amount' => $request->amount,
            'type' => TransactionType::DEPOSIT->value,
        ]);

        Address::factory()->for($transaction, 'addressable')->create();

        return response()->json(['message' => 'Success.']);
    }
}
