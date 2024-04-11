<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $incomes = auth()->user()->incomes()->get();
        return view('admin.income.index', compact('incomes'));
    }

    public function create()
    {
        return view('income.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'source' => 'required|string|max:255',
            'date' => 'required|date',
            'value' => 'required|numeric',
        ]);

        $income = new Income([
            'user_id' => auth()->id(),
            'source' => $request->source,
            'date' => $request->date,
            'value' => $request->value,
        ]);

        $income->save();

        return redirect()->route('income.index')->with('success', 'Income added successfully.');
    }

    public function edit($id)
    {
        $income = Income::findOrFail($id);

        if ($income->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('income.update', compact('income'));
    }

    public function update(Request $request, $id)
    {
        $income = Income::findOrFail($id);

        if ($income->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'source' => 'required|string|max:255',
            'date' => 'required|date',
            'value' => 'required|numeric',
        ]);

        $income->update($data);

        return redirect()->route('income.index')->with('success', 'Income updated successfully.');
    }

    public function destroy($id)
    {
        $income = Income::findOrFail($id);

        // Check if the income belongs to the authenticated user
        if ($income->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $income->delete();

        return redirect()->back()->with('success', 'Income deleted successfully.');
    }

}
