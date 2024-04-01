<?php

use App\Http\Controllers\Api\BankAccounts\BankAccountProcessWithdrawController;
use Illuminate\Support\Facades\Route;

Route::middleware('with_wallets_api_key')->group(function () {
    Route::post('/bank-account/{bank_account:account_number}/withdraw', BankAccountProcessWithdrawController::class)->name('bank-account.withdraw');
});
