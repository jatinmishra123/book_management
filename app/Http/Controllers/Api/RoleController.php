<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of admins.
     */
    public function index()
    {
        $admins = Admin::orderBy('id', 'desc')->paginate(10);
        return response()->json($admins, 200);
    }

    /**
     * Store a newly created admin.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'role' => ['required', 'string', Rule::in(['admin', 'editor', 'viewer'])],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        try {
            $admin = Admin::create($validated);
            return response()->json([
                'message' => 'Admin user added successfully!',
                'admin' => $admin
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to add admin: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified admin.
     */
    public function show(Admin $admin)
    {
        return response()->json($admin, 200);
    }

    /**
     * Update the specified admin.
     */
    public function update(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:20',
            'role' => ['required', 'string', Rule::in(['admin', 'editor', 'viewer'])],
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        try {
            $admin->update($validated);
            return response()->json([
                'message' => 'Admin user updated successfully!',
                'admin' => $admin
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update admin: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified admin.
     */
    public function destroy(Admin $admin)
    {
        try {
            $admin->delete();
            return response()->json(['message' => 'Admin user deleted successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete admin: ' . $e->getMessage()], 500);
        }
    }
}
