@extends('admin.layouts.app')

@section('title', 'Book & Subscription Report - Admin Dashboard')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .report-card {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            border-radius: 0.5rem;
            background-color: #fff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.2s ease-in-out;
            margin-bottom: 1.5rem;
        }

        .report-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
        }

        .report-card .icon-wrapper {
            font-size: 2.5rem;
            margin-right: 1rem;
            padding: 1rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .report-card .text-content {
            flex-grow: 1;
        }

        .report-card .text-content h6 {
            margin-bottom: 0.25rem;
            color: #6c757d;
        }

        .report-card .text-content h4 {
            font-weight: bold;
            color: #343a40;
        }

        .card-purchase .icon-wrapper {
            background-color: #007bff;
        }

        .card-publication .icon-wrapper {
            background-color: #28a745;
        }

        .card-subscription-amount .icon-wrapper {
            background-color: #ffc107;
        }

        .card-subscription-count .icon-wrapper {
            background-color: #dc3545;
        }

        .nav-tabs .nav-link {
            font-weight: 500;
            color: #6c757d;
        }

        .nav-tabs .nav-link.active {
            color: #007bff;
            border-bottom: 2px solid #007bff;
            background-color: transparent;
            border-color: transparent;
        }

        .nav-tabs {
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 1.5rem;
        }

        .tab-content {
            background-color: #fff;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="#"><i class="ri-home-line"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Report</li>
            </ol>
        </nav>

        {{-- Date Range Selection Card --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-3">Choose Date Range</h5>
                <form action="{{ route('admin.reports.book_subscription_dashboard') }}" method="GET"
                    class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="startDate" class="form-label">Start Date</label>
                        <div class="input-group">
                            <input type="text" class="form-control flatpickr-input" id="startDate" name="start_date"
                                value="{{ $startDate }}" placeholder="YYYY-MM-DD" required>
                            <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="endDate" class="form-label">End Date</label>
                        <div class="input-group">
                            <input type="text" class="form-control flatpickr-input" id="endDate" name="end_date"
                                value="{{ $endDate }}" placeholder="YYYY-MM-DD" required>
                            <span class="input-group-text"><i class="ri-calendar-line"></i></span>
                        </div>
                    </div>
                    <div class="col-md-auto">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Dashboard Statistics Cards --}}
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="report-card card-purchase">
                    <div class="icon-wrapper">
                        <i class="ri-book-3-line"></i>
                    </div>
                    <div class="text-content">
                        <h6>Total Purchased Books</h6>
                        <h4>${{ number_format($totalBookPurchase, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="report-card card-publication">
                    <div class="icon-wrapper">
                        <i class="ri-building-line"></i>
                    </div>
                    <div class="text-content">
                        <h6>Total Publications</h6>
                        <h4>{{ $totalPublications }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="report-card card-subscription-amount">
                    <div class="icon-wrapper">
                        <i class="ri-money-dollar-circle-line"></i>
                    </div>
                    <div class="text-content">
                        <h6>Total Subscription Amount</h6>
                        <h4>${{ number_format($totalSubscriptionAmount, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="report-card card-subscription-count">
                    <div class="icon-wrapper">
                        <i class="ri-list-check"></i>
                    </div>
                    <div class="text-content">
                        <h6>Total Subscription Types</h6>
                        <h4>{{ $totalSubscriptionTypes }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabbed Details Section --}}
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs" id="reportTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="purchased-books-tab" data-bs-toggle="tab"
                            data-bs-target="#purchased-books" type="button" role="tab" aria-controls="purchased-books"
                            aria-selected="true">Purchased Books</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="subscription-list-tab" data-bs-toggle="tab"
                            data-bs-target="#subscription-list" type="button" role="tab" aria-controls="subscription-list"
                            aria-selected="false">Subscription List</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="publications-list-tab" data-bs-toggle="tab"
                            data-bs-target="#publications-list" type="button" role="tab" aria-controls="publications-list"
                            aria-selected="false">Publications List</button>
                    </li>
                </ul>
                <div class="tab-content" id="reportTabsContent">
                    {{-- Purchased Books Tab Content --}}
                    <div class="tab-pane fade show active" id="purchased-books" role="tabpanel"
                        aria-labelledby="purchased-books-tab">
                        <h5 class="mb-3">Purchased Books Details ({{ Carbon::parse($startDate)->format('M d, Y') }} -
                            {{ Carbon::parse($endDate)->format('M d, Y') }})
                        </h5>
                        @if($purchasedBooksList->isEmpty())
                            <p>No purchased books found for this period.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Book Name</th>
                                            <th>Vendor</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Purchase Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($purchasedBooksList as $key => $book)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $book->book_name }}</td>
                                                <td>{{ $book->vendor }}</td>
                                                <td>{{ $book->quantity }}</td>
                                                <td>${{ number_format($book->price, 2) }}</td>
                                                <td>{{ $book->created_at->format('Y-m-d') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    {{-- Subscription List Tab Content --}}
                    <div class="tab-pane fade" id="subscription-list" role="tabpanel"
                        aria-labelledby="subscription-list-tab">
                        <h5 class="mb-3">Subscription Types List</h5>
                        @if($subscriptionTypesList->isEmpty())
                            <p>No subscription types found.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Title</th>
                                            <th>Amount</th>
                                            <th>Number of Days</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($subscriptionTypesList as $key => $subscriptionType)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $subscriptionType->title }}</td>
                                                <td>${{ number_format($subscriptionType->amount, 2) }}</td>
                                                <td>{{ $subscriptionType->number_of_days }}</td>
                                                <td>{{ $subscriptionType->description }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    {{-- Publications List Tab Content --}}
                    <div class="tab-pane fade" id="publications-list" role="tabpanel"
                        aria-labelledby="publications-list-tab">
                        <h5 class="mb-3">Publications List</h5>
                        @if($publicationsList->isEmpty())
                            <p>No publications found.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Publisher Name</th>
                                            <th>Address</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($publicationsList as $key => $publication)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $publication->publisher_name }}</td>
                                                <td>{{ $publication->address }}</td>
                                                <td>{{ $publication->description }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr("#startDate", { dateFormat: "Y-m-d" });
            flatpickr("#endDate", { dateFormat: "Y-m-d" });
        });
    </script>
@endpush