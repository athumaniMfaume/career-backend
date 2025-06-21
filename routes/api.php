<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\JobProgramController;
use App\Http\Controllers\CombinationController;
use App\Http\Controllers\ProgramSchoolController;
use App\Http\Controllers\SchoolCombinationController;
use App\Http\Controllers\CombinationProgramController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::middleware('auth:sanctum')->apiResource('users', UserController::class);


// Registration route
Route::post('/register', [RegisteredUserController::class, 'store']);

// Login route
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

// Logout route (protected - requires authentication)


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('subjects', SubjectController::class);
    Route::apiResource('combinations', CombinationController::class);
    Route::apiResource('program-schools', ProgramSchoolController::class);
    Route::apiResource('job-programs', JobProgramController::class);
    Route::apiResource('schools', SchoolController::class);
    Route::apiResource('jobs', JobController::class);
    Route::apiResource('careers', CareerController::class);
    Route::apiResource('quizzes', QuizController::class);
    Route::apiResource('questions', QuestionController::class);
    Route::apiResource('answers', AnswerController::class);
    Route::apiResource('locations', LocationController::class);
    Route::apiResource('schoolcombinations', SchoolCombinationController::class);
    Route::apiResource('programs', ProgramController::class);
    Route::apiResource('combination-programs', CombinationProgramController::class);
    Route::apiResource('program-schools', ProgramSchoolController::class);
    // Custom Pivot Routes
Route::post('combinations/{id}/attach-programs', [CombinationProgramController::class, 'attachPrograms']);
Route::delete('combinations/{id}/detach-programs', [CombinationProgramController::class, 'detachAllPrograms']);
Route::delete('combinations/{id}/detach-program/{programId}', [CombinationProgramController::class, 'detachProgram']);
});
