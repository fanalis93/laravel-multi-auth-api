<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Student\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// Route::get('/test', [AuthController::class, 'test']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user/{id}', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

//Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware(['auth:sanctum', 'student.auth'])->prefix('student')->group(function () {
    // Routes accessible to students only
    Route::get('/user', [StudentController::class, 'user']);
});

Route::middleware(['auth:sanctum', 'client.auth'])->group(function () {
    // Routes accessible to clients only
    Route::get('/client/user', [ClientController::class, 'user']);
});

Route::middleware(['auth:sanctum', 'admin.auth'])->group(function () {
    // Routes accessible to admins only
    Route::get('/admin/user', [AdminController::class, 'user']);
});
