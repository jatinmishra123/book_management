<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionType;
use Illuminate\Http\Request;

class SubscriptionTypeController extends Controller
{
    /**
     * Display a listing of subscription types.
     */
    public function index()
    {
        $subscriptionTypes = SubscriptionType::orderBy('id', 'desc')->paginate(10);
        return response()->json($subscriptionTypes);
    }

    /**
     * Store a newly created subscription type.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'number_of_days' => 'required|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $subscriptionType = SubscriptionType::create($validatedData);

        return response()->json([
            'message' => 'Subscription type added successfully!',
            'subscriptionType' => $subscriptionType
        ], 201);
    }

    /**
     * Display the specified subscription type.
     */
    public function show(SubscriptionType $subscriptionType)
    {
        return response()->json($subscriptionType);
    }

    /**
     * Update the specified subscription type.
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

        return response()->json([
            'message' => 'Subscription type updated successfully!',
            'subscriptionType' => $subscriptionType
        ]);
    }

    /**
     * Remove the specified subscription type.
     */
    public function destroy(SubscriptionType $subscriptionType)
    {
        $subscriptionType->delete();

        return response()->json([
            'message' => 'Subscription type deleted successfully.'
        ]);
    }
}
