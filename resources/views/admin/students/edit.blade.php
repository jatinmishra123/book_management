@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-14">
                <div class="card shadow-lg border-0 rounded-4">
                    <div
                        class="card-header bg-white py-4 d-flex justify-content-between align-items-center border-bottom-0 rounded-top-4">
                        <h4 class="mb-0 fw-bold text-dark-emphasis">
                            <i class="ri-user-edit-line me-2 text-primary"></i>Edit Student
                        </h4>
                        <a href="{{ route('admin.students.index') }}"
                            class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="ri-arrow-left-line me-1"></i> Back to Students
                        </a>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.students.update', $student->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-semibold">Full Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name', $student->name) }}" placeholder="Enter full name"
                                            required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-semibold">Email Address <span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $student->email) }}" placeholder="Enter email address"
                                            required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label fw-semibold">Phone Number <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="phone" id="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone', $student->phone) }}" placeholder="Enter phone number"
                                            required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="gender" class="form-label fw-semibold">Gender <span
                                                class="text-danger">*</span></label>
                                        <select name="gender" id="gender"
                                            class="form-select @error('gender') is-invalid @enderror" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{ old('gender', $student->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender', $student->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="address" class="form-label fw-semibold">Address</label>
                                        <textarea name="address" id="address"
                                            class="form-control @error('address') is-invalid @enderror"
                                            placeholder="Enter address"
                                            rows="3">{{ old('address', $student->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-4 text-center">
                                        <label for="profile_image" class="form-label fw-semibold d-block mb-3">Profile
                                            Image</label>

                                        <div class="image-preview-container mb-3">
                                            <div id="imagePreview"
                                                class="mx-auto rounded-circle overflow-hidden shadow-sm border border-light"
                                                style="width: 150px; height: 150px;">
                                                @if ($student->profile_image)
                                                    <img src="{{ asset('storage/' . $student->profile_image) }}"
                                                        alt="Current Profile" class="img-fluid" id="currentImage">
                                                @else
                                                    <div
                                                        class="bg-light-subtle rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                                        <i class="ri-user-line text-muted" style="font-size: 3.5rem;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <input type="file" name="profile_image" id="profile_image"
                                                class="form-control @error('profile_image') is-invalid @enderror"
                                                accept="image/*">
                                            <div class="form-text mt-2">Allowed formats: JPG, PNG, JPEG, GIF. Max size: 2MB
                                            </div>
                                            @error('profile_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="enrollment_date" class="form-label fw-semibold">Enrollment Date
                                                <span class="text-danger">*</span></label>
                                            <input type="date" name="enrollment_date" id="enrollment_date"
                                                class="form-control @error('enrollment_date') is-invalid @enderror"
                                                value="{{ old('enrollment_date', $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : '') }}"
                                                required>
                                            @error('enrollment_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="expiry_date" class="form-label fw-semibold">Expiry Date</label>
                                            <input type="date" name="expiry_date" id="expiry_date"
                                                class="form-control @error('expiry_date') is-invalid @enderror"
                                                value="{{ old('expiry_date', $student->expiry_date ? $student->expiry_date->format('Y-m-d') : '') }}">
                                            @error('expiry_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="total_amount" class="form-label fw-semibold">Total Amount
                                                (₹)</label>
                                            <input type="number" step="0.01" name="total_amount" id="total_amount"
                                                class="form-control @error('total_amount') is-invalid @enderror"
                                                value="{{ old('total_amount', $student->total_amount) }}"
                                                placeholder="0.00">
                                            @error('total_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="paid_amount" class="form-label fw-semibold">Paid Amount (₹)</label>
                                            <input type="number" step="0.01" name="paid_amount" id="paid_amount"
                                                class="form-control @error('paid_amount') is-invalid @enderror"
                                                value="{{ old('paid_amount', $student->paid_amount) }}" placeholder="0.00">
                                            @error('paid_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="payment_status" class="form-label fw-semibold">Payment
                                                Status</label>
                                            <select name="payment_status" id="payment_status"
                                                class="form-select @error('payment_status') is-invalid @enderror">
                                                <option value="">Select Status</option>
                                                <option value="Paid" {{ old('payment_status', $student->payment_status) == 'Paid' ? 'selected' : '' }}>Paid</option>
                                                <option value="Pending" {{ old('payment_status', $student->payment_status) == 'Pending' ? 'selected' : '' }}>Pending
                                                </option>
                                            </select>
                                            @error('payment_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="hall" class="form-label fw-semibold">Hall</label>
                                            <input type="text" name="hall" id="hall"
                                                class="form-control @error('hall') is-invalid @enderror"
                                                value="{{ old('hall', $student->hall) }}" placeholder="Enter hall">
                                            @error('hall')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-3 mt-4 border-top">
                                <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">
                                    <i class="ri-close-line me-1"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-save-line me-1"></i> Update Student
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        body {
            background-color: #f8f9fa;
            /* Lighter background */
        }

        .card {
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card-header {
            border-bottom: 1px solid #e9ecef;
            background-image: linear-gradient(to right, #4a90e2, #50c9c3);
            color: white;
        }

        .card-header .h4,
        .card-header a {
            color: #4a90e2;
        }

        .form-control,
        .form-select {
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            border: 1px solid #e0e0e0;
            background-color: #fbfbfb;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #4a90e2;
            box-shadow: 0 0 0 0.15rem rgba(74, 144, 226, 0.25);
            background-color: #fff;
        }

        .btn {
            border-radius: 0.75rem;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        .btn-primary {
            background-color: #4a90e2;
            border-color: #4a90e2;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(74, 144, 226, 0.4);
        }

        .btn-outline-primary {
            color: #4a90e2;
            border-color: #4a90e2;
        }

        .btn-outline-primary:hover {
            background-color: #4a90e2;
            color: white;
        }

        .image-preview-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        #imagePreview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background-color: #f8f9fa;
        }

        #imagePreview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .form-text {
            font-size: 0.8rem;
            color: #888;
        }

        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 1rem;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-block;
            white-space: nowrap;
            text-align: center;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const profileImageInput = document.getElementById('profile_image');
            const imagePreview = document.getElementById('imagePreview');
            const statusSelect = document.getElementById('status');

            profileImageInput.addEventListener('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.addEventListener('load', function () {
                        const newImg = document.createElement('img');
                        newImg.src = this.result;
                        newImg.alt = "New Profile Image";
                        newImg.className = "img-fluid w-100 h-100";
                        newImg.style.objectFit = 'cover';
                        imagePreview.innerHTML = '';
                        imagePreview.appendChild(newImg);
                    });
                    reader.readAsDataURL(file);
                } else {
                    const originalImageUrl = "{{ $student->profile_image ? asset('storage/' . $student->profile_image) : '' }}";
                    imagePreview.innerHTML = '';
                    if (originalImageUrl) {
                        const originalImg = document.createElement('img');
                        originalImg.src = originalImageUrl;
                        originalImg.alt = "Current Profile";
                        originalImg.className = "img-fluid w-100 h-100";
                        originalImg.style.objectFit = 'cover';
                        imagePreview.appendChild(originalImg);
                    } else {
                        const placeholder = document.createElement('div');
                        placeholder.className = "bg-light-subtle rounded-circle d-flex align-items-center justify-content-center w-100 h-100";
                        placeholder.innerHTML = '<i class="ri-user-line text-muted" style="font-size: 3.5rem;"></i>';
                        imagePreview.appendChild(placeholder);
                    }
                }
            });

            // Add a "clear" button for the status dropdown
            statusSelect.addEventListener('focus', function () {
                const clearOption = statusSelect.querySelector('.clear-option');
                if (clearOption) {
                    clearOption.remove();
                }
                const defaultOption = document.createElement('option');
                defaultOption.value = "";
                defaultOption.textContent = "Select Status";
                defaultOption.className = "clear-option";
                statusSelect.prepend(defaultOption);
                defaultOption.selected = true;
            });

            statusSelect.addEventListener('change', function () {
                const clearOption = statusSelect.querySelector('.clear-option');
                if (clearOption) {
                    clearOption.remove();
                }
            });
        });
    </script>
@endpush