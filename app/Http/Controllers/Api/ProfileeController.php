<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Library;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ProfileeController extends Controller
{
    public function completion(Request $request)
    {
        $user = $request->user(); // logged-in vendor

        // ---- Owner complete? (name + mobile + email + address present)
        $name = $user->full_name ?? $user->vendor_name ?? null;
        $ownerComplete = filled($name) && filled($user->mobile_number ?? null)
            && filled($user->email ?? null) && filled($user->address ?? null);

        // ---- Library complete? (कम से कम एक library linked to vendor)
        $libraryComplete = Library::where('vendor_id', $user->id)->exists();

        // ---- Plans complete? (vendor_id पर plans table में row और कोई field भरी हो)
        $planRow = Plan::where('vendor_id', $user->id)->first();
        $plansComplete = false;
        if ($planRow) {
            // 4-column design: ac, nonac, type3, plan – इनमें से कोई एक भरा है?
            $plansComplete = filled($planRow->ac ?? null) ||
                filled($planRow->nonac ?? null) ||
                filled($planRow->type3 ?? null) ||
                filled($planRow->plan ?? null);
        }

        // ---- Documents (Optional) – अगर table है तो check कर लो, नहीं तो optional ही मानो
        $documentsComplete = false;
        if (Schema::hasTable('vendor_documents')) {
            $documentsComplete = \DB::table('vendor_documents')
                ->where('vendor_id', $user->id)->exists();
        }

        // ---- percentage (केवल 3 required steps से)
        $requiredTotal = 3; // Owner, Library, Plans
        $completedRequired = ($ownerComplete ? 1 : 0)
            + ($libraryComplete ? 1 : 0)
            + ($plansComplete ? 1 : 0);
        $percent = intval(round(($completedRequired / $requiredTotal) * 100));

        return response()->json([
            'success' => true,
            'data' => [
                'steps' => [
                    ['key' => 'owner', 'label' => 'Owner', 'status' => $ownerComplete ? 'completed' : 'pending'],
                    ['key' => 'library', 'label' => 'Library Info', 'status' => $libraryComplete ? 'completed' : 'pending'],
                    ['key' => 'plans', 'label' => 'Plans', 'status' => $plansComplete ? 'completed' : 'pending'],
                    ['key' => 'documents', 'label' => 'Documents', 'status' => $documentsComplete ? 'completed' : 'optional'],
                ],
                'completion' => [
                    'percent' => $percent,                 // e.g. 100
                    'required_total' => $requiredTotal,    // 3
                    'completed_required' => $completedRequired,
                ],
            ],
        ]);
    }
}
