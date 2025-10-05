<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    /**
     * Display a listing of the vendors.
     */
    public function index()
    {
        // Fetches all vendors and paginates them, with the latest ones appearing first.
        $vendors = Vendor::orderBy('id', 'desc')->paginate(10);

        // Returns the 'admin.vendor' view with the paginated vendors data.
        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * Store a newly created vendor in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:vendors,email', // Correct validation for email
            'hall' => 'required|string|max:255',
            'floor' => 'required|integer|in:1,2,3',
            'seat' => 'required|integer|min:0|max:100',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            Vendor::create($request->all());
            return response()->json(['message' => 'Vendor added successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected server error occurred.'], 500);
        }
    }

    /**
     * Show the specified vendor for editing (used for AJAX requests).
     */
    public function edit(Vendor $vendor)
    {
        // Returns the vendor data as a JSON response, which the JavaScript on the front end will use to populate the modal form.
        return response()->json($vendor);
    }
    public function editJson(Vendor $vendor)
{
    return response()->json($vendor);
}


    /**
     * Update the specified vendor in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validator = Validator::make($request->all(), [
            'vendor_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:vendors,email,' . $vendor->id, // Correct validation for email uniqueness
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
            return response()->json(['message' => 'Vendor updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected server error occurred.'], 500);
        }
    }

    /**
     * Remove the specified vendor from storage.
     */
    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return response()->json(['message' => 'Vendor deleted successfully!'], 200);
    }
}