<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\VerificationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('{any}', function () {
    return view('welcome');
})->where('any', '^(?!email).*');

Route::get('/email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');

use App\Http\Controllers\V1\ReportController;
Route::get('/email/report/{id}/pdf', [ReportController::class, 'pdf']);