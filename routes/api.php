<?php

use App\Http\Controllers\Api\V1\Auth\AuthenticationController;
use App\Http\Controllers\Api\V1\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthenticationController::class, 'register'])->name('regster');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
Route::post('/verify', [AuthenticationController::class, 'verify'])->name('verify');
Route::post('/resetpassword', [AuthenticationController::class, 'resetPassword'])->name('resetPassword');
Route::post('/resendVerificationMail', [AuthenticationController::class, 'resendVerificationEmail'])->name('resendVerificationEmail');

Route::middleware(['auth:sanctum','role:Admin'])->group(function(){
    Route::apiResource('/posts', PostController::class);
});
