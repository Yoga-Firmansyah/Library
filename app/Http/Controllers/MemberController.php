<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.member');
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
            'sex' => 'required',
            'email' => 'required|email|unique:members',
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            Member::create([
                'name'   => $request->name,
                'sex' => $request->sex,
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
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required',
            'sex' => 'required',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone_number' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            $member->update([
                'name'   => $request->name,
                'sex' => $request->sex,
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
    public function destroy(Member $member)
    {
        $member->delete();

        if ($member) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }

    public function api(Request $request)
    {
        $sex = $request->sex;
        if ($sex) {
            $members = Member::where('sex', $sex)->get();
        } else {
            $members = Member::with('user', 'transactions')->get();
        }
        $dataTables = datatables()->of($members)
            ->addColumn('date', function ($member) {
                return convert_date($member->created_at);
            })
            ->addColumn('sex2', function ($member) {
                return $member->sex == 'M' ? 'Male' : 'Female';
            })
            ->addIndexColumn();

        return $dataTables->make(true);
    }
}
