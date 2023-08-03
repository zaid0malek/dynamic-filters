<?php

use App\Http\Controllers\CommitteeController;
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