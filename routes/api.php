<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BinomController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DemmandeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PfeController;
use App\Http\Controllers\ProfController;
use App\Http\Controllers\PropositionController;
use App\Http\Controllers\SoutnanceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SuiviPfeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ValidationPfeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// AUTHENTIFIED USER CAN DO :
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/annonces', [AnnonceController::class, 'index']); //DISPLAY ALL
    Route::get('/annonces/{annonce}', [AnnonceController::class, 'show']);// DISPLAY SINGLE

    Route::get('/pfes/status', [PfeController::class, 'pfeStatus']);

    Route::get('/pfes/filtre/{type}', [PfeController::class, 'pfeByType']);

    Route::get('/pfes/statistic', [PfeController::class, 'getStatisticPfe']);



    Route::post('/criter/{idCriter}', [PropositionController::class, 'deleteCriter']);

    Route::post('/suivis/note', [SuiviPfeController::class, 'noteEssaie']); // ADD NOTE

    Route::post('/pfes/envoie', [PfeController::class, 'sendMemoireToJurys']); // ADD NOTE


    Route::get('/demandes/proposition/{idProposition}', [DemmandeController::class, 'getDemandeProp']);



    Route::get('/chats', [ChatController::class, 'index']); //DISPLAY ALL
    Route::get('/chats/{id}', [ChatController::class, 'show']);
    Route::post('/chats', [ChatController::class, 'sendMessage']);

    Route::post('/rdvs', [SuiviPfeController::class, 'askForRdv']);
    Route::get('/rdvs', [SuiviPfeController::class, 'getAllRdv']);
    Route::get('/rdvs/profs', [SuiviPfeController::class, 'getAllRdvProf']);
    Route::post('/rdvs/resume', [SuiviPfeController::class, 'sendResume']);
    Route::post('/rdvs/decision', [SuiviPfeController::class, 'acceptRdv']);//{date:dateTime}
    Route::post('/rdvs/done', [SuiviPfeController::class, 'rdvDone']);//{date:dateTime}

    Route::get('/notifications', [NotificationController::class, 'index']);// DISPLAY ALL NOTIFICATIONS
    Route::post('/notifications', [NotificationController::class, 'store']);// DISPLAY ALL NOTIFICATIONS
    Route::delete('/notifications', [NotificationController::class, 'destroy']);// DISPLAY ALL NOTIFICATIONS


    Route::get('/binoms/all', [BinomController::class, 'getListBinomTwoByTwo']); // DISPLAY LIST STUDEN HAVE NOT BINOM
    Route::get('/binoms', [BinomController::class, 'getListBinoms']); // DISPLAY LIST STUDEN HAVE NOT BINOM
    Route::post('/binoms/request', [BinomController::class, 'sendBinomRequest']);// SEND BINOM REQUEST
    Route::get('/binoms/request/list', [BinomController::class, 'allBinomRequest']);// DISPLAY LIST OF REQUEST
    Route::post('/binoms/request/cancel/{id}', [BinomController::class, 'cancelRequest']);// CANCLE A REQUEST
    Route::post('/binoms/request/accref', [BinomController::class, 'acceptOrRefuseBinomRequest']); // ACCEPT OR REFUSE DEMMADNE
    Route::get('/binoms/demmande', [BinomController::class, 'allBinomDemmande']);//DISPLAY ALL DEMMANDE
    Route::post('/binoms', [BinomController::class, 'choseBinom']); // CHOSE BINOM FOR PERSON WHO HAVE CODE
    Route::post('/binoms/add', [BinomController::class, 'addMySelf']); // CHOSE BINOM FOR PERSON WHO HAVE CODE

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    // Logged User
    Route::get('/user', [AuthenticatedSessionController::class, 'user']);

    // Propositions
    Route::post('/propositions', [PropositionController::class, 'store']); //ADD
    Route::get('/propositions/mes', [PropositionController::class, 'mesProposition']); //ADD

    Route::post('/propositions/take', [PropositionController::class, 'takeProposition']); //ADD
    Route::get('/proposition/etudients', [PropositionController::class, 'propositionEtudients']); //ADD
    Route::get('/propositions', [PropositionController::class, 'index']); //DISPLAY ALL
    Route::get('/propositions/{id}', [PropositionController::class, 'show']);// DISPLAY SINGLE
    Route::post('/propositions/delete/{proposition}', [PropositionController::class, 'destroy']); // DELETE
    Route::post('/propositions/edit/{proposition}', [PropositionController::class, 'update']); // EDIT
    Route::post('/propositions/recomandation', [PfeController::class, 'recomandationSjtPfes']); // EDIT




    //demandes
    Route::get('/demandes', [DemmandeController::class, 'index']); //DISPLAY ALL
    Route::get('/demandes/{demmande}', [DemmandeController::class, 'show']); // DISPLAY SINGL

    // category
    Route::get('/category', [CategoryController::class, 'index']); // DISPLAY ALL
    Route::get('/category/{id}', [CategoryController::class, 'show']); // DISPLAY SINGL


    // Pfes
    Route::get('/pfes', [PfeController::class, 'index']); // DISPLAY ALL

    Route::get('/pfes/commission', [PfeController::class, 'pfesNeedCommisionSuivis']); // DISPLAY ALL
    Route::get('/pfes/commission/{pfe}', [PfeController::class, 'show']); // DISPLAY ALL

    Route::get('/pfes/{pfe}', [PfeController::class, 'show']); // DISPLAY ALL

    Route::post('/pfes/cansend', [PfeController::class, 'allowBinomToSendProject']);


    Route::post('/pfes/dateSoutn', [SoutnanceController::class, 'store']);

    Route::get('/soutnances', [SoutnanceController::class, 'index']);

    Route::get('/soutnances/{soutnance}', [SoutnanceController::class, 'show']);



    Route::post('/pfes/note', [PfeController::class, 'addNotePfe']);







    Route::post('/pfes/suivi', [SuiviPfeController::class, 'store']);
    Route::get('/pfes/suivi/mes', [SuiviPfeController::class, 'mesSuivis']);




    Route::post('/pfes/validate', [ValidationPfeController::class, 'validatePfe']);

    Route::post('/pfes/validate/profs', [PfeController::class, 'assignPfeToValidator']);

    Route::get('/pfes/mes/all', [PfeController::class, 'mesPfes']);

    Route::post('/pfes/choose/validators', [PfeController::class, 'chooseValidatorsManually']);

    Route::post('/pfes/choose/commission', [PfeController::class, 'assignCommissioDeSuivi']);


    Route::post('/pfes/choose/jurys', [PfeController::class, 'assignJuryToPfe']);




    Route::get('/pfes/recommande/validators/{pfe}', [PfeController::class, 'getRecomandedProf']); // STATUS
    Route::get('/pfes/recommande/commission/{pfe}', [ValidationPfeController::class, 'getRecomandedCommissionDeSuivi']); // STATUS



    Route::post('/demandes/decision/', [DemmandeController::class, 'acceptOrRejectDemande']);

    Route::get('/pfes/validators/pfes', [ValidationPfeController::class, 'pfeShouldValidatedByProf']); // ADD



});

