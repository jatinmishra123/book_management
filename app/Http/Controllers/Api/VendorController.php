<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VendorController extends Controller
{
    /**
     * Display a listing of vendors.
     */
    public function index()
    {
        try {
            $vendors = Vendor::orderBy('id', 'desc')->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Vendors fetched successfully!',
                'data' => $vendors
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vendors.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created vendor and generate token.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'library_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20|unique:vendors,mobile_number',
            'email' => 'required|email|max:255|unique:vendors,email',
            'address' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vendor = Vendor::create([
                'full_name' => $request->full_name,
                'library_name' => $request->library_name,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'address' => $request->address,
                'password' => Hash::make($request->password),
            ]);

            $token = $vendor->createToken('vendor_auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Vendor registered successfully!',
                'data' => $vendor,
                'token' => $token,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register vendor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vendor Login with email or mobile_number + password
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vendor = Vendor::where('email', $request->login)
                ->orWhere('mobile_number', $request->login)
                ->first();

            if (!$vendor || !Hash::check($request->password, $vendor->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid login credentials',
                ], 401);
            }

            // Delete old tokens
            $vendor->tokens()->delete();

            // New token
            $token = $vendor->createToken('vendor_auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful!',
                'data' => $vendor,
                'token' => $token,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout vendor
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logout successful!',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified vendor.
     */
    public function show($id)
    {
        try {
            $vendor = Vendor::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Vendor fetched successfully!',
                'data' => $vendor
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor not found.'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vendor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified vendor.
     */
    public function update(Request $request, $id)
    {
        try {
            $vendor = Vendor::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:255',
                'library_name' => 'required|string|max:255',
                'mobile_number' => 'required|string|max:20|unique:vendors,mobile_number,' . $vendor->id,
                'email' => 'required|email|max:255|unique:vendors,email,' . $vendor->id,
                'address' => 'required|string|max:255',
                'password' => 'nullable|string|min:6|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $validator->errors()
                ], 422);
            }

            $vendor->update([
                'full_name' => $request->full_name,
                'library_name' => $request->library_name,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'address' => $request->address,
                'password' => $request->password ? Hash::make($request->password) : $vendor->password,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vendor updated successfully!',
                'data' => $vendor,
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor not found.'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vendor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified vendor.
     */
    public function destroy($id)
    {
        try {
            $vendor = Vendor::findOrFail($id);
            $vendor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vendor deleted successfully!'
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor not found.'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vendor.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
