<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hall;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // 📌 Home / Dashboard API
    public function home(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        // ✅ Total Seats (sum of halls seats for this vendor)
        $totalSeats = Hall::where('vendor_id', $user->id)->sum('total_seats');

        // ✅ Active Members (students whose subscription not expired)
        $activeMembers = Student::where('vendor_id', $user->id)
            ->whereDate('expiry_date', '>=', Carbon::today())
            ->count();

        // ✅ Expiring in 3 Days
        $expiringSoon = Student::where('vendor_id', $user->id)
            ->whereBetween('expiry_date', [Carbon::today(), Carbon::today()->addDays(3)])
            ->get();

        // ✅ Overdue Students (already expired)
        $overdue = Student::where('vendor_id', $user->id)
            ->whereDate('expiry_date', '<', Carbon::today())
            ->get();

        // ✅ Vacant Seats = Total - Active
        $vacantSeats = $totalSeats - $activeMembers;

        // ✅ Quick Overview stats
        $quickOverview = [
            'occupied' => $activeMembers,
            'vacant' => $vacantSeats,
            'expiring' => $expiringSoon->count(),
            'total_seats' => $totalSeats,
        ];

        return response()->json([
            'success' => true,
            'quick_overview' => $quickOverview,
            'upcoming_expirations' => $expiringSoon,
            'overdue' => $overdue,
            'reports' => [
                'active_members' => $activeMembers,
                'total_seats' => $totalSeats,
                'vacant_seats' => $vacantSeats,
                'available_counter' => 5, // agar tum counter ka table banaoge to yaha dynamic karna
                'expiring_in_3_days' => $expiringSoon->count(),
                'overdue' => $overdue->count(),
                'booked_lockers' => 45, // lockers ka table hoga to dynamic kar dena
                'available_lockers' => 5,
            ]
        ]);
    }
}
