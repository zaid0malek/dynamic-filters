<?php

use App\Http\Controllers\BankdatasetController;
use App\Http\Controllers\CommitteeController;
use App\Models\Bankdataset;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/getcommittee', [CommitteeController::class, "index"])->name("fetch");
Route::get('/showcommittee', [CommitteeController::class, "showData2"])->name("show");
Route::get('/filtered', [CommitteeController::class, "filter"])->name("filter");

Route::get('/showbank', [BankdatasetController::class, "showData"])->name("showbank");
Route::get('/bankfiltered', [BankdatasetController::class, "filter"])->name("bankfilter");