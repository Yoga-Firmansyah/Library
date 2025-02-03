<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Catalog;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $catalogs = Catalog::all();
        return view('admin.book', compact('publishers', 'authors', 'catalogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isbn'  => 'required|numeric|digits:9|unique:books',
            'title'  => 'required',
            'year' => 'required|numeric|digits:4',
            'publisher_id'  => 'required',
            'author_id'  => 'required',
            'catalog_id'  => 'required',
            'qty' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            Book::create([
                'isbn'   => $request->isbn,
                'title' => $request->title,
                'year'   => $request->year,
                'publisher_id'   => $request->publisher_id,
                'author_id'   => $request->author_id,
                'catalog_id'   => $request->catalog_id,
                'qty'   => $request->qty,
                'price'   => $request->price,
            ]);

            return response()->json([
                'success'   => true,
                'message' => 'Data Saved Successfully!'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validator = Validator::make($request->all(), [
            'isbn'  => 'required|numeric|digits:9|unique:books,isbn,' . $book->id,
            'title'  => 'required',
            'year' => 'required|numeric|digits:4',
            'publisher_id'  => 'required',
            'author_id'  => 'required',
            'catalog_id'  => 'required',
            'qty' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            $book->update([
                'isbn'   => $request->isbn,
                'title' => $request->title,
                'year'   => $request->year,
                'publisher_id'   => $request->publisher_id,
                'author_id'   => $request->author_id,
                'catalog_id'   => $request->catalog_id,
                'qty'   => $request->qty,
                'price'   => $request->price,
            ]);

            return response()->json([
                'success'   => true,
                'message' => 'Data Updated Successfully!'
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();

        if ($book) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }

    public function api()
    {
        $books = Book::all();

        foreach ($books as $key => $book) {
            $book->date = convert_date($book->created_at);
            $book->price2 = convert_price($book->price);
        }
        
        return json_encode($books);
    }
}
