<?php

namespace App\Http\Controllers\Api\BankAccounts;

use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\BankAccounts\BankAccountProcessTransaction;
use App\Models\Address;
use App\Models\BankAccount;
use Illuminate\Http\JsonResponse;

class BankAccountProcessDepositController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param BankAccountProcessTransaction $request
     * @param BankAccount $bankAccount
     * @return JsonResponse
     */
    public function __invoke(BankAccountProcessTransaction $request, BankAccount $bankAccount): JsonResponse
    {
        $transaction = $bankAccount->transactions()->create([
            'amount' => $request->amount,
            'type' => TransactionType::DEPOSIT->value,
        ]);

        Address::factory()->for($transaction, 'addressable')->create();

        return response()->json(['message' => 'Success.']);
    }
}
