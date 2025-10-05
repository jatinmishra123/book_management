<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display the authenticated admin's profile.
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return response()->json([
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'phone' => $admin->phone,
            'role' => $admin->role,
            'is_active' => $admin->is_active,
            'created_at' => $admin->created_at,
            'updated_at' => $admin->updated_at,
        ], 200);
    }
}
