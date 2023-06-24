<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class BookController extends Controller
{
    //
    public function index()
    {
        try {
            if (Auth::check()) {

                $books = Book::all()->toArray();
                $response = [
                    'success' => true,
                    'books' => $books,
                ];
                return response()->json($response, 200);
            }
            $response = [
                'success' => false,
                'message' => 'Usuário nao autenticado',
            ];
            return response()->json($response, 401);
        } catch (\Throwable $th) {
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

    public function create(Request $request)
    {
        // implement book creation logic
        $request->validate([
            'name' => 'required|string|max:255',
            'ISBN' => 'required|numeric|unique:books',
            'value' => 'required|numeric',
        ]);
        try {
            //code...
            // create the new book in the database
            if(Auth::check()){

                $book = Book::create([
                    'status' => 'active',
                    'name' => $request->name,
                    'ISBN' => $request->ISBN,
                    'value' => $request->value,
                ]);

                $response = [
                    'success' => true,
                    'message' => 'livro criado com sucesso',
                ];
                return response()->json($response, 200);
            }
            $response = [
                'success' => false,
                'message' => 'Usuário nao autenticado',
            ];
            return response()->json($response, 401);
        } catch (\Throwable $th) {
            //throw $th;
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }


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
        try {
            //code...
            if(Auth::check()) {
                // update the book in the database
                $book->fill([
                    'status' => $request->status,
                    'name' => $request->name,
                    'ISBN' => $request->ISBN,
                    'value' => $request->value,
                ])->save();

                if($book) {
                    $response = [
                        'success' => true,
                        'message' => 'Livro atualizado com sucesso',
                    ];
                    return response()->json($response, 200);
                }
            }

            $response = [
                'success' => false,
                'message' => 'Usuário nao autenticado',
            ];
            return response()->json($response, 401);
        } catch (\Throwable $th) {
            //throw $th;
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }

    }

    public function delete($id)
    {
        // implement book deletion logic
        // find the book by ID
        try {
            //code...
            if(Auth::check()) {

                $book = Book::findOrFail($id);
                // delete the book from the database
                $book->delete();

                $response = [
                    'success' => true,
                    'message' => 'Livro removido com sucesso',
                ];
                return response()->json($response, 200);
            }
            $response = [
                'success' => false,
                'message' => 'Usuário nao autenticado',
            ];
            return response()->json($response, 401);
        } catch (\Throwable $th) {
            //throw $th;
            $response = [
                'success' => false,
                'message' => $th->getMessage(),
            ];
            return response()->json($response, 500);
        }


    }
}
