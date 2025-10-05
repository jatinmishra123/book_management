@extends('admin.layouts.app')

@section('title', 'Student Management - Admin Dashboard')

@section('content')
    <div class="container-fluid">
        {{-- Page Heading --}}
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h2 class="mb-0">Student Management</h2>

                <div class="d-flex gap-2 align-items-center flex-wrap">
                    {{-- Live Search Input --}}
                    <div class="form-group mb-0">
                        <input type="text" id="live-search" class="form-control" placeholder="Search students...">
                    </div>

                    <a href="{{ route('admin.students.create') }}" class="btn btn-primary">Add Student</a>

                    <div class="dropdown">
                        <button class="btn btn-outline-info dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('admin.students.export.csv') }}">Export CSV</a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.students.export.excel') }}">Export Excel</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Students Table --}}
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Profile</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Enrollment Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="student-table-body">
                        @forelse($students as $key => $student)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    @if($student->profile_image)
                                        <img src="{{ asset('storage/' . $student->profile_image) }}" class="rounded-circle"
                                            width="50" height="50" alt="Profile Image">
                                    @else
                                        <img src="{{ asset('images/default-avatar.png') }}" class="rounded-circle" width="50"
                                            height="50" alt="Default Image">
                                    @endif
                                </td>

                                <td>{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->phone }}</td>
                                <td>{{ $student->enrollment_date ? $student->enrollment_date->format('d-m-Y') : '-' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        {{-- Show Modal Button --}}
                                        <button class="btn btn-sm btn-info text-light show-student" data-bs-toggle="modal"
                                            data-bs-target="#studentModal" data-student='@json($student)'>
                                            <i class="ri-eye-line"></i>
                                        </button>

                                        {{-- Edit Button --}}
                                        <a href="{{ route('admin.students.edit', $student->id) }}"
                                            class="btn btn-sm btn-warning text-light">
                                            <i class="ri-pencil-line"></i>
                                        </a>

                                        {{-- Delete Button --}}
                                        <button class="btn btn-sm btn-danger delete-student" data-id="{{ $student->id }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No students found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{-- Pagination Links --}}
                {{ $students->links() }}
            </div>
        </div>
    </div>

    {{-- Student Modal --}}
    <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="studentModalLabel">
                        <i class="ri-user-line me-2"></i>Student Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-md-4 text-center border-end">
                            <div class="position-relative d-inline-block">
                                <img id="modalProfileImage" src="" class="rounded-circle shadow" width="140" height="140"
                                    alt="Profile" style="object-fit: cover;">
                                <span id="modalStatusBadge"
                                    class="position-absolute bottom-0 end-0 badge rounded-circle p-2 border border-3 border-white"></span>
                            </div>
                            <h4 id="modalName" class="mt-3 mb-1"></h4>
                            <div id="modalPaymentStatus" class="mb-3"></div>

                            <div class="d-grid gap-2">
                                <a href="#" id="modalEditLink" class="btn btn-outline-primary btn-sm">
                                    <i class="ri-pencil-line me-1"></i> Edit Student
                                </a>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="form-label fw-semibold text-muted mb-1">Email Address</label>
                                        <p id="modalEmail" class="mb-0 fs-6"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="form-label fw-semibold text-muted mb-1">Phone Number</label>
                                        <p id="modalPhone" class="mb-0 fs-6"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="form-label fw-semibold text-muted mb-1">Gender</label>
                                        <p id="modalGender" class="mb-0 fs-6"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="form-label fw-semibold text-muted mb-1">Hall</label>
                                        <p id="modalHall" class="mb-0 fs-6"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="form-label fw-semibold text-muted mb-1">Enrollment Date</label>
                                        <p id="modalEnrollment" class="mb-0 fs-6"></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="form-label fw-semibold text-muted mb-1">Expiry Date</label>
                                        <p id="modalExpiry" class="mb-0 fs-6"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="form-label fw-semibold text-muted mb-1">Total Amount</label>
                                        <p id="modalTotal" class="mb-0 fs-6 fw-bold text-primary">₹0.00</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <label class="form-label fw-semibold text-muted mb-1">Paid Amount</label>
                                        <p id="modalPaid" class="mb-0 fs-6 fw-bold text-success">₹0.00</p>
                                    </div>
                                </div>
                            </div>

                            <div class="info-item mb-3">
                                <label class="form-label fw-semibold text-muted mb-1">Address</label>
                                <p id="modalAddress" class="mb-0 fs-6"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .info-item {
            padding: 8px 12px;
            border-radius: 8px;
            background-color: #f8f9fa;
            transition: all 0.2s ease;
        }

        .info-item:hover {
            background-color: #e9ecef;
            transform: translateY(-2px);
        }

        #modalStatusBadge {
            width: 20px;
            height: 20px;
        }

        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: 1px solid #dee2e6;
            border-radius: 12px 12px 0 0;
        }

        .modal-footer {
            border-top: 1px solid #dee2e6;
            border-radius: 0 0 12px 12px;
        }

        .table thead th {
            font-weight: 600;
            background-color: #f0f2f5;
        }

        .table tbody tr:hover {
            background-color: #f9f9f9;
        }

        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }

        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Live search functionality
            const searchInput = document.getElementById('live-search');
            const tableBody = document.getElementById('student-table-body');

            searchInput.addEventListener('keyup', function () {
                const query = this.value.toLowerCase();
                const rows = tableBody.getElementsByTagName('tr');

                for (let i = 0; i < rows.length; i++) {
                    let found = false;
                    const cells = rows[i].getElementsByTagName('td');
                    for (let j = 0; j < cells.length; j++) {
                        const text = cells[j].textContent.toLowerCase();
                        if (text.includes(query)) {
                            found = true;
                            break;
                        }
                    }
                    rows[i].style.display = found ? '' : 'none';
                }
            });

            // Event delegation for Delete button
            tableBody.addEventListener('click', function (e) {
                const deleteBtn = e.target.closest('.delete-student');
                if (deleteBtn) {
                    const studentId = deleteBtn.dataset.id;
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
                            form.action = `/admin/students/${studentId}`;
                            form.innerHTML = '@csrf @method("DELETE")';
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }
            });

            // Event delegation for Show modal
            tableBody.addEventListener('click', function (e) {
                const showBtn = e.target.closest('.show-student');
                if (showBtn) {
                    const student = JSON.parse(showBtn.dataset.student);

                    document.getElementById('modalProfileImage').src = student.profile_image ?
                        '/storage/' + student.profile_image : '/images/default-avatar.png';
                    document.getElementById('modalName').textContent = student.name;
                    document.getElementById('modalEmail').textContent = student.email;
                    document.getElementById('modalPhone').textContent = student.phone;
                    document.getElementById('modalGender').textContent = student.gender || '-';
                    document.getElementById('modalAddress').textContent = student.address || '-';
                    document.getElementById('modalEnrollment').textContent = student.enrollment_date ?
                        new Date(student.enrollment_date).toLocaleDateString('en-GB') : '-';
                    document.getElementById('modalExpiry').textContent = student.expiry_date ?
                        new Date(student.expiry_date).toLocaleDateString('en-GB') : '-';
                    document.getElementById('modalTotal').textContent = '₹' + (student.total_amount ? parseFloat(student.total_amount).toFixed(2) : '0.00');
                    document.getElementById('modalPaid').textContent = '₹' + (student.paid_amount ? parseFloat(student.paid_amount).toFixed(2) : '0.00');
                    document.getElementById('modalHall').textContent = student.hall || '-';

                    // Payment status badge
                    const paymentStatusElem = document.getElementById('modalPaymentStatus');
                    paymentStatusElem.innerHTML = student.payment_status === 'Paid' ?
                        '<span class="badge bg-success">Paid</span>' :
                        '<span class="badge bg-warning text-dark">Pending</span>';

                    // Status badge
                    const statusBadge = document.getElementById('modalStatusBadge');
                    statusBadge.className = student.status ?
                        'position-absolute bottom-0 end-0 badge bg-success rounded-circle p-2 border border-3 border-white' :
                        'position-absolute bottom-0 end-0 badge bg-danger rounded-circle p-2 border border-3 border-white';

                    // Edit link
                    document.getElementById('modalEditLink').href = `/admin/students/${student.id}/edit`;
                }
            });

        });
    </script>

@endpush