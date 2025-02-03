<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.author');
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
            'name'  => 'required',
            'email' => 'required|email|unique:authors',
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            Author::create([
                'name'   => $request->name,
                'email'   => $request->email,
                'phone_number'   => $request->phone_number,
                'address'   => $request->address,
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
    public function show(Author $author)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'email' => 'required|email|unique:authors,email,' . $author->id,
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            $author->update([
                'name'   => $request->name,
                'email'   => $request->email,
                'phone_number'   => $request->phone_number,
                'address'   => $request->address,
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
    public function destroy(Author $author)
    {
        $author->delete();

        if ($author) {
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
        $authors = Author::with('books')->get();

        $dataTables = datatables()->of($authors)
            ->addColumn('date', function ($author) {
                return convert_date($author->created_at);
            })->addIndexColumn();

        return $dataTables->make(true);
    }
}
