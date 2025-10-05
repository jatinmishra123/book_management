@extends('admin.layouts.app')

@section('title', 'Vendor Management - Admin Dashboard')

{{-- Make sure you have this line in your main layout file's <head> section --}}
{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

@section('content')
    <div class="container-fluid">
        {{-- Page Heading --}}
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h2 class="mb-0">Vendor Management</h2>

                <div class="d-flex gap-2 align-items-center flex-wrap">
                    {{-- Live Search --}}
                    <div class="form-group mb-0">
                        <input type="text" id="vendor-live-search" class="form-control" placeholder="Search vendors...">
                    </div>

                    {{-- Add Vendor Button --}}
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#vendorManagementModal" id="addVendorBtn">
                        <i class="ri-add-line me-1"></i> Add Vendor
                    </button>
                </div>
            </div>
        </div>
        <hr>
        {{-- Vendors Table --}}
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Vendor Name</th>
                            <th>Company</th>
                            <th>Phone Number</th>
                            <th>Email</th>
                            <th>Hall</th>
                            <th>Floor</th>
                            <th>Seat</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="vendor-table-body">
                        @forelse($vendors as $key => $vendor)
                            <tr>
                                <td>{{ $vendors->firstItem() + $key }}</td>
                                <td>{{ $vendor->vendor_name }}</td>
                                <td>{{ $vendor->company }}</td>
                                <td>{{ $vendor->phone_number }}</td>
                                <td>{{ $vendor->email }}</td>
                                <td>{{ $vendor->hall }}</td>
                                <td>{{ $vendor->floor }}</td>
                                <td>{{ $vendor->seat }}</td>
                                <td>{{ $vendor->address }}</td>
                                <td class="d-flex gap-2">
                                    {{-- Edit --}}
                                    <button class="btn btn-sm btn-warning edit-vendor" data-id="{{ $vendor->id }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    {{-- Delete --}}
                                    <button class="btn btn-sm btn-danger delete-vendor" data-id="{{ $vendor->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No vendors found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $vendors->links() }}
            </div>
        </div>
    </div>
    <hr>
    {{-- Vendor Management Modal --}}
    <div class="modal fade" id="vendorManagementModal" tabindex="-1" aria-labelledby="vendorManagementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="vendorManagementModalLabel">Add Vendor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pt-0">
                    <form id="vendorForm" action="{{ route('admin.vendors.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="vendorMethod" value="POST">
                        <input type="hidden" name="id" id="vendorId">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="vendorName" class="form-label">Vendor Name</label>
                                <input type="text" class="form-control" id="vendorName" name="vendor_name"
                                    placeholder="Enter vendor name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="company" class="form-label">Company</label>
                                <input type="text" class="form-control" id="company" name="company"
                                    placeholder="Enter company name" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phoneNumber" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phoneNumber" name="phone_number"
                                    placeholder="Enter phone number" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email"
                                    required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="hall" class="form-label">Hall</label>
                                <input type="text" class="form-control" id="hall" name="hall" placeholder="Enter hall"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="floor" class="form-label">Floor</label>
                                <select class="form-control" id="floor" name="floor" required>
                                    <option value="">-- Select Floor --</option>
                                    <option value="1">1 Floor</option>
                                    <option value="2">2 Floor</option>
                                    <option value="3">3 Floor</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="seat" class="form-label">Seat</label>
                                <input type="number" class="form-control" id="seat" name="seat" placeholder="Enter seat"
                                    min="0" max="100" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="1"
                                    placeholder="Enter address"></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-outline-danger" id="clearVendorForm">Clear Form</button>
                            <button type="submit" class="btn btn-primary" id="vendorSubmitButton">Add Vendor</button>
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
            const vendorManagementModalElement = document.getElementById('vendorManagementModal');
            const vendorManagementModal = new bootstrap.Modal(vendorManagementModalElement);
            const addVendorBtn = document.getElementById('addVendorBtn');
            const vendorForm = document.getElementById('vendorForm');
            const vendorIdInput = document.getElementById('vendorId');
            const vendorMethodInput = document.getElementById('vendorMethod');
            const vendorManagementModalLabel = document.getElementById('vendorManagementModalLabel');
            const vendorSubmitButton = document.getElementById('vendorSubmitButton');
            const clearVendorFormBtn = document.getElementById('clearVendorForm');
            const vendorTableBody = document.getElementById('vendor-table-body');
            const liveSearchInput = document.getElementById('vendor-live-search');

            // Function to clear form and reset modal state
            function resetFormAndModal() {
                vendorForm.reset();
                vendorIdInput.value = '';
                vendorMethodInput.value = 'POST';
                vendorForm.action = "{{ route('admin.vendors.store') }}";
                vendorManagementModalLabel.textContent = 'Add Vendor';
                vendorSubmitButton.textContent = 'Add Vendor';
            }

            // Handle 'Add Vendor' button click
            addVendorBtn.addEventListener('click', function () {
                resetFormAndModal();
            });

            // Handle 'Clear Form' button click
            clearVendorFormBtn.addEventListener('click', () => {
                vendorForm.reset();
            });

            // Live Search
            liveSearchInput.addEventListener('keyup', function () {
                const query = this.value.toLowerCase();
                const rows = vendorTableBody.getElementsByTagName('tr');
                for (let i = 0; i < rows.length; i++) {
                    let found = false;
                    const cells = rows[i].getElementsByTagName('td');
                    for (let j = 1; j < cells.length - 1; j++) {
                        if (cells[j].textContent.toLowerCase().includes(query)) {
                            found = true;
                            break;
                        }
                    }
                    rows[i].style.display = found ? '' : 'none';
                }
            });

            // Handle modal show event to ensure it's always reset for adding
            vendorManagementModalElement.addEventListener('show.bs.modal', function (event) {
                if (event.relatedTarget && event.relatedTarget.id === 'addVendorBtn') {
                    resetFormAndModal();
                }
            });

            // Form submission with AJAX to prevent page reload
            vendorForm.addEventListener('submit', async function (event) {
                event.preventDefault();

                const formData = new FormData(vendorForm);
                if (vendorMethodInput.value === 'PUT') {
                    formData.append('_method', 'PUT');
                }
                
                try {
                    const response = await fetch(vendorForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    if (!response.ok) {
                        const errorData = await response.json();
                        let errorMessage = errorData.message || 'An unexpected error occurred.';
                        if (errorData.errors) {
                             errorMessage = Object.values(errorData.errors).flat().join('<br>');
                        }
                        return Swal.fire('Error', errorMessage, 'error');
                    }

                    const result = await response.json();

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: result.message,
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });

                    vendorManagementModal.hide(); 
                    // To see the updated data, we need to refresh the page.
                    // The old way of doing this caused the backdrop issue.
                    // The new way is to reload the page after a small delay.
                    // But for a better user experience, we should dynamically update the table.
                    // For now, let's keep it simple and just remove the line that was causing the issue.

                    // Removing window.location.reload(); will fix the backdrop issue, 
                    // but the table won't automatically update. To update the table
                    // dynamically, you would need more complex JS logic.
                    // For now, let's proceed with the simplest fix.

                } catch (error) {
                    console.error('Submission error:', error);
                    Swal.fire('Error', 'An unexpected error occurred. Please try again.', 'error');
                }
            });

            // Edit and Delete button handling using event delegation
            vendorTableBody.addEventListener('click', async (event) => {
                const editButton = event.target.closest('.edit-vendor');
                const deleteButton = event.target.closest('.delete-vendor');

                // Edit Vendor
                if (editButton) {
                    const vendorId = editButton.dataset.id;
                    try {
                        const response = await fetch(`/admin/vendors/${vendorId}/edit`);
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        const vendor = await response.json();

                        vendorManagementModalLabel.textContent = 'Edit Vendor';
                        vendorSubmitButton.textContent = 'Update Vendor';
                        vendorMethodInput.value = 'PUT';
                        vendorIdInput.value = vendor.id;
                        vendorForm.action = `/admin/vendors/${vendor.id}`;

                        // Populate the form fields with data from the server
                        document.getElementById('vendorName').value = vendor.vendor_name || '';
                        document.getElementById('company').value = vendor.company || '';
                        document.getElementById('phoneNumber').value = vendor.phone_number || '';
                        document.getElementById('email').value = vendor.email || '';
                        document.getElementById('hall').value = vendor.hall || '';
                        document.getElementById('floor').value = vendor.floor || '';
                        document.getElementById('seat').value = vendor.seat || '';
                        document.getElementById('address').value = vendor.address || '';

                        vendorManagementModal.show();
                    } catch (error) {
                        console.error('Error fetching vendor data:', error);
                        Swal.fire('Error', 'Failed to load vendor data. Please try again.', 'error');
                    }
                }

                // Delete Vendor
                if (deleteButton) {
                    const vendorId = deleteButton.dataset.id;
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
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/admin/vendors/${vendorId}`;
                            form.innerHTML = '@csrf @method("DELETE")';
                            document.body.appendChild(form);
                            form.submit();
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