// UNAUTHNTIFIED USER CAN DO
Route::middleware(['guest'])->group(function () {
    // Login Register
    Route::get('/semantic', [PfeController::class, 'semanticSearchFunction']); // EDIT
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/send/{productName}', [BinomController::class, 'sendMailProduct']); // CHOSE BINOM FOR PERSON WHO HAVE CODE

});


// ADMIN CAN DO:
Route::middleware(['auth:sanctum', 'admin'])->group(function () {




    //Category Routes
    Route::post('/category', [CategoryController::class, 'store']); // ADD
    Route::put('/category/{id}', [CategoryController::class, 'edit']);// EDIT
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);// DELETE

    // Pfe Route
    Route::post('/pfes', [PfeController::class, 'store']); // ADD
    Route::put('/pfes/{pfe}', [CategoryController::class, 'edit']); // EDIT
    Route::delete('/pfes/{pfe}', [CategoryController::class, 'destroy']); //DELETE

    Route::post('/pfes/validators', [ValidationPfeController::class, 'store']);
    Route::get('/pfes/validators/profs', [ValidationPfeController::class, 'index']);






    // Annonces
    Route::post('/annonces', [AnnonceController::class, 'store']); //ADD


    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/admins', [AdminController::class, 'index']);
    Route::get('/users/admins/{admin}', [AdminController::class, 'show']);
    Route::get('/users/students', [StudentController::class, 'index']);
    Route::get('/users/students/{student}', [StudentController::class, 'show']);
    Route::get('/users/profs', [ProfController::class, 'index']);
    Route::get('/users/profs/{prof}', [ProfController::class, 'show']);

    Route::get('/users/profs/filtre/{type}', [ProfController::class, 'getProfByType']);

    Route::delete('/annonces/{annonce}', [AnnonceController::class, 'destroy']); // DELETE
    Route::put('/annonces/{annonce}', [AnnonceController::class, 'update']); // EDIT





});

// PROF CAN DO:
Route::middleware(['auth:sanctum', 'prof'])->group(function () {
    //Suivis PFE
    Route::get('/suivis', [SuiviPfeController::class, 'mesSuivis']); // DISPLAY ALL

    Route::get('/suivis/{suiviPfe}', [SuiviPfeController::class, 'show']); // DISPLAY SINGL

// ADD



});

// STUDENT CAN DO:
Route::middleware(['auth:sanctum', 'student'])->group(function () {
    // demandes
    Route::post('/demandes', [DemmandeController::class, 'store']); // ADD
    Route::delete('/demandes/{demmande}', [DemmandeController::class, 'destroy']); // DELETE


    //Suivis
    Route::get('/suivis', [SuiviPfeController::class, 'mesSuivis']); // DISPLAY SUIVIS
    Route::post('/suivis', [SuiviPfeController::class, 'store']); // ADD
    Route::get('/suivis/{suiviPfe}', [SuiviPfeController::class, 'show']);// DISPLAY SINGL

    // Binoms////
   // Route::get('/binoms/all', [BinomController::class, 'getListBinomTwoByTwo']); // DISPLAY LIST STUDEN HAVE NOT BINOM
    //Route::get('/binoms', [BinomController::class, 'getListBinoms']); // DISPLAY LIST STUDEN HAVE NOT BINOM
    //Route::post('/binoms/request', [BinomController::class, 'sendBinomRequest']);// SEND BINOM REQUEST
   // Route::get('/binoms/request/list', [BinomController::class, 'allBinomRequest']);// DISPLAY LIST OF REQUEST
   // Route::post('/binoms/request/cancel/{id}', [BinomController::class, 'cancelRequest']);// CANCLE A REQUEST
   // Route::post('/binoms/request/accref', [BinomController::class, 'acceptOrRefuseBinomRequest']); // ACCEPT OR REFUSE DEMMADNE
   // Route::post('/binoms/demmande', [BinomController::class, 'allBinomDemmande']);//DISPLAY ALL DEMMANDE
   // Route::post('/binoms', [BinomController::class, 'choseBinom']); // CHOSE BINOM FOR PERSON WHO HAVE CODE

});

