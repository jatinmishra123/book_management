@extends('admin.layouts.app')

@section('title', 'Add Student - Admin Dashboard')

@section('content')
       <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-header  text-white">
                <h4 class="mb-0">{{ isset($student) ? 'Edit Student' : 'Add Student' }}</h4>
            </div>
            <div class="card-body">
                <form action="{{ isset($student) ? route('admin.students.update', $student->id) : route('admin.students.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if(isset($student))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <!-- Left Column - Personal Info -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $student->name ?? '') }}" placeholder="Enter full name" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $student->email ?? '') }}" placeholder="Enter email" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $student->phone ?? '') }}" placeholder="Enter phone number" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender', $student->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender', $student->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror"
                                    placeholder="Enter address" rows="3">{{ old('address', $student->address ?? '') }}</textarea>
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Right Column - Enrollment & Payment Info -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="enrollment_date" class="form-label">Enrollment Date <span class="text-danger">*</span></label>
                                <input type="date" name="enrollment_date" id="enrollment_date"
                                    class="form-control @error('enrollment_date') is-invalid @enderror"
                                    value="{{ old('enrollment_date', isset($student) ? $student->enrollment_date->format('Y-m-d') : '') }}" required>
                                @error('enrollment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="expiry_date" class="form-label">Expiry Date</label>
                                <input type="date" name="expiry_date" id="expiry_date"
                                    class="form-control @error('expiry_date') is-invalid @enderror"
                                    value="{{ old('expiry_date', isset($student) && $student->expiry_date ? $student->expiry_date->format('Y-m-d') : '') }}">
                                @error('expiry_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="hall" class="form-label">Hall</label>
                                <input type="text" name="hall" id="hall" class="form-control @error('hall') is-invalid @enderror"
                                    value="{{ old('hall', $student->hall ?? '') }}" placeholder="Enter hall">
                                @error('hall')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label for="seat" class="form-label">seat</label>
                                <input type="text" name="seat" id="seat" class="form-control @error('seat') is-invalid @enderror"
                                    value="{{ old('seat', $student->seat ?? '') }}" placeholder="Enter seat">
                                @error('seat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="total_amount" class="form-label">Total Amount ($)</label>
                                    <input type="number" step="0.01" name="total_amount" id="total_amount"
                                        class="form-control @error('total_amount') is-invalid @enderror"
                                        value="{{ old('total_amount', $student->total_amount ?? '') }}" placeholder="0.00">
                                    @error('total_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="paid_amount" class="form-label">Paid Amount ($)</label>
                                    <input type="number" step="0.01" name="paid_amount" id="paid_amount"
                                        class="form-control @error('paid_amount') is-invalid @enderror"
                                        value="{{ old('paid_amount', $student->paid_amount ?? '') }}" placeholder="0.00">
                                    @error('paid_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="payment_status" class="form-label">Payment Status</label>
                                <select name="payment_status" id="payment_status"
                                    class="form-select @error('payment_status') is-invalid @enderror">
                                    <option value="">Select Status</option>
                                    <option value="Paid" {{ old('payment_status', $student->payment_status ?? '') == 'Paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="Pending" {{ old('payment_status', $student->payment_status ?? '') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                                @error('payment_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    <!-- Profile Image Section -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="profile_image" class="form-label">Profile Image</label>
                                <input type="file" name="profile_image" id="profile_image"
                                    class="form-control @error('profile_image') is-invalid @enderror" accept="image/*">
                                @error('profile_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="image-preview-container text-center">
                                <div id="imagePreview" class="mt-2">
                                    @if(isset($student) && $student->profile_image)
                                        <img src="{{ asset('storage/' . $student->profile_image) }}" alt="Profile" class="img-thumbnail" width="150">
                                    @else
                                        <span class="text-muted">Image preview will appear here</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Students
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> {{ isset($student) ? 'Update' : 'Save' }} Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Image Preview -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileImageInput = document.getElementById('profile_image');
        const imagePreview = document.getElementById('imagePreview');

        profileImageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();

                reader.addEventListener('load', function() {
                    imagePreview.innerHTML = `<img src="${this.result}" alt="Profile Preview" class="img-thumbnail" width="150">`;
                });

                reader.readAsDataURL(file);
            } else {
                imagePreview.innerHTML = '<span class="text-muted">Image preview will appear here</span>';
            }
        });
    });
    </script>

    <style>
    .card {
        border: none;
        border-radius: 10px;
    }

    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }

    .form-control, .form-select {
        border-radius: 6px;
        padding: 10px;
    }

    .btn {
        border-radius: 6px;
        padding: 10px 20px;
    }

    .image-preview-container {
        min-height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 10px;
    }

    .img-thumbnail {
        border-radius: 8px;
        object-fit: cover;
        height: 150px;
        width: 150px;
    }
    </style>
@endsection