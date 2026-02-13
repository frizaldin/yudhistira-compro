<?php

use App\Http\Controllers\Api\SignInController;
use App\Http\Controllers\Api\SignUpController;
use App\Http\Controllers\Api\SupportCenterApiController;
use App\Http\Controllers\Api\SupportCenterChatApiController;
use App\Http\Controllers\Api\OpenTicketApiController;
use App\Http\Controllers\Api\OpenTicketChatApiController;
use App\Http\Controllers\Api\TeacherBookApiController;
use App\Http\Controllers\Api\BlogTeacherHubApiController;
use App\Http\Controllers\Api\CategoryBlogTeacherHubApiController;
use App\Http\Controllers\Api\AnnouncementTeacherHubApiController;
use App\Http\Controllers\Api\CategoryAnnouncementTeacherHubApiController;
use App\Http\Controllers\Api\EventTeacherHubApiController;
use App\Http\Controllers\Api\BlogAndEventTeacherHubApiController;
use App\Http\Controllers\Api\TeacherRewardApiController;
use App\Http\Controllers\Api\DigitalLearningSupportApiController;
use App\Http\Controllers\Api\CategoryGuideBookApiController;
use App\Http\Controllers\Api\GuideBookApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    // Blog Teacher Hub
    Route::get('/blog-teacher-hubs', [BlogTeacherHubApiController::class, 'index']);
    Route::get('/blog-teacher-hubs/{id}', [BlogTeacherHubApiController::class, 'show']);
    Route::get('/category-teacher-hubs', [CategoryBlogTeacherHubApiController::class, 'index']);

    // Announcement Teacher Hub
    Route::get('/announcement-teacher-hubs', [AnnouncementTeacherHubApiController::class, 'index']);
    Route::get('/announcement-teacher-hubs/{id}', [AnnouncementTeacherHubApiController::class, 'show']);
    Route::get('/category-announcement-teacher-hubs', [CategoryAnnouncementTeacherHubApiController::class, 'index']);

    // Event Teacher Hub (questions route harus di atas {id} agar tidak tertimpa)
    Route::get('/event-teacher-hubs', [EventTeacherHubApiController::class, 'index']);
    Route::get('/event-teacher-hubs/{id}/questions', [EventTeacherHubApiController::class, 'questions']);
    Route::get('/event-teacher-hubs/{id}', [EventTeacherHubApiController::class, 'show']);
    Route::get('/category-event-teacher-hubs', [EventTeacherHubApiController::class, 'categories']);

    // Blog + Event (feed gabungan)
    Route::get('/blog-and-event-teacher-hubs', [BlogAndEventTeacherHubApiController::class, 'index']);
    Route::get('/category-blog-and-event-teacher-hubs', [BlogAndEventTeacherHubApiController::class, 'categories']);

    // Lainnya
    Route::get('/teacher-rewards', [TeacherRewardApiController::class, 'index']);
    Route::get('/digital-learning-supports', [DigitalLearningSupportApiController::class, 'index']);
    Route::get('/digital-learning-supports/{id}', [DigitalLearningSupportApiController::class, 'show']);
    Route::get('/category-guide-books', [CategoryGuideBookApiController::class, 'index']);

    // Guide Book
    Route::get('/guide-books', [GuideBookApiController::class, 'index']);
    Route::get('/guide-books/{id}', [GuideBookApiController::class, 'show']);

    Route::get('/teacher-books/products', [TeacherBookApiController::class, 'products']);
});

Route::middleware('auth.teacher.api')->group(function () {
    Route::post('/teacher-books/redeem', [TeacherBookApiController::class, 'redeem']);
    Route::get('/teacher-books/my-books', [TeacherBookApiController::class, 'myBooks']);
    Route::get('/teacher-books/my-events', [TeacherBookApiController::class, 'myEvents']);
    Route::get('/teacher-books/my-points', [TeacherBookApiController::class, 'myPoints']);
    Route::get('/teacher-books/dashboard-count', [TeacherBookApiController::class, 'dashboardCount']);
    Route::post('/event-teacher-hubs/answer', [EventTeacherHubApiController::class, 'submitAnswer']);

    Route::get('/support-centers', [SupportCenterApiController::class, 'index']);
    Route::get('/support-centers/{id}', [SupportCenterApiController::class, 'show']);
    Route::post('/support-centers/create', [SupportCenterApiController::class, 'store']);
    Route::put('/support-centers/update/{id}', [SupportCenterApiController::class, 'update']);
    Route::delete('/support-centers/delete/{id}', [SupportCenterApiController::class, 'destroy']);

    Route::get('/support-centers/chats', [SupportCenterChatApiController::class, 'index']);
    Route::post('/support-centers/chats/store', [SupportCenterChatApiController::class, 'store']);
    Route::put('/support-centers/chats/update/{chatId}', [SupportCenterChatApiController::class, 'update']);
    Route::delete('/support-centers/chats/delete/{chatId}', [SupportCenterChatApiController::class, 'destroy']);

    Route::get('/open-tickets', [OpenTicketApiController::class, 'index']);
    Route::get('/open-tickets/{id}', [OpenTicketApiController::class, 'show']);
    Route::post('/open-tickets/create', [OpenTicketApiController::class, 'store']);
    Route::put('/open-tickets/update/{id}', [OpenTicketApiController::class, 'update']);
    Route::delete('/open-tickets/delete/{id}', [OpenTicketApiController::class, 'destroy']);

    Route::get('/open-tickets/chats', [OpenTicketChatApiController::class, 'index']);
    Route::post('/open-tickets/chats/store', [OpenTicketChatApiController::class, 'store']);
    Route::put('/open-tickets/chats/update/{chatId}', [OpenTicketChatApiController::class, 'update']);
    Route::delete('/open-tickets/chats/delete/{chatId}', [OpenTicketChatApiController::class, 'destroy']);
});

Route::prefix('sign-up')->controller(SignUpController::class)->group(function () {
    Route::post('run', 'container');
    Route::get('data', 'container');
});

Route::prefix('sign-in')->controller(SignInController::class)->group(function () {
    Route::post('/', 'run');
});
