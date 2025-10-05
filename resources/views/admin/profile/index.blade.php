@extends('admin.layouts.app')

@section('title', 'Admin Profile')

@section('content')
    <div class="container-fluid py-4">
        <h2 class="mb-4">Admin Profile</h2>

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary bg-opacity-10 text-primary rounded-top-4 p-4">
                        <div class="d-flex align-items-center">
                            <div class="profile-avatar-lg me-4">
                                <i class="ri-user-line profile-icon-lg"></i>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-1">{{ $admin->name }}</h3>
                                <p class="text-muted mb-0">
                                    <span class="badge bg-secondary me-2">{{ ucfirst($admin->role) }}</span>
                                    @if ($admin->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-5">
                        <h5 class="fw-bold text-muted mb-4">Contact Information</h5>
                        <ul class="list-group list-group-flush mb-5">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="text-muted d-flex align-items-center">
                                    <i class="ri-mail-line me-2 text-primary"></i> Email Address
                                </div>
                                <span class="fw-bold">{{ $admin->email }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="text-muted d-flex align-items-center">
                                    <i class="ri-phone-line me-2 text-primary"></i> Phone Number
                                </div>
                                <span class="fw-bold">{{ $admin->phone ?? 'N/A' }}</span>
                            </li>
                        </ul>

                        <h5 class="fw-bold text-muted mb-4">Account Details</h5>
                        <ul class="list-group list-group-flush mb-5">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="text-muted d-flex align-items-center">
                                    <i class="ri-time-line me-2 text-primary"></i> Last Login
                                </div>
                                <span
                                    class="fw-bold">{{ \Carbon\Carbon::parse($admin->last_login_at)->format('d M Y, h:i A') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="text-muted d-flex align-items-center">
                                    <i class="ri-calendar-check-line me-2 text-primary"></i> Member Since
                                </div>
                                <span
                                    class="fw-bold">{{ \Carbon\Carbon::parse($admin->created_at)->format('d M Y') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div class="text-muted d-flex align-items-center">
                                    <i class="ri-history-line me-2 text-primary"></i> Last Updated
                                </div>
                                <span
                                    class="fw-bold">{{ \Carbon\Carbon::parse($admin->updated_at)->diffForHumans() }}</span>
                            </li>
                        </ul>

                        <div class="d-flex justify-content-end gap-2">
                            <!-- Edit Button now opens a modal -->
                            <button class="btn btn-primary px-4 py-2" data-bs-toggle="modal"
                                data-bs-target="#editProfileModal">
                                <i class="ri-pencil-line me-1"></i> Edit Profile
                            </button>
                            <button class="btn btn-outline-danger px-4 py-2">
                                <i class="ri-delete-bin-line me-1"></i> Delete Account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="editProfileModalLabel">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm" method="POST" action="{{ route('admin.profile.update') }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $admin->name }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $admin->email }}"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" value="{{ $admin->phone }}">
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password"
                                placeholder="Leave blank to keep current password">
                        </div>
                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="new_password_confirmation"
                                name="new_password_confirmation">
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />
        <style>
            .profile-avatar-lg {
                width: 100px;
                height: 100px;
                background-color: var(--bs-primary);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .profile-icon-lg {
                font-size: 3rem;
                color: white;
            }

            .card-hover:hover {
                transform: translateY(-5px);
                transition: all 0.3s ease;
            }

            .list-group-item {
                border-color: #e9ecef;
            }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const editProfileForm = document.getElementById('editProfileForm');

                editProfileForm.addEventListener('submit', async function (event) {
                    event.preventDefault();

                    const formData = new FormData(editProfileForm);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));

                    try {
                        const response = await fetch(editProfileForm.action, {
                            method: 'POST', // Method will be 'PUT' due to @method('PUT')
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        });

                        const result = await response.json();

                        if (response.ok) {
                            modal.hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: result.message,
                                timer: 3000,
                                showConfirmButton: false
                            }).then(() => {
                                // Reload the page to show updated data
                                window.location.reload();
                            });
                        } else {
                            // Handle validation errors or other server-side errors
                            let errorMessage = 'An unexpected error occurred.';
                            if (result.errors) {
                                errorMessage = Object.values(result.errors).flat().join('<br>');
                            } else if (result.message) {
                                errorMessage = result.message;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                html: errorMessage,
                            });
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to update profile. Please try again.',
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection