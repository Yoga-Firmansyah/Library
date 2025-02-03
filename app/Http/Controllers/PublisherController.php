<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publishers = Publisher::with('books')->get();
        return view('admin.publisher', compact('publishers'));
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
            'email' => 'required|email|unique:publishers',
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            Publisher::create([
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
    public function show(Publisher $publisher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publisher $publisher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publisher $publisher)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'email' => 'required|email|unique:publishers,email,' . $publisher->id,
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            $publisher->update([
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
    public function destroy(Publisher $publisher)
    {
        $publisher->delete();

        if ($publisher) {
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
        $publishers = Publisher::with('books')->get();
        $dataTables = datatables()->of($publishers)->addColumn('date', function ($publisher) {
            return convert_date($publisher->created_at);
        })->addIndexColumn();
        return $dataTables->make(true);
    }
}
