<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Books;
use App\Models\Publication;
use App\Models\SubscriptionType;

class BookSubscriptionReportController extends Controller
{
    public function index(Request $request)
    {
        // Default date range
        $startDate = $request->input('start_date', Carbon::yesterday()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));

        // --- Dashboard Statistics ---
        $totalBookPurchase = Books::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->sum('price');

        $totalPublications = Publication::count();
        $totalSubscriptionAmount = SubscriptionType::sum('amount');
        $totalSubscriptionTypes = SubscriptionType::count();

        // --- Tabular Data ---
        $purchasedBooksList = Books::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $subscriptionTypesList = SubscriptionType::orderBy('id', 'desc')->paginate(10);

        $publicationsList = Publication::orderBy('id', 'desc')->paginate(10);

        return response()->json([
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'statistics' => [
                'total_book_purchase' => $totalBookPurchase,
                'total_publications' => $totalPublications,
                'total_subscription_amount' => $totalSubscriptionAmount,
                'total_subscription_types' => $totalSubscriptionTypes,
            ],
            'lists' => [
                'purchased_books' => $purchasedBooksList,
                'subscription_types' => $subscriptionTypesList,
                'publications' => $publicationsList,
            ]
        ], 200);
    }
}
