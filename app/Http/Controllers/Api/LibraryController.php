<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Library;
use App\Models\Hall;
use App\Models\Plan;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    // 📌 Add Library API (same as before)
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'library_name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'address' => 'required|string',
            'city' => 'required|string',
            'locality' => 'nullable|string',
            'state' => 'required|string',
            'pincode' => 'required|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only([
            'library_name',
            'start_time',
            'end_time',
            'address',
            'city',
            'locality',
            'state',
            'pincode'
        ]);
        $data['vendor_id'] = $user->id;

        if ($request->hasFile('logo')) {
            $fileName = time() . '_logo.' . $request->file('logo')->getClientOriginalExtension();
            $path = $request->file('logo')->storeAs('uploads/logo', $fileName, 'public');
            $data['logo'] = $path;
        }

        if ($request->hasFile('photo')) {
            $fileName = time() . '_photo.' . $request->file('photo')->getClientOriginalExtension();
            $path = $request->file('photo')->storeAs('uploads/photo', $fileName, 'public');
            $data['photo'] = $path;
        }

        $library = Library::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Library added successfully',
            'library' => $library,
            'library_filled' => true
        ]);
    }

    // 📌 Combined Check (Library + Halls + Plans)
    public function completionStatus(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        return response()->json([
            'success' => true,
            'library_filled' => Library::where('vendor_id', $user->id)->exists(),
            'hall_filled' => Hall::where('vendor_id', $user->id)->exists(),
            'plan_filled' => Plan::where('vendor_id', $user->id)->exists(),
        ]);
    }
}
