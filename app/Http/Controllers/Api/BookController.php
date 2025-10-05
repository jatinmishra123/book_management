<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Books; // Using 'Book' as per your original file, not 'Books'
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $books = Books::orderBy('id', 'desc')->paginate(10);
        return response()->json($books);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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

        $book = Books::create($data);

        return response()->json([
            'message' => 'Book added successfully.',
            'book' => $book
        ], 201); // 201 Created status code
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Books  $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Books $book)
    {
        return response()->json($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Books  $book
     * @return \Illuminate\Http\JsonResponse
     */
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

        return response()->json([
            'message' => 'Book updated successfully.',
            'book' => $book
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Books  $book
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Books $book)
    {
        $book->delete();
        return response()->json(['message' => 'Book deleted successfully.']);
    }
}