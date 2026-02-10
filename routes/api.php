<?php

use App\Http\Controllers\Api\SignUpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TeacherHubApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::get('/blog-teacher-hubs', [TeacherHubApiController::class, 'blogTeacherHubs']);
    Route::get('/blog-teacher-hubs/{id}', [TeacherHubApiController::class, 'blogTeacherHubDetail']);
    Route::get('/category-teacher-hubs', [TeacherHubApiController::class, 'categoryTeacherHubs']);
    Route::get('/announcement-teacher-hubs', [TeacherHubApiController::class, 'announcementTeacherHubs']);
    Route::get('/announcement-teacher-hubs/{id}', [TeacherHubApiController::class, 'announcementTeacherHubDetail']);
    Route::get('/category-announcement-teacher-hubs', [TeacherHubApiController::class, 'categoryAnnouncementTeacherHubs']);
    Route::get('/event-teacher-hubs', [TeacherHubApiController::class, 'eventTeacherHubs']);
    Route::get('/teacher-rewards', [TeacherHubApiController::class, 'teacherRewards']);
});

Route::prefix('sign-up')->controller(SignUpController::class)->group(function () {
    Route::post('run', 'container');
    Route::get('data', 'container');
});
