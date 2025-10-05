<?php

namespace App\Http\Controllers\Admin;

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
        // Define a default date range for the report
        $startDate = $request->input('start_date', Carbon::yesterday()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));

        // --- Fetch Dashboard Statistics ---
        // I'm assuming the 'price' column in your books table represents the purchase cost
        $totalBookPurchase = Books::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->sum('price');

        // I'm assuming you want to count publications since your Publication table doesn't have an amount
        $totalPublications = Publication::count();

        // Total amount from all subscription types
        $totalSubscriptionAmount = SubscriptionType::sum('amount');

        // Total number of subscription types
        $totalSubscriptionTypes = SubscriptionType::count();

        // --- Fetch Tabular Data ---
        // Purchased Books List
        $purchasedBooksList = Books::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Subscription Types List
        $subscriptionTypesList = SubscriptionType::orderBy('id', 'desc')
            ->paginate(10);

        // Vendor Publications List
        $publicationsList = Publication::orderBy('id', 'desc')
            ->paginate(10);


        return view('admin.reports.book_subscription_dashboard', compact(
            'startDate',
            'endDate',
            'totalBookPurchase',
            'totalPublications',
            'totalSubscriptionAmount',
            'totalSubscriptionTypes',
            'purchasedBooksList',
            'subscriptionTypesList',
            'publicationsList'
        ));
    }
}