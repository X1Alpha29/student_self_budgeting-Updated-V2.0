<?php

namespace App\Http\Controllers;

use App\Models\Debit;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DebitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch debits associated with the authenticated user
        $userId = Auth::id();
        $debits = Debit::where('user_id', $userId)->get();
        
        // Fetch upcoming direct debits within the next week
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $upcomingDirectDebits = Debit::where('user_id', $userId)
            ->whereBetween('reoccurance_date', [$startOfWeek, $endOfWeek])
            ->get();
        
        // Pass data to the view
        return view('admin.debits.index', compact('debits', 'upcomingDirectDebits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $userId = Auth::id();

        $debitData = $request->all();
        $debitData['user_id'] = $userId;

        if (empty($debitData['details'])) {
            $debitData['details'] = 'No details provided'; 
        }

        Debit::create($debitData);

        return back()->with('success', 'Direct Debit added successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $userId = Auth::id();
        $debit = Debit::where('user_id', $userId)->findOrFail($id);
        return view('debits.edit', compact('debit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $debit = Debit::where('user_id', $userId)->findOrFail($id);

        $updatedData = $request->all();
        if (empty($updatedData['details'])) {
            $updatedData['details'] = 'No details provided';
        }

        $debit->update($updatedData);

        return redirect()->route('debits.index')->with('success', 'Direct Debit updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userId = Auth::id();
        $debit = Debit::where('user_id', $userId)->findOrFail($id);
        $debit->delete();
        return back();
    }
}
