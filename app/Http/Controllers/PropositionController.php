<?php

namespace App\Http\Controllers;

use App\Models\Attachement;
use App\Models\Proposition;
use App\Models\propositionCategory;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropositionController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     */

     public function getProposition($id = null)
     {
        if($id == null){
            return Proposition::with(['demmandes','categories','details'])->get();
        }else{
            return Proposition::where('id',$id)->with(['demmandes','categories','details','demmandes.binom','demmandes.binom.student1','demmandes.binom.student2','demmandes.binom.student1.user','demmandes.binom.student2.user'])->get();
        }
     }

    public function index()
    {
        $propositions = $this->getProposition();
        return response()->json($propositions);
    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = "";
        $status = "bad";
        $typePrposition = "";
        if(Auth::user()->typeUser == 0){
            $typePrposition = "student";
        }elseif(Auth::user()->typeUser == 1){
            $typePrposition = "prof";
        }else{
            $typePrposition = "entreprise";
        }
        $request->request->add(['type'=> $typePrposition]);
        $request->request->add(['idUser'=> Auth::user()->id]);
        $data = $request->all();

        $proposition = Proposition::create($data);
        if ($proposition) {
            if ($request->hasFile('files')) {
                foreach ($request->files as $file) {
                    $fileUploaded = $this->upload($file, "proposition");
                    if ($fileUploaded) {
                        $attachement = new Attachement();
                        $attachement->path = $fileUploaded['originalName'];
                        $attachement->type = "Type";
                        $attachement->size = $fileUploaded['size'];
                        $attachement->idProp = $proposition->id;
                        $attachement->save();
                    }
                }
            }
            foreach($request->categories as $category){
                $propCategory = new propositionCategory();
                $propCategory->idCategory = $category;
                $propCategory->idProp = $proposition->id;
                $propCategory->save();

            }
            $message = "Proposition created secssefully";
            $status = "good";
        }

        return response()->json([
            'message' => $message,
            'status' => $status,
            'proposition' => $proposition
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $proposition = $this->getProposition($id);
        return response()->json($proposition);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proposition $proposition)
    {
        $message = "";
        $status = "bad";
        $data = $request->all();
        if($proposition->update()){
            if ($request->hasFile('files')) {
                foreach ($request->files as $file) {
                    $fileUploaded = $this->upload($file, "proposition");
                    if ($fileUploaded) {
                        $attachement = new Attachement();
                        $attachement->path = $fileUploaded['originalName'];
                        $attachement->type = $fileUploaded['type'];
                        $attachement->size = $fileUploaded['size'];
                        $attachement->idProp = $proposition->id;
                        $attachement->save();
                    }
                }
            }
            $status = "good";
            $message = "Proposition is updated secssfully";
        }else{
            $message = "Error updateing proposition";
        }
        return response()->json([
            'message'=> $message,
            'status' => $status,
        ]);

    }


    public function removeFile($fileId){
        $message = "";
        $status = "bad";
        $attachemnt = Attachement::find($fileId);
        if($this->deleteFileFromStorage($attachemnt->path,'proposition')){
            if($attachemnt->delete()){
                $message = "The file is delted seccssfully";
                $status = "good";
            }else{
                $message = "The file is not delted from database";
            }
        }else{
            $message = "The file is not exisit";
        }
        return response()->json([
            'message' => $message,
            'status'=> $status
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proposition $proposition)
    {
        $message = "Error deliting proposition";
        $status = "bad";
        $attachments = Attachement::where('idPropo',$proposition->id)->get();
        foreach ($attachments as $at) {
            $fileDeleted = $this->deleteFileFromStorage($at->path,'proposition');
            if($fileDeleted){
                $at->delete();
            }
        }
        if($proposition->delete()){
            $message = "The proposition is deleted seccessfully";
            $status = "good";
        }
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }
}
