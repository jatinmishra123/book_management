@extends('admin.layouts.app')

@section('title', 'Books Management - Admin Dashboard')

@section('content')
    <div class="container-fluid">
        {{-- Page Heading --}}
        <div class="row mb-3">
            <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h2 class="mb-0">Books Management</h2>

                <div class="d-flex gap-2 align-items-center flex-wrap">
                    {{-- Live Search --}}
                    <div class="form-group mb-0">
                        <input type="text" id="live-search" class="form-control" placeholder="Search books...">
                    </div>

                    {{-- Add Book Button --}}
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#bookManagementModal" id="addBookBtn">
                        <i class="ri-add-line me-1"></i> Add Book
                    </button>
                </div>
            </div>
        </div>

        {{-- Books Table --}}
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Book Name</th>
                            <th>Vendor</th>
                            <th>Quantity</th>
                            <th>Per Book Price</th>
                            <th>Purchase Date</th>
                            <th>Invoice</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="book-table-body">
                        @forelse($books as $key => $book)
                            <tr>
                                <td>{{ $books->firstItem() + $key }}</td>
                                <td>{{ $book->book_name }}</td>
                                <td>{{ $book->vendor }}</td>
                                <td>{{ $book->quantity }}</td>
                                <td>${{ number_format($book->price, 2) }}</td>
                                <td>{{ $book->purchase_date?->format('d-m-Y') ?? '-' }}</td>
                                <td>{{ $book->invoice ?? '-' }}</td>
                                <td class="d-flex gap-2">
                                    {{-- Edit --}}
                                    <button class="btn btn-sm btn-warning edit-book" data-id="{{ $book->id }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    {{-- Delete --}}
                                    <button class="btn btn-sm btn-danger delete-book" data-id="{{ $book->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No books found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $books->links() }}
            </div>
        </div>
    </div>

    {{-- Book Management Modal --}}
    <div class="modal fade" id="bookManagementModal" tabindex="-1" aria-labelledby="bookManagementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="bookManagementModalLabel">Add Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body px-4 pt-0">
                    <form id="bookForm" action="{{ route('admin.books.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" id="bookMethod" value="POST">
                        <input type="hidden" name="id" id="bookId">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="bookName" class="form-label">Book Name</label>
                                <input type="text" class="form-control" id="bookName" name="book_name"
                                    placeholder="Enter book name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="vendor" class="form-label">Vendor</label>
                                <input type="text" class="form-control" id="vendor" name="vendor"
                                    placeholder="Enter vendor name" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" min="1"
                                    placeholder="Enter quantity" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">Price per Book</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" class="form-control" id="price" name="price"
                                        placeholder="0.00" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="totalAmount" class="form-label">Total Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="totalAmount" name="total_amount" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="purchase_date" class="form-label">Purchase Date</label>
                                <input type="date" class="form-control" id="purchase_date" name="purchase_date">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="invoice" class="form-label">Invoice</label>
                                <input type="text" class="form-control" id="invoice" name="invoice"
                                    placeholder="Enter invoice number">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-outline-danger" id="clearBookForm">Clear Form</button>
                            <button type="submit" class="btn btn-primary" id="bookSubmitButton">Add Book</button>
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

        // Live Search
        document.getElementById('live-search').addEventListener('keyup', function () {
            const query = this.value.toLowerCase();
            const rows = document.getElementById('book-table-body').getElementsByTagName('tr');
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

        // Delete Book
        document.querySelectorAll('.delete-book').forEach(button => {
            button.addEventListener('click', function () {
                const bookId = this.dataset.id;
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
                        form.action = `/admin/books/${bookId}`;
                        form.innerHTML = '@csrf @method("DELETE")';
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

        // Calculate Total
        const quantityInput = document.getElementById('quantity');
        const priceInput = document.getElementById('price');
        const totalAmountInput = document.getElementById('totalAmount');
        const clearFormBtn = document.getElementById('clearBookForm');
        const bookForm = document.getElementById('bookForm');

        function calculateTotal() {
            const quantity = parseFloat(quantityInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            totalAmountInput.value = (quantity * price).toFixed(2);
        }

        quantityInput.addEventListener('input', calculateTotal);
        priceInput.addEventListener('input', calculateTotal);

        // Clear Form for Add
        clearFormBtn.addEventListener('click', () => {
            bookForm.reset();
            document.getElementById('bookId').value = '';
            document.getElementById('bookMethod').value = 'POST';
            bookForm.action = "{{ route('admin.books.store') }}";
            document.getElementById('bookManagementModalLabel').textContent = 'Add Book';
            document.getElementById('bookSubmitButton').textContent = 'Add Book';
            totalAmountInput.value = '';
        });

        // Edit Book
        document.querySelectorAll('.edit-book').forEach(button => {
            button.addEventListener('click', async () => {
                const bookId = button.dataset.id;
                const response = await fetch(`/admin/books/${bookId}/edit`);
                const book = await response.json();

                document.getElementById('bookManagementModalLabel').textContent = 'Edit Book';
                document.getElementById('bookSubmitButton').textContent = 'Update Book';
                document.getElementById('bookMethod').value = 'PUT';
                document.getElementById('bookId').value = book.id;

                // Update form action dynamically for PUT request
                bookForm.action = `/admin/books/${book.id}`;

                // Fill form fields
                document.getElementById('bookName').value = book.book_name;
                document.getElementById('vendor').value = book.vendor;
                document.getElementById('quantity').value = book.quantity;
                document.getElementById('price').value = book.price;
                totalAmountInput.value = (book.quantity * book.price).toFixed(2);
                document.getElementById('purchase_date').value = book.purchase_date || '';
                document.getElementById('invoice').value = book.invoice || '';

                new bootstrap.Modal(document.getElementById('bookManagementModal')).show();
            });
        });
    </script>

@endpush