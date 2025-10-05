<?php
// app/Http/Controllers/Admin/BookController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Books;
use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function index()
    {
        $books = Books::orderBy('id', 'desc')->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'book_name' => 'required|string|max:255',
            'vendor' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'invoice' => 'nullable|string|max:255',
        ]);

        Books::create($data);

        return redirect()->back()->with('success', 'Book added successfully.');
    }

    public function edit(Books $book)
    {
        return response()->json($book); // For AJAX edit
    }

    public function update(Request $request, Books $book)
    {
        $data = $request->validate([
            'book_name' => 'required|string|max:255',
            'vendor' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'purchase_date' => 'nullable|date',
            'invoice' => 'nullable|string|max:255',
        ]);

        $book->update($data);

        return redirect()->back()->with('success', 'Book updated successfully.');
    }

    public function destroy(Books $book)
    {
        $book->delete();
        return redirect()->back()->with('success', 'Book deleted successfully.');
    }
}

