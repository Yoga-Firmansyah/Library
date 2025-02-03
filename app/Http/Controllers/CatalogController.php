<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $catalogs = Catalog::with('books')->get()->all();

        foreach ($catalogs as $key => $catalog) {
            $catalog->date = convert_date($catalog->created_at);
        }
        return view('admin.catalog.index', compact('catalogs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.catalog.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|unique:catalogs'
        ]);

        $catalog = Catalog::create([
            'name'   => $request->name,
        ]);

        if ($catalog) {
            return redirect()->route('catalogs.index')->with(['success' => 'Data Saved Successfully!']);
        } else {
            return redirect()->route('catalogs.index')->with(['error' => 'Data Failed to Save!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Catalog $catalog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Catalog $catalog)
    {
        return view('admin.catalog.edit', compact('catalog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Catalog $catalog)
    {
        $request->validate([
            'name'  => 'required|unique:catalogs'
        ]);

        $catalog->update([
            'name'   => $request->name,
        ]);

        if ($catalog) {
            return redirect()->route('catalogs.index')->with(['success' => 'Data Updated Successfully!']);
        } else {
            return redirect()->route('catalogs.index')->with(['error' => 'Data Failed to Update!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Catalog $catalog)
    {
        $catalog->delete();

        if ($catalog) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
