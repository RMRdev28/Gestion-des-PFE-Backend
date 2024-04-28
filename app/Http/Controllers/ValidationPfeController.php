<?php

namespace App\Http\Controllers;

use App\Models\Prof;
use App\Models\ValidationPfe;
use Illuminate\Http\Request;

class ValidationPfeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $validateursPfe  = Prof::where('isValidator',1)->get();
        return response()->json($validateursPfe);
    }



    /**
     * this function is to mak a prof as validator
     */
    public function store(Request $request)
    {
        $prof = Prof::find($request->idProf);
        $prof->isValidator == 1;
        if($prof->save()){
            return response()->json([
                'message'=> "The prof is a valdator now",
                'status' => "good"
            ]);
        }
        return response()->json([
            'message'=> "Problem in valiating prof",
            'status' => "bed"
        ]);

    }
}
