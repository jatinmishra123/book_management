@extends('admin.layouts.app')

@section('title', 'Dashboard - Admin Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header with date and quick actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Dashboard Overview</h2>
                <p class="text-muted mb-0">Welcome back, here's today's summary - {{ now()->format('l, F j, Y') }}</p>
            </div>

        </div>

        <!-- Summary Cards with improved design -->
        <div class="row g-4 mb-4">
            <!-- Total Subscriptions Card -->
            <div class="col-md-4">
                <div class="card card-hover shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded">
                                <i class="ri-wallet-line dashboard-icon text-primary fs-2"></i>
                            </div>
                            <span
                                class="badge {{ $subscriptionGrowthPositive ? 'bg-success' : 'bg-danger' }} bg-opacity-10 text-{{ $subscriptionGrowthPositive ? 'success' : 'danger' }}">{{ $subscriptionGrowthPositive ? '+' : '' }}{{ $subscriptionGrowthPercentage }}%</span>
                        </div>
                        <h4 class="fw-bold mb-1">{{ $totalSubscriptions }}</h4>
                        <p class="text-muted mb-0">Total Subscriptions</p>
                        <div class="mt-3">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 75%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Vendors Card -->
            <div class="col-md-4">
                <div class="card card-hover shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-info bg-opacity-10 p-3 rounded">
                                <i class="ri-group-line dashboard-icon text-info fs-2"></i>
                            </div>
                            <span
                                class="badge {{ $vendorGrowthPositive ? 'bg-success' : 'bg-danger' }} bg-opacity-10 text-{{ $vendorGrowthPositive ? 'success' : 'danger' }}">{{ $vendorGrowthPositive ? '+' : '' }}{{ $vendorGrowthPercentage }}%</span>
                        </div>
                        <h4 class="fw-bold mb-1">{{ $totalVendors }}</h4>
                        <p class="text-muted mb-0">Total Vendors</p>
                        <div class="mt-3">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 60%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Students Card -->
            <div class="col-md-4">
                <div class="card card-hover shadow-sm border-0">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="bg-success bg-opacity-10 p-3 rounded">
                                <i class="ri-graduation-cap-line dashboard-icon text-success fs-2"></i>
                            </div>
                            <span
                                class="badge {{ $studentGrowthPositive ? 'bg-success' : 'bg-danger' }} bg-opacity-10 text-{{ $studentGrowthPositive ? 'success' : 'danger' }}">{{ $studentGrowthPositive ? '+' : '' }}{{ $studentGrowthPercentage }}%</span>
                        </div>
                        <h4 class="fw-bold mb-1">{{ $totalStudents }}</h4>
                        <p class="text-muted mb-0">Total Students</p>
                        <div class="mt-3">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 85%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Subscriptions Table -->
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Recent Subscriptions</h5>
                        <a href="#" class="btn btn-sm btn-link">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Amount</th>
                                        <th>Days</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentSubscriptions as $subscription)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-primary bg-opacity-10 p-2 rounded me-2">
                                                        <i class="ri-wallet-line text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <p class="fw-bold mb-0">{{ Str::limit($subscription->title, 15) }}</p>
                                                        <small
                                                            class="text-muted">{{ \Carbon\Carbon::parse($subscription->created_at)->format('M d') }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="fw-bold">${{ $subscription->amount }}</td>
                                            <td><span class="badge bg-light text-dark">{{ $subscription->number_of_days }}
                                                    days</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-4">No subscriptions found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Vendors Table -->
            <div class="col-lg-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Recent Vendors</h5>
                        <a href="#" class="btn btn-sm btn-link">View All</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Vendor Name</th>
                                        <th>Company</th>
                                        <th>Email</th>
                                        <th>Joined</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentVendors as $vendor)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-info bg-opacity-10 p-2 rounded me-2">
                                                        <i class="ri-user-line text-info"></i>
                                                    </div>
                                                    <div>
                                                        <p class="fw-bold mb-0">{{ $vendor->vendor_name }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $vendor->company }}</td>
                                            <td>{{ $vendor->email }}</td>
                                            <td>{{ \Carbon\Carbon::parse($vendor->created_at)->format('d M Y') }}</td>
                                            <td>
                                                <span
                                                    class="badge {{ $vendor->is_active ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }}">
                                                    {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">No vendors found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- Remixicon CSS for icons -->
        <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
        <style>
            .card-hover:hover {
                transform: translateY(-5px);
                transition: all 0.3s ease;
            }

            .dashboard-icon {
                font-size: 1.8rem;
            }
        </style>
    @endpush
@endsection