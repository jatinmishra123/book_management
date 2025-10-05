@extends('admin.layouts.app')

@section('title', 'Subscription Type Management - Admin Dashboard')

@section('content')
    <div class="container-fluid">
        {{-- Page Heading --}}
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h2 class="mb-0">Subscription Type Management</h2>

                <div class="d-flex gap-2 align-items-center flex-wrap">
                    {{-- Live Search --}}
                    <div class="form-group mb-0">
                        <input type="text" id="subscription-type-live-search" class="form-control"
                            placeholder="Search subscription types...">
                    </div>

                    {{-- Export Dropdown Button --}}
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Export <i class="ri-file-download-line ms-1"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.subscription_types.export.csv') }}">Export as
                                    CSV</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.subscription_types.export.excel') }}">Export
                                    as Excel</a></li>
                        </ul>
                    </div>

                    {{-- Add Subscription Type Button --}}
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#subscriptionTypeManagementModal" id="addSubscriptionTypeBtn">
                        <i class="ri-add-line me-1"></i> Add Subscription Type
                    </button>
                </div>
            </div>
        </div>

        {{-- Subscription Types Table --}}
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Title</th>
                            <th>Amount</th>
                            <th>Number of Days</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="subscription-type-table-body">
                        @forelse($subscriptionTypes as $key => $subscriptionType)
                            <tr>
                                <td>{{ $subscriptionTypes->firstItem() + $key }}</td>
                                <td>{{ $subscriptionType->title }}</td>
                                <td>${{ number_format($subscriptionType->amount, 2) }}</td>
                                <td>{{ $subscriptionType->number_of_days }}</td>
                                <td>{{ $subscriptionType->description }}</td>
                                <td class="d-flex gap-2">
                                    {{-- Edit --}}
                                    <button class="btn btn-sm btn-warning edit-subscription-type"
                                        data-id="{{ $subscriptionType->id }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    {{-- Delete --}}
                                    <button class="btn btn-sm btn-danger delete-subscription-type"
                                        data-id="{{ $subscriptionType->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No subscription types found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $subscriptionTypes->links() }}
            </div>
        </div>
    </div>

    {{-- Subscription Type Management Modal --}}
    <div class="modal fade" id="subscriptionTypeManagementModal" tabindex="-1"
        aria-labelledby="subscriptionTypeManagementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="subscriptionTypeManagementModalLabel">Add Subscription Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pt-0">
                    <form id="subscriptionTypeForm" action="{{ route('admin.subscription_types.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="subscriptionTypeMethod" value="POST">
                        <input type="hidden" name="id" id="subscriptionTypeId">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter title"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="amount" name="amount"
                                        placeholder="0.00" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="numberOfDays" class="form-label">Number Of Days</label>
                                <input type="number" class="form-control" id="numberOfDays" name="number_of_days" min="1"
                                    placeholder="Enter number of days" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                    placeholder="Enter description"></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-outline-danger" id="clearSubscriptionTypeForm">Clear
                                Form</button>
                            <button type="submit" class="btn btn-primary" id="subscriptionTypeSubmitButton">Add Subscription
                                Type</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalElement = document.getElementById('subscriptionTypeManagementModal');
            const addBtn = document.getElementById('addSubscriptionTypeBtn');
            const form = document.getElementById('subscriptionTypeForm');
            const idInput = document.getElementById('subscriptionTypeId');
            const methodInput = document.getElementById('subscriptionTypeMethod');
            const modalLabel = document.getElementById('subscriptionTypeManagementModalLabel');
            const submitBtn = document.getElementById('subscriptionTypeSubmitButton');
            const clearFormBtn = document.getElementById('clearSubscriptionTypeForm');
            const tableBody = document.getElementById('subscription-type-table-body');
            const liveSearchInput = document.getElementById('subscription-type-live-search');

            // Function to clear form and reset modal state
            function resetFormAndModal() {
                form.reset();
                idInput.value = '';
                methodInput.value = 'POST';
                form.action = "{{ route('admin.subscription_types.store') }}";
                modalLabel.textContent = 'Add Subscription Type';
                submitBtn.textContent = 'Add Subscription Type';
            }

            // Handle 'Add' button click
            addBtn.addEventListener('click', function () {
                resetFormAndModal();
            });

            // Handle 'Clear Form' button click
            clearFormBtn.addEventListener('click', () => {
                resetFormAndModal();
            });

            // Live Search
            liveSearchInput.addEventListener('keyup', function () {
                const query = this.value.toLowerCase();
                const rows = tableBody.getElementsByTagName('tr');
                for (let i = 0; i < rows.length; i++) {
                    let found = false;
                    const cells = rows[i].getElementsByTagName('td');
                    for (let j = 0; j < cells.length - 1; j++) { // exclude actions column
                        if (cells[j].textContent.toLowerCase().includes(query)) {
                            found = true;
                            break;
                        }
                    }
                    rows[i].style.display = found ? '' : 'none';
                }
            });

            // Handle edit and delete button clicks using event delegation
            tableBody.addEventListener('click', async (event) => {
                const editButton = event.target.closest('.edit-subscription-type');
                const deleteButton = event.target.closest('.delete-subscription-type');

                // Edit Subscription Type
                if (editButton) {
                    const id = editButton.dataset.id;
                    try {
                        const response = await fetch(`/admin/subscription_types/${id}/edit-json`);
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        const subscriptionType = await response.json();

                        modalLabel.textContent = 'Edit Subscription Type';
                        submitBtn.textContent = 'Update Subscription Type';
                        methodInput.value = 'PUT';
                        idInput.value = subscriptionType.id;
                        form.action = `/admin/subscription_types/${subscriptionType.id}`;

                        document.getElementById('title').value = subscriptionType.title;
                        document.getElementById('amount').value = subscriptionType.amount;
                        document.getElementById('numberOfDays').value = subscriptionType.number_of_days;
                        document.getElementById('description').value = subscriptionType.description || '';

                        new bootstrap.Modal(modalElement).show();

                    } catch (error) {
                        console.error('Error fetching data:', error);
                        Swal.fire('Error', 'Failed to load data. Please try again.', 'error');
                    }
                }

                // Delete Subscription Type
                if (deleteButton) {
                    const id = deleteButton.dataset.id;
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const tempForm = document.createElement('form');
                            tempForm.method = 'POST';
                            tempForm.action = `/admin/subscription_types/${id}`;
                            tempForm.innerHTML = `@csrf @method("DELETE")`;
                            document.body.appendChild(tempForm);
                            tempForm.submit();
                        }
                    });
                }
            });

            // SweetAlert for session success messages
            @if(session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif
            });
    </script>
@endpush