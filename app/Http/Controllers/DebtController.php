<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DebtController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $debts = $user->debts()->with(['payments'])->get()->each(function ($debt) {
            $remainingDays = Carbon::now()->diffInDays(Carbon::parse($debt->payback_deadline), false);
            $debt->remaining_days = $remainingDays > 0 ? $remainingDays : 0;
        });

        return view('admin.debts.index', compact('debts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'payback_deadline' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        $user = Auth::user();
        $debt = new Debt($request->all());
        $user->debts()->save($debt);

        return redirect()->route('debts.index')->with('success', 'Debt logged successfully.');
    }

    public function destroy(Debt $debt)
    {
        if ($debt->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $debt->delete();
        return back()->with('success', 'Debt removed successfully.');
    }

    public function payDebt(Request $request)
    {
        $request->validate([
            'debt_id' => 'required|exists:debts,id',
            'payment_amount' => 'required|numeric|min:0.01',
        ]);

        $debt = Debt::findOrFail($request->debt_id);

        if ($debt->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $payment = new Payment([
            'amount' => $request->payment_amount,
            'payment_date' => now(), 
        ]);

        $debt->payments()->save($payment);

        $debt->amount -= $request->payment_amount;
        if($debt->amount <= 0) {
            $debt->amount = 0; 
            $debt->status = 'paid'; 
        }
        $debt->save();

        return back()->with('success', 'Payment logged successfully.');
    }

}
