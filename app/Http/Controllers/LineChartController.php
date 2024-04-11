<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Debit;
use App\Models\Finance;
use App\Models\Debt;
use Illuminate\Support\Facades\Auth;
use App\Models\Income;

class LineChartController extends Controller
{
    public function monthlyExpensesByCategory()
    {
        $userId = Auth::id();
        $monthlyExpensesByCategory = Expense::selectRaw('MONTH(date) as month, category, SUM(amount) as total_amount')
            ->where('user_id', $userId) 
            ->where('date', '>=', Carbon::now()->subYear())
            ->groupBy('month', 'category')
            ->orderBy('month', 'asc')
            ->get();
        return response()->json($monthlyExpensesByCategory);
    }

    public function weeklyExpensesByCategory()
    {
        $userId = Auth::id(); 
        $weeklyExpensesByCategory = Expense::selectRaw('WEEK(date) as week, category, SUM(amount) as total_amount')
            ->where('user_id', $userId)
            ->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy('week', 'category')
            ->orderBy('week', 'asc')
            ->get();

        return response()->json($weeklyExpensesByCategory);
    }

    public function monthlyExpensesArea()
    {
        $userId = Auth::id(); // Fetch the ID of the currently authenticated user

        // Fetch monthly expenses for the past year, scoped to the authenticated user
        $monthlyExpenses = Expense::selectRaw('DATE_FORMAT(date, "%Y-%m") AS month')
            ->selectRaw('SUM(amount) AS total_amount')
            ->where('user_id', $userId) // Ensure expenses are filtered by the authenticated user's ID
            ->whereYear('date', '=', now()->subYear()->year) // Fetch expenses for the past year
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json($monthlyExpenses);
    }

    public function monthlyExpensesTotal()
    {
        $userId = Auth::id(); // Fetch the ID of the currently authenticated user

        // Calculate the total expenses for the current month, scoped to the authenticated user
        $currentMonthExpensesTotal = Expense::where('user_id', $userId) // Ensure expenses are filtered by the authenticated user's ID
            ->whereYear('date', '=', now()->year)
            ->whereMonth('date', '=', now()->month)
            ->sum('amount');

        return response()->json(['total' => $currentMonthExpensesTotal]);
    }

    public function monthlyExpensesSummary()
    {
        $userId = Auth::id();

        // Fetch monthly expenses total, scoped to the authenticated user
        $currentMonthExpensesTotal = Expense::where('user_id', $userId) // Ensure expenses are filtered by the authenticated user's ID
            ->whereYear('date', '=', now()->year)
            ->whereMonth('date', '=', now()->month)
            ->sum('amount');

        // Fetch most used category, scoped to the authenticated user
        $mostUsedCategory = Expense::select('category', DB::raw('COUNT(*) as count'))
            ->where('user_id', $userId) // Ensure expenses are filtered by the authenticated user's ID
            ->whereYear('date', '=', now()->year)
            ->whereMonth('date', '=', now()->month)
            ->groupBy('category')
            ->orderByDesc('count')
            ->first();

        return response()->json([
            'total' => $currentMonthExpensesTotal,
            'most_used_category' => $mostUsedCategory ? $mostUsedCategory->category : 'N/A'
        ]);
    }

    public function lastMonthExpenses()
    {
        $userId = Auth::id();
        // Calculate the date for the last month
        $lastMonth = Carbon::now()->subMonth();

        // Fetch expenses for the last month, scoped to the authenticated user
        $lastMonthExpenses = Expense::selectRaw('YEAR(date) as year, MONTH(date) as month, SUM(amount) as total_amount')
            ->where('user_id', $userId) // Ensure expenses are filtered by the authenticated user's ID
            ->whereYear('date', '=', $lastMonth->year)
            ->whereMonth('date', '=', $lastMonth->month)
            ->get();

        return response()->json($lastMonthExpenses);
    }


    public function upcomingDirectDebits()
    {
        $userId = Auth::id();
        $today = Carbon::now()->format('Y-m-d');

        $upcomingDirectDebits = Debit::where('user_id', $userId)
            ->where('reoccurance_date', '>=', $today)
            ->orderBy('reoccurance_date', 'asc')
            ->get();

        return response()->json($upcomingDirectDebits);
    }


    public function getFinances()
    {
        $userId = Auth::id();
        // Fetch finances scoped to the authenticated user
        $finances = Finance::where('user_id', $userId)->get(); // Ensure finances are filtered by the authenticated user's ID

        return response()->json($finances); // Return user-specific finances as JSON
    }

    public function showDashboard() 
    {
        $userId = Auth::id();
        $salesData = [
            'labels' => ['January', 'February', 'March', 'April'],
            'data' => [150, 200, 180, 220]
        ];
        return view('container', compact('salesData'));
    }

        public function debtsWithinMonth()
    {
        $userId = Auth::id();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $debtsWithinMonth = Debt::where('user_id', $userId)
                                ->whereBetween('payback_deadline', [$startOfMonth, $endOfMonth])
                                ->get()
                                ->map(function ($debt) {
                                    $debt->remaining = $debt->amount - $debt->paid; // Calculate remaining debt
                                    return $debt;
                                });

        return response()->json($debtsWithinMonth);
    }

    public function expenseCategoriesForDoughnutChart()
    {
        $userId = Auth::id();
        // Fetch expense categories and their total amounts for the current month, scoped to the authenticated user
        $expenseCategories = Expense::select('category', DB::raw('SUM(amount) as total_amount'))
            ->where('user_id', $userId) // Ensure expenses are filtered by the authenticated user's ID
            ->whereYear('date', '=', now()->year)
            ->whereMonth('date', '=', now()->month)
            ->groupBy('category')
            ->get();

        return response()->json($expenseCategories);
    }
    public function incomeOverTimeForLineChart()
    {
        $userId = Auth::id();
        // Fetch dates and amounts of incomes for the current month, scoped to the authenticated user
        $dailyIncomes = Income::select(DB::raw('DATE(date) as day'), DB::raw('SUM(value) as total_value'))
            ->where('user_id', $userId) // Ensure incomes are filtered by the authenticated user's ID
            ->whereYear('date', '=', now()->year)
            ->whereMonth('date', '=', now()->month)
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('day', 'ASC')
            ->get();

        // Prepare data for the line chart
        $chartData = [
            'labels' => $dailyIncomes->pluck('day')->all(),
            'datasets' => [
                [
                    'label' => "Total Income",
                    'data' => $dailyIncomes->pluck('total_value')->all(),
                    'fill' => false,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'lineTension' => 0.1
                ]
            ]
        ];

        return response()->json($chartData);
    }
}
