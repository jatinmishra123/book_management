@extends('admin.layouts.app')

@section('title', 'Role Management - Admin Dashboard')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h2 class="mb-0">Role Management</h2>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adminModal"
                    id="addAdminBtn">
                    <i class="ri-add-line me-1"></i> Add Admin
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Last Login</th>
                                        <th>Created At</th>
                                        <th>Updated At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="admin-table-body">
                                    @forelse($admins as $admin)
                                        <tr>
                                            <td>{{ $admin->id }}</td>
                                            <td>{{ $admin->name }}</td>
                                            <td>{{ $admin->email }}</td>
                                            <td>{{ $admin->phone ?? 'N/A' }}</td>
                                            <td><span class="badge bg-secondary">{{ ucfirst($admin->role) }}</span></td>
                                            <td>
                                                @if ($admin->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $admin->last_login_at ? \Carbon\Carbon::parse($admin->last_login_at)->format('d M Y, h:i A') : 'N/A' }}
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($admin->created_at)->format('d M Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($admin->updated_at)->diffForHumans() }}</td>
                                            <td>

                                                <button class="btn btn-sm btn-danger delete-admin" data-id="{{ $admin->id }}">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center">No admin users found.</td>
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

    <div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="adminModalLabel">Add Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="adminForm" action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="adminMethod" value="POST">
                        <input type="hidden" name="id" id="adminId">

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter full name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter email address" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter password">
                            <small id="passwordHelp" class="form-text text-muted" style="display: none;">
                                Leave blank to keep the current password on edit.
                            </small>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter phone number">
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="" disabled selected>Select role</option>
                                <option value="admin">Admin</option>
                                <option value="editor">Editor</option>
                                <option value="viewer">Viewer</option>
                            </select>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                                checked>
                            <label class="form-check-label" for="is_active">Is Active</label>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="adminSubmitBtn">Save changes</button>
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
            const adminModal = new bootstrap.Modal(document.getElementById('adminModal'));
            const addAdminBtn = document.getElementById('addAdminBtn');
            const adminForm = document.getElementById('adminForm');
            const adminIdInput = document.getElementById('adminId');
            const adminMethodInput = document.getElementById('adminMethod');
            const adminModalLabel = document.getElementById('adminModalLabel');
            const adminSubmitBtn = document.getElementById('adminSubmitBtn');
            const passwordInput = document.getElementById('password');
            const passwordHelp = document.getElementById('passwordHelp');

            // Reset form for "Add Admin"
            addAdminBtn.addEventListener('click', function () {
                adminForm.reset();
                adminIdInput.value = '';
                adminMethodInput.value = 'POST';
                adminForm.action = "{{ route('admin.roles.store') }}";
                adminModalLabel.textContent = 'Add Admin';
                adminSubmitBtn.textContent = 'Add Admin';
                passwordInput.required = true;
                passwordHelp.style.display = 'none';
            });

            // Event delegation for Edit and Delete buttons
            document.getElementById('admin-table-body').addEventListener('click', function (e) {
                // Handle Edit
                if (e.target.closest('.edit-admin')) {
                    const button = e.target.closest('.edit-admin');
                    const adminId = button.dataset.id;

                    fetch(`/admin/roles/${adminId}/edit`)
                        .then(response => response.json())
                        .then(admin => {
                            adminForm.reset(); // Reset form to clear previous data
                            adminIdInput.value = admin.id;
                            document.getElementById('name').value = admin.name;
                            document.getElementById('email').value = admin.email;
                            document.getElementById('phone').value = admin.phone;
                            document.getElementById('role').value = admin.role;
                            document.getElementById('is_active').checked = admin.is_active;

                            adminMethodInput.value = 'PUT';
                            adminForm.action = `/admin/roles/${admin.id}`;
                            adminModalLabel.textContent = 'Edit Admin';
                            adminSubmitBtn.textContent = 'Update Admin';
                            passwordInput.required = false;
                            passwordHelp.style.display = 'block';

                            adminModal.show();
                        })
                        .catch(error => {
                            console.error('Error fetching admin data:', error);
                            Swal.fire('Error', 'Failed to load admin data. Please try again.', 'error');
                        });
                }

                // Handle Delete
                if (e.target.closest('.delete-admin')) {
                    const button = e.target.closest('.delete-admin');
                    const adminId = button.dataset.id;

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
                            // Create a form dynamically
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = `/admin/roles/${adminId}`;

                            // CSRF token
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = '{{ csrf_token() }}';
                            form.appendChild(csrfInput);

                            // DELETE method
                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';
                            form.appendChild(methodInput);

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }

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