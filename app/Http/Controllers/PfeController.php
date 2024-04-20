<?php

namespace App\Http\Controllers;

use App\Models\Pfe;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;

class PfeController extends Controller
{
    use UploadTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pfes = Pfe::all();
        return response()->json($pfes);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = "";
        $status = "bad";
        $data = $request->all();
        $pfe = Pfe::create($data);
        if ($pfe) {
            if ($request->hasFile('pfe')) {
                $fileUploaded = $this->upload($request->pfe, 'pfe');
                if ($fileUploaded) {
                    $pfe->pfe = $fileUploaded['originalName'];
                    $pfe->save();
                }
            }
            $message = "The pfe is updated secessfully";
            $status = "good";
        } else {
            $message = "Error adding pfe";
        }
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);


    }

    /**
     * Display the specified resource.
     */
    public function show(Pfe $pfe)
    {
        return response()->json($pfe);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pfe $pfe)
    {
        $message = "";
        $status = "bad";
        $data = $request->all();
        if ($pfe->update($data)) {
            if ($request->hasFile('pfe')) {
                $fileUploaded = $this->upload($request->file, 'pfe');
                if ($fileUploaded) {
                    $deletedFile = $this->deleteFileFromStorage($pfe->pfe, 'pfe');
                    if ($deletedFile) {
                        $pfe->pfe = $fileUploaded['originalName'];
                        if ($pfe->save()) {
                            $message = "The pfe is updated secssfully";
                            $status = "good";
                        }
                    }

                } else {
                    $message = "Error editing file";
                }


            }
            $message = "The pfe is updated secssfully";
            $status = "good";
        } else {
            $message = "Error updating info pfe";
        }
        return response()->json([
            'message' => $message,
            'status' => $status,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pfe $pfe)
    {
        $message = "";
        $status = "bad";
        $fileDeleted = $this->deleteFileFromStorage($pfe->pfe, 'pfe');
        if ($fileDeleted) {
            $pfe->delete();
            $message = "The pfe is deleted secessfully";
            $status = "good";
        } else {
            $message = "Error delteing file";
        }
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }
}
