<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    // Display list of admins
    public function index()
    {
        $admins = Admin::orderBy('id', 'desc')->paginate(10);
        return view('admin.roles.index', compact('admins'));
    }

    // Store new admin
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
            Admin::create($validated);
            return redirect()->back()->with('success', 'Admin user added successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to add admin: ' . $e->getMessage());
        }
    }

    // Return admin data for modal edit
    public function edit(Admin $admin)
    {
        return response()->json($admin);
    }

    // Update admin
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
            return redirect()->back()->with('success', 'Admin user updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update admin: ' . $e->getMessage());
        }
    }

    // Delete admin
    public function destroy(Admin $admin)
    {
        try {
            $admin->delete();
            return redirect()->back()->with('success', 'Admin user deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete admin: ' . $e->getMessage());
        }
    }
}
    