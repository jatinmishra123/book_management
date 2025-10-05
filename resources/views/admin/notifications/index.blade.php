@extends('admin.layouts.app')

@section('title', 'Notification Management - Admin Dashboard')

@section('content')
    <div class="container-fluid">
        {{-- Page Heading --}}
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h2 class="mb-0">Notifications Management</h2>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    {{-- Live Search --}}
                    <div class="form-group mb-0">
                        <input type="text" id="student-live-search" class="form-control"
                            placeholder="Search by name, email, or phone...">
                    </div>
                    {{-- Send SMS Button --}}
                    <button type="button" class="btn btn-warning" id="openSmsModalBtn" disabled>
                        <i class="ri-mail-send-line me-1"></i> Send SMS
                    </button>
                </div>
            </div>
        </div>
        <hr>
        {{-- Students Table --}}
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAllStudents">
                            </th>
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Enrollment Date</th>
                            <th>Payment Status</th>
                        </tr>
                    </thead>
                    <tbody id="student-table-body">
                        @include('admin.notifications.partials.table_rows')
                    </tbody>
                </table>
                <div id="pagination-links">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- SMS Modal --}}
    <div class="modal fade" id="smsModal" tabindex="-1" aria-labelledby="smsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smsModalLabel">Send SMS to Students</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="smsForm">
                        @csrf
                        <input type="hidden" name="student_ids" id="selectedStudentIdsInput">
                        <div class="mb-3">
                            <label for="smsMessage" class="form-label">Message</label>
                            <textarea class="form-control" id="smsMessage" name="message" rows="4" required></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Send SMS</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('student-live-search');
            const tableBody = document.getElementById('student-table-body');
            const selectAllCheckbox = document.getElementById('selectAllStudents');
            const paginationContainer = document.getElementById('pagination-links');
            const openSmsModalBtn = document.getElementById('openSmsModalBtn');
            const smsModal = new bootstrap.Modal(document.getElementById('smsModal'));
            const smsForm = document.getElementById('smsForm');
            const selectedStudentIdsInput = document.getElementById('selectedStudentIdsInput');
            let selectedIds = new Set();
            let debounceTimer;

            // Live Search
            searchInput.addEventListener('keyup', function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const query = this.value;
                    fetchStudents(1, query);
                }, 300);
            });

            // Pagination
            paginationContainer.addEventListener('click', function (e) {
                if (e.target.tagName === 'A') {
                    e.preventDefault();
                    const url = new URL(e.target.href);
                    const page = url.searchParams.get("page");
                    const query = searchInput.value;
                    fetchStudents(page, query);
                }
            });

            // Fetch data from server
            function fetchStudents(page, query) {
                axios.get('{{ route("admin.notifications.index") }}', {
                    params: {
                        page: page,
                        search: query
                    }
                })
                    .then(response => {
                        tableBody.innerHTML = response.data.table_rows;
                        paginationContainer.innerHTML = response.data.pagination_links;
                        updateCheckboxes();
                        updateButtonState();
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                    });
            }

            // Select All logic
            selectAllCheckbox.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('input[name="student_id[]"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                    const id = checkbox.value;
                    if (this.checked) {
                        selectedIds.add(id);
                    } else {
                        selectedIds.delete(id);
                    }
                });
                updateButtonState();
            });

            // Handle individual checkbox clicks
            function handleIndividualCheckboxChange(event) {
                const id = event.target.value;
                if (event.target.checked) {
                    selectedIds.add(id);
                } else {
                    selectedIds.delete(id);
                }
                updateButtonState();
                updateSelectAllState();
            }

            // Update checkbox states after a new table is loaded
            function updateCheckboxes() {
                const checkboxes = document.querySelectorAll('input[name="student_id[]"]');
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', handleIndividualCheckboxChange);
                    if (selectedIds.has(checkbox.value)) {
                        checkbox.checked = true;
                    }
                });
                updateSelectAllState();
            }

            // Update select all checkbox state
            function updateSelectAllState() {
                const checkboxes = document.querySelectorAll('input[name="student_id[]"]');
                const totalCheckboxes = checkboxes.length;
                const checkedCheckboxes = document.querySelectorAll('input[name="student_id[]"]:checked').length;
                selectAllCheckbox.checked = totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes;
            }

            // Enable/disable SMS button
            function updateButtonState() {
                openSmsModalBtn.disabled = selectedIds.size === 0;
            }

            // Handle 'Send SMS' modal
            openSmsModalBtn.addEventListener('click', function () {
                selectedStudentIdsInput.value = JSON.stringify(Array.from(selectedIds));
                smsModal.show();
            });

            // Handle SMS form submission
            smsForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(this);
                axios.post('{{ route('admin.notifications.index') }}', formData)
                    .then(response => {
                        Swal.fire('Success', response.data.success, 'success');
                        smsModal.hide();
                        smsForm.reset();
                        selectedIds.clear();
                        updateButtonState();
                        updateCheckboxes();
                    })
                    .catch(error => {
                        const errorMessage = error.response.data.message || 'Failed to send SMS. Please try again.';
                        Swal.fire('Error', errorMessage, 'error');
                        console.error('SMS send error:', error);
                    });
            });

            // Initial setup
            updateCheckboxes();
            updateButtonState();
        });
    </script>
@endpush