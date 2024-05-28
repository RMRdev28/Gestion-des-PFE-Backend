<?php

namespace App\Http\Controllers;

use App\Models\Soutnance;
use Illuminate\Http\Request;

class SoutnanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            $soutnance = new Soutnance();
            $soutnance->idPfe =$request->idPfe;
            $soutnance->date = $request->dateStn;
            $soutnance->salle = $request->salleStn;
            $soutnance->save();
            return response()->json([
                'message'=>"La date de soutenance est ajouté avec successe",
                'status'=>"good"
            ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Soutnance $soutnance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Soutnance $soutnance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Soutnance $soutnance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Soutnance $soutnance)
    {
        //
    }
}
