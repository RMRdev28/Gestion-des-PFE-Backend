<?php

namespace App\Http\Controllers;

use App\Mail\BinomRequestAccepted;
use App\Mail\BinomRequestCancled;
use App\Mail\BinomRequestRejected;
use App\Mail\NewBinomRequest;
use App\Models\Binom;
use App\Models\Student;
use App\Models\User;
use App\Traits\NotifyTrait;
use App\Traits\SendEmailTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BinomController extends Controller
{
    use SendEmailTrait, NotifyTrait;


    public function getListBinomTwoByTwo(){
        $binoms = Binom::with(['student1','student2','student2.user','student2.user'])->where('type','valid')->get();
        return response()->json($binoms);
    }
    public function getListBinoms()
    {
        $users = Student::where('idUser', '<>', Auth::user()->id)->where('haveBinom', -1)->where('level', Auth::user()->userDetail->level)->where('specialite', Auth::user()->specialite)->get();
        return response()->json($users);
    }

    public function choseBinom(Request $request)
    {
        $code = $request->code;
        $message = "";
        $status = "bad";
        $userBinom = Student::where('uniqueCode', $code)->first();
        if ($userBinom->haveBinom == 1) {
            $message = "This user is already have binom";
        } else {
            $message = "Seccessfully";
            $status = "good";
        }
        $binom = new Binom();
        $binom->idEtu1 = Auth::user()->userDetail->id;
        $binom->idEtu2 = $userBinom->id;
        $binom->save();
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }

    public function sendBinomRequest(Request $request)
    {
        $message = "";
        $status = "bad";
        $idBinom = $request->idBinom;
        $student = Student::find($idBinom);
        $binomUser = User::find($student->idUser);
        $binom = new Binom();
        // convention que l user 1 c lutilisateur qui envoi la demmande
        $binom->idEtu1 = Auth::user()->userDetail->id;
        $binom->idEtu2 = $idBinom;
        $binom->type = "request";

        if ($binom->save()) {
            $mailToBinom = new NewBinomRequest($binomUser, Auth::user());
            if ($this->sendEmail($binomUser->email, $mailToBinom)) {
                $this->notify($binomUser->id,"New Binom request","You have new binom request from :".Auth::user()->fname."-".Auth::user()->lname);
                $message = "Request send secssfully";
                $status = "good";
            } else {
                $message = "They are problem sending message";
            }
        } else {
            $message = "They are problem save request";
        }
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }


    public function allBinomRequest()
    {
        $binomRequests = Binom::where('type', "request")->where('idEtu2', Auth::user()->userDetail->id)->get();
        return response()->json($binomRequests);
    }

    public function cancelRequest($id)
    {
        $message = "";
        $status = "bad";
        $binom = Binom::find($id);
        $binomUser = Student::find($binom->idEtu2);
        if ($binom->delete()) {
            $mailToBinom = new BinomRequestCancled($binomUser, Auth::user()->id);
            if ($this->sendEmail($binomUser->email, $mailToBinom)) {
                $message = "The request is cancled secssfully";
                $status = "good";
            } else {
                $message = "Error sending email";
            }
        } else {
            $message = "Error cancling request";
        }
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }

    public function allBinomDemmande()
    {
        $binomDemmandes = Binom::where('type', "request")->where('idEtu1', Auth::user()->userDetail->id)->get();
        return response()->json($binomDemmandes);
    }


    public function acceptOrRefuseBinomRequest(Request $request)
    {
        $message = "";
        $status = "bad";
        $id = $request->id;
        $binom = Binom::find($id);
        $requester = User::find($binom->idEtu1);
        $binomUser = User::find($binom->idEtu2);
        if ($request->type == 0) {
            if ($binom->delete()) {
                $mailToUser = new BinomRequestRejected($binomUser, $requester);
                if ($this->sendEmail($requester->email, $mailToUser)) {
                    $message = "The request is rejected";
                    $status = "good";
                } else {
                    $message = "Error sending Email";
                }
            } else {
                $message = "Error reject the requester";
            }
        } else {
            $binom->type = "valid";
            if ($binom->save()) {
                $mailToUser = new BinomRequestAccepted($binomUser, $requester);
                if ($this->sendEmail($requester->email, $mailToUser)) {
                    if ($this->deleteAllOtherRequest($id)) {
                        $message = "The request is accepted";
                        $status = "good";
                    } else {
                        $message = "Error delting other requests";
                    }

                } else {
                    $message = "Problem sending email";
                }
            } else {
                $message = "Error saving request";
            }

        }
        return response()->json([
            'message' => $message,
            'status' => $status
        ]);
    }

    private function deleteAllOtherRequest($id)
    {

        if (Binom::where('id', '!=', $id)->delete())
            return true;
        return false;

    }


}
