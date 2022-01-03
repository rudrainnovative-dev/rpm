<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\DashboardController;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\CategoryController;
use App\Http\Controllers\V1\QuestionController;
use App\Http\Controllers\V1\TypeController;
use App\Http\Controllers\V1\TestController;
use App\Http\Controllers\V1\TestpurposeController;
use App\Http\Controllers\V1\PerformanceController;
use App\Http\Controllers\V1\AssignController;
use App\Http\Controllers\V1\RegisterfieldController;
use App\Http\Controllers\V1\OnlinetestController;
use App\Http\Controllers\V1\ReportController;

use App\Models\Criteria;
use App\Models\Performancetype;
use App\Models\Test;

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
Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::post('/password/email', [AuthController::class, 'sendResetLinkResponse']);
Route::post('/otp/verify', [AuthController::class, 'otpVerification']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {

    Route::resource('type', TypeController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('question', QuestionController::class);
    Route::resource('test', TestController::class);
    Route::resource('testpurpose', TestpurposeController::class);
    Route::resource('performance', PerformanceController::class);
    Route::resource('assign', AssignController::class);
    Route::resource('registation-field', RegisterfieldController::class);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/question/test/{test_id}', [QuestionController::class, 'getTestQuestion']);
    Route::post('/question/upload', [QuestionController::class, 'uploadQuestions']);
    Route::post('test/duplicate', [TestController::class, 'duplicate']);
    Route::get('test/{id}/questions', [TestController::class, 'questions']);
    Route::get('candidate/{id}/show', [AssignController::class, 'showCandidate']);

    Route::get('/report/{id}/test', [ReportController::class, 'index']);
    Route::get('/report/{id}/show', [ReportController::class, 'show']);
    Route::get('/report/{id}/pdf', [ReportController::class, 'pdf']);
    Route::post('/report/send/bulk/email/pdf', [ReportController::class, 'sendEmailPDF']);

    Route::get('/criterias', function() {
        return response()->json([
            'message' => 'All criterias.',
            'criterias' => Criteria::get()
        ], 200);
    });

    Route::get('/performance-type', function() {
        return response()->json([
            'message' => 'All Performance.',
            'performance_type' => Performancetype::get()
        ], 200);
    });

    Route::get('/all_tests', function() {
        return response()->json([
            'message' => 'All Test.',
            'tests' => Test::orderBy('id','desc')->select('id', 'name', 'assessment_type')->get()
        ], 200);
    });
});


Route::get('/online-test/{id}', [OnlinetestController::class, 'index']);
Route::post('/online-test/{id}', [OnlinetestController::class, 'registation']);
Route::get('/online-test/{id}/question', [OnlinetestController::class, 'question']);
Route::get('/online-test/{id}/questions', [OnlinetestController::class, 'questions']);
Route::post('/online-test-answered', [OnlinetestController::class, 'answered']);
Route::post('/online-test-taker', [OnlinetestController::class, 'taker_update']);
Route::post('/online-test-snap', [OnlinetestController::class, 'taker_snap']);
Route::post('/online-test-screenshot', [OnlinetestController::class, 'taker_screenshot']);
Route::post('/online-test/{id}/update', [OnlinetestController::class, 'updateStatus']);
Route::get('/online-test/{id}/report', [OnlinetestController::class, 'report']);
Route::get('/share-report', [ReportController::class, 'reportCandidate']);