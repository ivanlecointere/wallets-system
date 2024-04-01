<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        if (auth()->user()->hasRole('admin')) {
            return Inertia::render('AdminDashboard');
        }

        return Inertia::render('Dashboard', [
            'account' => $account = auth()->user()->bankAccount->append('balance'),
            'currency' => config('app.currency'),
            'transactions' => $account->transactions()->with('address')->orderBy('created_at', 'DESC')->byDateRange($request->range_from, $request->range_to)->paginate(10),
            'incomes' => $account->calculateIncomeByRange($request->range_from, $request->range_to),
            'expenses' => $account->calculateExpensesByRange($request->range_from, $request->range_to),
        ]);
    }
}
