<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BinomController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DemmandeController;
use App\Http\Controllers\PfeController;
use App\Http\Controllers\PropositionController;
use App\Http\Controllers\SuiviPfeController;
use App\Http\Controllers\UserController;
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
    // Logout
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    // Logged User
    Route::get('/user', [AuthenticatedSessionController::class, 'user']);

    // Propositions
    Route::post('/propositions', [PropositionController::class, 'store']); //ADD
    Route::get('/propositions', [PropositionController::class, 'index']); //DISPLAY ALL
    Route::get('/propositions/{id}', [PropositionController::class, 'show']);// DISPLAY SINGLE
    Route::delete('/propositions/{id}', [PropositionController::class, 'destroy']); // DELETE
    Route::put('/propositions/{id}', [PropositionController::class, 'edit']); // EDIT

    //Demmandes
    Route::get('/demmandes', [DemmandeController::class, 'index']); //DISPLAY ALL
    Route::get('/demmandes/{demmande}', [DemmandeController::class, 'show']); // DISPLAY SINGL

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']); // DISPLAY ALL
    Route::get('/categories/{id}', [CategoryController::class, 'show']); // DISPLAY SINGL


    // Pfes
    Route::get('/pfes', [PfeController::class, 'index']); // DISPLAY ALL





});

// UNAUTHNTIFIED USER CAN DO
Route::middleware(['guest'])->group(function () {
    // Login Register
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/send/{productName}', [BinomController::class, 'sendMailProduct']); // CHOSE BINOM FOR PERSON WHO HAVE CODE

});


// ADMIN CAN DO:
Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    //Category Routes
    Route::post('/categories', [CategoryController::class, 'store']); // ADD
    Route::put('/categories/{id}', [CategoryController::class, 'edit']);// EDIT
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);// DELETE

    // Pfe Route
    Route::post('/pfes', [PfeController::class, 'store']); // ADD
    Route::put('/pfes/{pfe}', [CategoryController::class, 'edit']); // EDIT
    Route::delete('/pfes/{pfe}', [CategoryController::class, 'destroy']); //DELETE


});

// PROF CAN DO:
Route::middleware(['auth:sanctum', 'prof'])->group(function () {
    //Suivis PFE
    Route::get('/suivis', [SuiviPfeController::class, 'mesSuivis']); // DISPLAY ALL
    Route::post('/suivis/note', [SuiviPfeController::class, 'noteEssaie']); // ADD NOTE
    Route::get('/suivis/{suiviPfe}', [SuiviPfeController::class, 'show']); // DISPLAY SINGL

});

// STUDENT CAN DO:
Route::middleware(['auth:sanctum', 'student'])->group(function () {
    // Demmandes
    Route::post('/demmandes', [DemmandeController::class, 'store']); // ADD
    Route::delete('/demmandes/{demmande}', [DemmandeController::class, 'destroy']); // DELETE
    Route::put('/demmandes/{demmande}', [DemmandeController::class, 'edit']); // EDIT

    //Suivis
    Route::get('/suivis', [SuiviPfeController::class, 'mesSuivis']); // DISPLAY SUIVIS
    Route::post('/suivis', [SuiviPfeController::class, 'store']); // ADD
    Route::get('/suivis/{suiviPfe}', [SuiviPfeController::class, 'show']);// DISPLAY SINGL

    // Binoms
    Route::get('/binoms', [BinomController::class, 'getListBinoms']); // DISPLAY LIST STUDEN HAVE NOT BINOM
    Route::post('/binoms/request', [BinomController::class, 'sendBinomRequest']);// SEND BINOM REQUEST
    Route::get('/binoms/request/list', [BinomController::class, 'allBinomRequest']);// DISPLAY LIST OF REQUEST
    Route::post('/binoms/request/cancel/{id}', [BinomController::class, 'cancelRequest']);// CANCLE A REQUEST
    Route::post('/binoms/request/accref', [BinomController::class, 'acceptOrRefuseBinomRequest']); // ACCEPT OR REFUSE DEMMADNE
    Route::post('/binoms/demmande', [BinomController::class, 'allBinomDemmande']);//DISPLAY ALL DEMMANDE
    Route::post('/binoms', [BinomController::class, 'choseBinom']); // CHOSE BINOM FOR PERSON WHO HAVE CODE

});
