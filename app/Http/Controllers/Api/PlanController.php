<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    // ✅ Create or Update Plan for a Vendor
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        // Validation (plan must be array)
        $request->validate([
            'ac' => 'nullable|string|max:100',
            'nonac' => 'nullable|string|max:100',
            'type3' => 'nullable|string|max:100',
            'plan' => 'nullable|array', // ✅ JSON array/object allowed
        ]);

        // Save (Laravel automatically encodes array → JSON if casted)
        $plan = Plan::updateOrCreate(
            ['vendor_id' => $user->id],
            [
                'ac' => $request->ac,
                'nonac' => $request->nonac,
                'type3' => $request->type3,
                'plan' => $request->plan, // ✅ array will be saved as JSON
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Plan saved successfully',
            'data' => $plan
        ]);
    }

    // ✅ Get Vendor Plan
    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $plan = Plan::where('vendor_id', $user->id)->first();

        return response()->json([
            'success' => true,
            'data' => $plan
        ]);
    }
}
