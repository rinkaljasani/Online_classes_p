<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\GeneralController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('get-project', [GeneralController::class,'getProject'])->name('general.get.project');
Route::post('register', [AuthenticationController::class,'register'])->name('user.register');
Route::post('get-plan', [GeneralController::class,'getPlan'])->name('general.get.plan');
