<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return Response
     */
    public function __invoke(): Response
    {
        if (auth()->user()->hasRole('admin')) {
            return Inertia::render('AdminDashboard');
        }

        return Inertia::render('Dashboard', [
            'account' => $account = auth()->user()->bankAccount->append('balance'),
            'transactions' => $account->transactions()->orderBy('created_at', 'DESC')->paginate(20),
        ]);
    }
}
