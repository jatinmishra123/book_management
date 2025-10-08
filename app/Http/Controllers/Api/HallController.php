<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hall;
use Illuminate\Http\Request;

class HallController extends Controller
{
    // ✅ Create Hall API
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'hall_name' => 'required|string|max:255',
            'total_seats' => 'required|integer',
            'type' => 'required|string|max:100',
            'facilities' => 'nullable|array',        // 👈 array validate
            'facilities.*' => 'string|max:100',        // 👈 har item string ho
        ]);

        $data = $request->only(['hall_name', 'total_seats', 'type', 'facilities']);
        $data['vendor_id'] = $user->id;

        $hall = Hall::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Hall created successfully',
            'data' => $hall
        ]);
    }

    // ✅ Get All Halls (sirf vendor ke halls)
    public function index()
    {
        $user = auth()->user();
        return response()->json(
            Hall::where('vendor_id', $user->id)->get()
        );
    }

    // ✅ Get Single Hall
    public function show($id)
    {
        $hall = Hall::findOrFail($id);

        if ($hall->vendor_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        return response()->json($hall);
    }

    // ✅ Update Hall
    public function update(Request $request, $id)
    {
        $hall = Hall::findOrFail($id);

        if ($hall->vendor_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $request->validate([
            'hall_name' => 'nullable|string|max:255',
            'total_seats' => 'nullable|integer',
            'type' => 'nullable|string|max:100',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string|max:100',
        ]);

        $hall->update($request->only(['hall_name', 'total_seats', 'type', 'facilities']));

        return response()->json([
            'success' => true,
            'message' => 'Hall updated successfully',
            'data' => $hall
        ]);
    }

    // ✅ Delete Hall
    public function destroy($id)
    {
        $hall = Hall::findOrFail($id);

        if ($hall->vendor_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
        }

        $hall->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hall deleted successfully'
        ]);
    }
}
