<?php

namespace App\Http\Controllers;

use App\Models\Pfe;
use App\Models\Prof;
use App\Models\User;
use App\Models\Soutnance;
use Illuminate\Http\Request;

class SoutnanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $soutnances = Soutnance::all();
        foreach($soutnances as $st){
            $pfe = Pfe::find($st->idPfe);
            $jury1 = Prof::find($pfe->jury1);
            $jury2 = Prof::find($pfe->jury2);
            $userJ1 = User::find($jury1->idUser);
            $userJ2 = User::find($jury2->idUser);
            $jury1Name = $userJ1->lname." ".$userJ1->fname;
            $jury2Name = $userJ2->lname." ".$userJ2->fname;
            $pfe->$jury1Name = $jury1Name;
            $pfe->$jury2Name = $jury2Name;
            $st->pfe = $pfe;
            dd($st);
        }
        return response()->json($soutnances);
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
