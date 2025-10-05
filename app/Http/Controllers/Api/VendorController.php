<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    /**
     * Display a listing of vendors.
     */
    public function index()
    {
        $vendors = Vendor::orderBy('id', 'desc')->paginate(10);

        return response()->json($vendors, 200);
    }

    /**
     * Store a newly created vendor.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:vendors,email',
            'hall' => 'required|string|max:255',
            'floor' => 'required|integer|in:1,2,3',
            'seat' => 'required|integer|min:0|max:100',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $vendor = Vendor::create($request->all());
            return response()->json([
                'message' => 'Vendor added successfully!',
                'vendor' => $vendor
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected server error occurred.'], 500);
        }
    }

    /**
     * Display the specified vendor.
     */
    public function show(Vendor $vendor)
    {
        return response()->json($vendor, 200);
    }

    /**
     * Update the specified vendor.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validator = Validator::make($request->all(), [
            'vendor_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:vendors,email,' . $vendor->id,
            'hall' => 'required|string|max:255',
            'floor' => 'required|integer|in:1,2,3',
            'seat' => 'required|integer|min:0|max:100',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $vendor->update($request->all());
            return response()->json([
                'message' => 'Vendor updated successfully!',
                'vendor' => $vendor
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected server error occurred.'], 500);
        }
    }

    /**
     * Remove the specified vendor.
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return response()->json(['message' => 'Vendor deleted successfully!'], 200);
    }
}
    