<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransaksiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/login-admin', [AuthController::class, 'loginAdmin']);
Route::post('/login-superadmin', [AuthController::class, 'loginSuperadmin']);


//Protecting Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', function(Request $request) {
        return auth()->user();
    });

    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{id}', [TicketController::class, 'show']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::put('/tickets/{id}', [TicketController::class, 'update']);
    Route::delete('/tickets/{id}', [TicketController::class, 'destroy']);

    Route::post('/transaksi', [TransaksiController::class, 'pushCustomer']);
    Route::get('/transaksi', [TransaksiController::class, 'index']);
    Route::get('/transaksiById/{id}', [TransaksiController::class, 'getById']);
    Route::delete('/delete-transaksi/{id}', [TransaksiController::class, 'deleteCustomer']);
    Route::put('/update-transaksi/{id}', [TransaksiController::class, 'updateCustomer']);

    Route::put('/update-role', [AuthController::class, 'updateRole']);

    // API route for logout user
    Route::post('/logout', [AuthController::class, 'logout']);
});
