<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionType;
use App\Models\Vendor;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Counts
        $totalStudents = DB::table('students')->count();
        $totalSubscriptions = DB::table('subscription_types')->count();
        $totalVendors = DB::table('vendors')->count();

        // Recent Subscriptions (last 5 records) using Eloquent
        $recentSubscriptions = SubscriptionType::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Recent Vendors (last 5 records) using Eloquent
        $recentVendors = Vendor::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Data for Vendor Growth Chart (last 12 months)
        $vendorData = Vendor::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->all();

        $labels = [];
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create(null, $i, 1)->format('M');
            $labels[] = $monthName;
            $data[] = $vendorData[$i] ?? 0;
        }

        // Data for Revenue Chart (example data based on subscriptions)
        $revenueData = SubscriptionType::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(amount) as total_revenue')
        )
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_revenue', 'month')
            ->all();

        $revenue = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenue[] = $revenueData[$i] ?? 0;
        }

        // Data for Subscriptions Chart (assuming types 'Basic', 'Premium', 'Enterprise')
        $subscriptionCounts = SubscriptionType::select('title', DB::raw('COUNT(*) as count'))
            ->groupBy('title')
            ->pluck('count', 'title')
            ->all();

        $subscriptionLabels = array_keys($subscriptionCounts);
        $subscriptionData = array_values($subscriptionCounts);

        // Calculate dynamic growth percentages
        $lastMonth = Carbon::now()->subMonth()->month;
        $currentMonth = Carbon::now()->month;

        $subscriptionsLastMonth = DB::table('subscription_types')->whereMonth('created_at', $lastMonth)->count();
        $subscriptionsThisMonth = DB::table('subscription_types')->whereMonth('created_at', $currentMonth)->count();
        $subscriptionGrowthPercentage = $subscriptionsLastMonth > 0 ? round((($subscriptionsThisMonth - $subscriptionsLastMonth) / $subscriptionsLastMonth) * 100) : 0;
        $subscriptionGrowthPositive = $subscriptionGrowthPercentage >= 0;

        $vendorsLastMonth = DB::table('vendors')->whereMonth('created_at', $lastMonth)->count();
        $vendorsThisMonth = DB::table('vendors')->whereMonth('created_at', $currentMonth)->count();
        $vendorGrowthPercentage = $vendorsLastMonth > 0 ? round((($vendorsThisMonth - $vendorsLastMonth) / $vendorsLastMonth) * 100) : 0;
        $vendorGrowthPositive = $vendorGrowthPercentage >= 0;

        $studentsLastMonth = DB::table('students')->whereMonth('created_at', $lastMonth)->count();
        $studentsThisMonth = DB::table('students')->whereMonth('created_at', $currentMonth)->count();
        $studentGrowthPercentage = $studentsLastMonth > 0 ? round((($studentsThisMonth - $studentsLastMonth) / $studentsLastMonth) * 100) : 0;
        $studentGrowthPositive = $studentGrowthPercentage >= 0;

        return view('admin.dashboard', compact(
            'totalStudents',
            'totalSubscriptions',
            'totalVendors',
            'recentSubscriptions',
            'recentVendors',
            'labels',
            'data',
            'revenue',
            'subscriptionLabels',
            'subscriptionData',
            'subscriptionGrowthPercentage',
            'subscriptionGrowthPositive',
            'vendorGrowthPercentage',
            'vendorGrowthPositive',
            'studentGrowthPercentage',
            'studentGrowthPositive'
        ));
    }
}
