<?php

namespace App\Http\Controllers;

use App\Models\Prof;
use App\Models\User;
use Illuminate\Http\Request;

class ProfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profs = User::where('typeUser',1)->with(['userDetail'])->get();
        return response()->json($profs);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Prof $prof)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Prof $prof)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prof $prof)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prof $prof)
    {
        //
    }
}
