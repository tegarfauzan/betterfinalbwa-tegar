<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingTransactionController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\HospitalSpecialistController;
use App\Http\Controllers\MyOrderController;
use App\Http\Controllers\SpecialistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ini untuk postman, buat testing login pake token
Route::post('token-login', [AuthController::class, 'tokenLogin']);
Route::post('register', [AuthController::class, 'register']);

// ini untuk testing login pake session, buat frontend react, karena react itu bisa handle session juga, jadi kita bisa pake login biasa tanpa token
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    // endpoint ini cuma “mengambil data user dari session/token yang sudah autentikasi”, bukan dari data yang dikirim di body request.
    Route::get('user', [AuthController::class, 'user']);
});

// Install Laravel Sanctum
// Install Spatie Permission
Route::middleware(['auth:sanctum', 'role:manager'])->group(function () {

    Route::apiResource('specialists', SpecialistController::class);
    Route::apiResource('doctors', DoctorController::class);
    Route::apiResource('hospitals', HospitalController::class);
    
    Route::post('hospitals/{hospital}/specialists', [HospitalSpecialistController::class, 'attach']);
    Route::delete('hospitals/{hospital}/specialists/{specialist}', [HospitalSpecialistController::class, 'detach']);
    
    // nampilin dan update status transaksi buat manager
    Route::apiResource('transactions', BookingTransactionController::class);
    Route::patch('/transactions/{id}/status', [BookingTransactionController::class, 'updateStatus']);
    
});

Route::middleware(['auth:sanctum', 'role:customer|manager'])->group(function () {
    Route::get('specialists', [SpecialistController::class, 'index']);
    Route::get('specialists/{specialist}', [SpecialistController::class, 'show']);

    Route::get('hospitals', [HospitalController::class, 'index']);
    Route::get('hospitals/{hospital}', [HospitalController::class, 'show']);
    
    Route::get('doctors', [DoctorController::class, 'index']);
    Route::get('doctors/{doctor}', [DoctorController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'role:customer'])->group(function () {
    
    Route::get('/doctors-filter', [DoctorController::class, 'filterBySpecialistAndHospital']);
    Route::get('/doctors/{doctorId}/available-slots', [DoctorController::class, 'availableSlots']);
    
    // order transaksi buat customer
    Route::get('my-orders', [MyOrderController::class, 'index']);
    Route::post('my-orders', [MyOrderController::class, 'store']);
    Route::get('my-orders/{id}', [MyOrderController::class, 'show']);
    
});
