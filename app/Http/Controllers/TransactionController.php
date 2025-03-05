<?php

namespace App\Http\Controllers;
use App\Models\SteelSheet;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{


    public function add(Request $request, $id)
{
    $steelSheet = SteelSheet::findOrFail($id);

    $validated = $request->validate([
        'quantity' => 'required|integer|min:1', // Ensure quantity is positive
    ]);

    try {
        $steelSheet->increment('total_count', $validated['quantity']);
        Transaction::create([
            'steel_sheet_id' => $id,
            'quantity' => $validated['quantity'],
        ]);

        return response()->json(['message' => 'Inventory updated successfully.'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to update inventory.', 'details' => $e->getMessage()], 500);
    }
}

public function subtract(Request $request, $id)
{
    $steelSheet = SteelSheet::findOrFail($id);

    $validated = $request->validate([
        'quantity' => 'required|integer|min:1', // Ensure quantity is positive
    ]);

    if ($steelSheet->total_count < $validated['quantity']) {
        return response()->json(['error' => 'Not enough stock to subtract.'], 400);
    }

    try {
        $steelSheet->decrement('total_count', $validated['quantity']);
        Transaction::create([
            'steel_sheet_id' => $id,
            'quantity' => -$validated['quantity'], // Negative value for subtraction
        ]);

        return response()->json(['message' => 'Inventory updated successfully.'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to update inventory.', 'details' => $e->getMessage()], 500);
    }
}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
