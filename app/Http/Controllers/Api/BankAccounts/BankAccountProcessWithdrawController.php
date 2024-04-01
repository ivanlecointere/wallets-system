<?php

namespace App\Http\Controllers\Api\BankAccounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankAccounts\BankAccountProcessWithdrawRequest;
use App\Models\BankAccount;

class BankAccountProcessWithdrawController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(BankAccountProcessWithdrawRequest $request, BankAccount $bankAccount)
    {

    }
}
