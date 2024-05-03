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

        $errors = [];
        foreach($request->profs as $prof){

            $prof = Prof::find($prof);
            $prof->isValidator == 1;
            if(!$prof->save()){
                $errors[] = $prof->name;
            }
        }
        if(count($errors) > 0){
            $message = "Not all selected prof are validated";
            $status = "bad";
        }else{
            $message = "good";
            $status = "good";
        }
        return response()->json([
            'message'=> $message,
            'status' => $status,
            'errors' => $errors
        ]);


    }
}
