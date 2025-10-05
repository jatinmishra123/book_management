<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriptionTypeController extends Controller
{
    /**
     * Display a listing of the subscription types.
     */
    public function index()
    {
        $subscriptionTypes = SubscriptionType::orderBy('id', 'desc')->paginate(10);
        return view('admin.subscription_types.index', compact('subscriptionTypes'));
    }

    /**
     * Store a newly created subscription type in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'number_of_days' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        SubscriptionType::create($validatedData);

        return redirect()->back()->with('success', 'Subscription type added successfully!');
    }

    /**
     * Show the specified subscription type for editing (used for AJAX requests).
     */
    public function edit(SubscriptionType $subscriptionType)
    {
        return response()->json($subscriptionType);
    }

    /**
     * Update the specified subscription type in storage.
     */
    public function update(Request $request, SubscriptionType $subscriptionType)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'number_of_days' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $subscriptionType->update($validatedData);

        return redirect()->back()->with('success', 'Subscription type updated successfully!');
    }

    /**
     * Remove the specified subscription type from storage.
     */
    public function destroy(SubscriptionType $subscriptionType)
    {
        $subscriptionType->delete();
        return redirect()->back()->with('success', 'Subscription type deleted successfully.');
    }

    /**
     * Export subscription types to CSV.
     */
    public function exportCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="subscription_types.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['S.No.', 'Title', 'Amount', 'Number of Days', 'Description', 'Created At']);

            $subscriptionTypes = SubscriptionType::all();
            foreach ($subscriptionTypes as $index => $subscriptionType) {
                fputcsv($file, [
                    $index + 1,
                    $subscriptionType->title,
                    $subscriptionType->amount,
                    $subscriptionType->number_of_days,
                    $subscriptionType->description,
                    $subscriptionType->created_at,
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Export subscription types to Excel (using CSV format for simplicity).
     */
    public function exportExcel()
    {
        $headers = [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="subscription_types.xls"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['S.No.', 'Title', 'Amount', 'Number of Days', 'Description', 'Created At'], "\t");

            $subscriptionTypes = SubscriptionType::all();
            foreach ($subscriptionTypes as $index => $subscriptionType) {
                fputcsv($file, [
                    $index + 1,
                    $subscriptionType->title,
                    $subscriptionType->amount,
                    $subscriptionType->number_of_days,
                    $subscriptionType->description,
                    $subscriptionType->created_at,
                ], "\t");
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}