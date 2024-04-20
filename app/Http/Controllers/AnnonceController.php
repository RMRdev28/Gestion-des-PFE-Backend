<?php

namespace App\Http\Controllers;

use App\Mail\newAnnonce;
use App\Models\Annonce;
use App\Models\User;
use App\Traits\SendEmailTrait;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;

class AnnonceController extends Controller
{
    use UploadTrait;
    use SendEmailTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $annonces = Annonce::all();
        return response()->json($annonces);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = "";
        $status = "bad";
        $data = $request->all();
        $annonce = Annonce::create($data);
        if ($annonce) {
            // if ($request->hasFile('background')) {
            //     $fileUploaded = $this->upload($request->background, 'annonce');
            //     if ($fileUploaded) {
            //         $annonce->background = $fileUploaded['originalName'];
            //         $annonce->save();
            //         if ($request->notify == true) {
            //             $mail = new newAnnonce($annonce);
            //             $users = User::where('typeUser', $annonce->type)->get();
            //             foreach ($users as $user) {
            //                 $this->sendEmail($user->email, $mail);
            //             }
            //         }
            //     }
            // }
            $message = "The annonce is uploaded secssfully";
            $status = "good";
        } else {
            $message = "Problem saving the annonce";
        }
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(Annonce $annonce)
    {
        return response()->json($annonce);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Annonce $annonce)
    {
        $message = "";
        $status = "bad";
        $data = $request->all();
        if ($annonce->update()) {
            // if ($request->hasFile('background')) {
            //     $fileUploaded = $this->upload($request->background, 'annonce');
            //     if ($fileUploaded) {
            //         $fileDelted = $this->deleteFileFromStorage($annonce->background, 'annonce');
            //         if ($fileDelted) {
            //             $annonce->background = $fileUploaded['originalName'];
            //             $annonce->save();
            //             if ($request->notify == true) {
            //                 $mail = new newAnnonce($annonce);
            //                 $users = User::where('typeUser', $annonce->type)->get();
            //                 foreach ($users as $user) {
            //                     $this->sendEmail($user->email, $mail);
            //                 }
            //             }
            //         }

            //     }
            // }
            $message = "The anonce is edited secessfuly";
            $status = "good";
        }else{
            $message = "Problem Editinf annonce";
        }

        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Annonce $annonce)
    {
        $message = "";
        $status = "bad";
        if(true || $this->deleteFileFromStorage($annonce->background,'annonce')){
            $annonce->delete();
            $message = "The annonce is delted secssfully";
            $status = "good";
        }else{
            $message = "They are problem delteing the annonce";

        }
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }
}
