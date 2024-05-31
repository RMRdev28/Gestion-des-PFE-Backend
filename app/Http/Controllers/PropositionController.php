<?php

namespace App\Http\Controllers;

use App\Models\Attachement;
use App\Models\Category;
use App\Models\Criter;
use App\Models\Demmande;
use App\Models\Proposition;
use App\Models\propositionCategory;
use App\Models\PropsCriter;
use App\Models\User;
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

            return Proposition::where('id',$id)->with(['demmandes','categories','details','demmandes.binom','demmandes.binom.student1','demmandes.binom.student2','demmandes.binom.student1.user','demmandes.binom.student2.user'])->first();
        }
     }

    public function index()
    {
        $propositions = Proposition::all();
        foreach($propositions as $prop){
            $created_by= User::find($prop->idUser);
            $prop->created_by = $created_by->lname. " " .$created_by->fname;
            $nbrDeamnde = Demmande::where('idProp',$prop->id)->count();
            $categoryIds = propositionCategory::where('idProp',$prop->id)->pluck('idCategory');
            $categories = Category::whereIn('id',$categoryIds)->get();
            $prop->categories = $categories;
            $prop->nbrDemande = $nbrDeamnde;
        }
        return response()->json($propositions);
    }


    public function mesProposition(){
        $propositions = Proposition::where('idUser',Auth::user()->id)->get();
        foreach($propositions as $prop){
            $nbrDeamnde = Demmande::where('idProp',$prop->id)->count();
            $categoryIds = propositionCategory::where('idProp',$prop->id)->pluck('idCategory');
            $categories = Category::whereIn('id',$categoryIds)->get();
            $prop->categories = $categories;
            $prop->nbrDemande = $nbrDeamnde;
        }
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
            $typePrposition = "interne";
            $needSuivis = 0;
        }else{
            $typePrposition = $request->type;
            $needSuivis = $request->need_suivi;
        }
        $request->request->add(['type'=> $typePrposition]);
        $request->request->add(['idUser'=> Auth::user()->id]);
        $request->request->add(['need_suivis'=> $needSuivis]);
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
            if($request->categories){
                foreach($request->categories as $category){
                    $propCategory = new propositionCategory();
                    $propCategory->idCategory = $category;
                    $propCategory->idProp = $proposition->id;
                    $propCategory->save();

                }
            }

            if($request->criters){
                foreach ($request->criters as $c) {
                    $criter = new Criter();
                    $criter->title = $c['name'];
                    $criter->save();
                    $criterProposition = new PropsCriter();
                    $criterProposition->idCriter = $criter->id;
                    $criterProposition->idProp = $proposition->id;
                    $criterProposition->valeur = $c['value'];
                    $criterProposition->save();

                }
            }

            $message = "Proposition Ajouter avec successe";
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
        $proposition = Proposition::find($id);
        $created_by = User::find($proposition->idUser);
        if($created_by->typeUser == 0){
            $proposition->ens = "Student";
        }elseif($created_by->typeUser == 1){
            $proposition->ens = "Prof";
        }else{
            $proposition->ens = "Admin";
        }
        $categoryIds = propositionCategory::where('idProp',$proposition->id)->pluck('idCategory');
        $critersIds = PropsCriter::where('idProp',$proposition->id)->pluck('idCriter');
        $criters = Criter::whereIn('id', $critersIds)->get();
        foreach ($criters as $criter) {
            $propsCriter = PropsCriter::where('idProp', $proposition->id)
                ->where('idCriter', $criter->id)
                ->first();
            $criter->value = $propsCriter->valeur;
        }
        $proposition->criters = $criters;


        $categories = Category::whereIn('id',$categoryIds)->get();
        $proposition->categories = $categories;
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
        if($proposition->update($data)){
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


            if($request->categories){
                foreach($request->categories as $category)  {
                    $proposition->categories()->sync($category);
                }
            }
            if($request->criters){
                foreach($request->criters as $criter)  {
                    $proposition->catecritersgories()->sync($criter);
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
