<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    //
    public function index()
    {
        $books = Book::all();
        // return view with all books
    }

    public function create(Request $request)
    {
        // implement book creation logic
        $request->validate([
            'name' => 'required|string|max:255',
            'ISBN' => 'required|numeric|unique:books',
            'value' => 'required|numeric',
        ]);

        // create the new book in the database
        $book = Book::create([
            'status' => 'active',
            'name' => $request->name,
            'ISBN' => $request->ISBN,
            'value' => $request->value,
        ]);

        // redirect the user to the books index page with a success message
        return redirect()->route('books.index')->with('success', 'Book created successfully.');
    }

    public function update(Request $request, $id)
    {
        // implement book update logic
        // find the book by ID
        $book = Book::findOrFail($id);

        // validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'ISBN' => 'required|numeric|unique:books,ISBN,'.$book->id,
            'value' => 'required|numeric',
        ]);

        // update the book in the database
        $book->fill([
            'status' => $request->status,
            'name' => $request->name,
            'ISBN' => $request->ISBN,
            'value' => $request->value,
        ])->save();

        // redirect the user to the books index page with a success message
        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }

    public function delete($id)
    {
        // implement book deletion logic
        // find the book by ID
        $book = Book::findOrFail($id);

        // delete the book from the database
        $book->delete();

        // redirect the user to the books index page with a success message
        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }
}
