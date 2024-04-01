<?php

namespace App\Http\Controllers\Api\BankAccounts;

use App\Enums\TransactionType;
use App\Http\Controllers\Controller;
use App\Http\Requests\BankAccounts\BankAccountProcessTransaction;
use App\Models\Address;
use App\Models\BankAccount;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class BankAccountProcessWithdrawController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(BankAccountProcessTransaction $request, BankAccount $bankAccount)
    {
        DB::transaction(function() use($bankAccount, $request){
            $depositsTotal = Transaction::where([
                'bank_account_id' => $bankAccount->id,
                'type' => TransactionType::DEPOSIT->value,
            ])
                ->sharedLock()
                ->sum('amount');

            $withdrawsTotal = Transaction::where([
                'bank_account_id' => $bankAccount->id,
                'type' => TransactionType::WITHDRAW->value,
            ])
                ->sharedLock()
                ->sum('amount');

            if ($depositsTotal - $withdrawsTotal < $request->amount * 100) {
                throw new \Exception('Insufficient funds.');
            }

            $transaction = $bankAccount->transactions()->create([
                'amount' => $request->amount,
                'type' => TransactionType::WITHDRAW->value,
            ]);

            Address::factory()->for($transaction, 'addressable')->create();
        });

        return response()->json(['message' => 'Success.']);
    }
}
