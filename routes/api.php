<?php

use App\Http\Controllers\Api\SignInController;
use App\Http\Controllers\Api\SignUpController;
use App\Http\Controllers\Api\SupportCenterApiController;
use App\Http\Controllers\Api\SupportCenterChatApiController;
use App\Http\Controllers\Api\OpenTicketApiController;
use App\Http\Controllers\Api\OpenTicketChatApiController;
use App\Http\Controllers\Api\CreativeTeacherApiController;
use App\Http\Controllers\Api\VideoLearningApiController;
use App\Http\Controllers\Api\EventAndVideoLearningApiController;
use App\Http\Controllers\Api\BookmarkApiController;
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
use App\Http\Controllers\Api\TutorialVideoApiController;
use App\Http\Controllers\Api\ReferenceDataApiController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\RequestBookApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Email verification (route bernama verification.verify untuk link di email)
Route::get('/v1/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->name('verification.verify');
Route::post('/v1/email/resend', [EmailVerificationController::class, 'resend']);

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

    Route::get('/video-learning-categories', [VideoLearningApiController::class, 'categories']);
    Route::get('/video-learnings', [VideoLearningApiController::class, 'index']);

    Route::get('/event-and-video-learnings', [EventAndVideoLearningApiController::class, 'index']);

    Route::get('/tutorial-videos', [TutorialVideoApiController::class, 'index']);
    Route::get('/tutorial-videos/{id}', [TutorialVideoApiController::class, 'show']);

    // Reference data (provinces, cities, mata_pelajaran)
    Route::get('/provinces', [ReferenceDataApiController::class, 'provinces']);
    Route::get('/cities', [ReferenceDataApiController::class, 'cities']);
    Route::get('/mata-pelajaran', [ReferenceDataApiController::class, 'mataPelajaran']);
});

Route::middleware('auth.teacher.api')->group(function () {
    // Profile (user login, update profile, update password)
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile-edit', [ProfileController::class, 'update']);
    Route::put('/profile-edit-password', [ProfileController::class, 'updatePassword']);

    Route::post('/teacher-books/redeem', [TeacherBookApiController::class, 'redeem']);
    Route::post('/request-books/create', [RequestBookApiController::class, 'store']);
    Route::get('/request-books', [RequestBookApiController::class, 'index']);
    Route::get('/request-books/{id}', [RequestBookApiController::class, 'show']);
    Route::get('/teacher-books/my-books', [TeacherBookApiController::class, 'myBooks']);
    Route::get('/teacher-books/my-events', [TeacherBookApiController::class, 'myEvents']);
    Route::get('/teacher-books/my-event', [TeacherBookApiController::class, 'myEvent']);
    Route::get('/teacher-books/my-learning', [TeacherBookApiController::class, 'myLearning']);
    Route::get('/teacher-books/my-event-completed', [TeacherBookApiController::class, 'myEventCompleted']);
    Route::get('/teacher-books/my-learning-completed', [TeacherBookApiController::class, 'myLearningCompleted']);
    Route::get('/teacher-books/my-points', [TeacherBookApiController::class, 'myPoints']);
    Route::get('/teacher-books/dashboard-count', [TeacherBookApiController::class, 'dashboardCount']);
    Route::post('/event-teacher-hubs/answer', [EventTeacherHubApiController::class, 'submitAnswer']);
    Route::post('/event-teacher-hubs/complete-via-link', [EventTeacherHubApiController::class, 'completeViaLink']);
    Route::get('/event-teacher-hubs/certificate', [EventTeacherHubApiController::class, 'certificate']);

    Route::get('/support-centers', [SupportCenterApiController::class, 'index']);
    Route::get('/support-centers/{id}', [SupportCenterApiController::class, 'show']);
    Route::post('/support-centers/create', [SupportCenterApiController::class, 'store']);
    Route::put('/support-centers/update/{id}', [SupportCenterApiController::class, 'update']);
    Route::delete('/support-centers/delete/{id}', [SupportCenterApiController::class, 'destroy']);

    Route::get('/support-center/chats', [SupportCenterChatApiController::class, 'index']);
    Route::post('/support-center/chats/store', [SupportCenterChatApiController::class, 'store']);
    Route::put('/support-center/chats/update/{chatId}', [SupportCenterChatApiController::class, 'update']);
    Route::delete('/support-center/chats/delete/{chatId}', [SupportCenterChatApiController::class, 'destroy']);

    Route::get('/open-tickets', [OpenTicketApiController::class, 'index']);
    Route::get('/open-tickets/{id}', [OpenTicketApiController::class, 'show']);
    Route::post('/open-tickets/create', [OpenTicketApiController::class, 'store']);
    Route::put('/open-tickets/update/{id}', [OpenTicketApiController::class, 'update']);
    Route::delete('/open-tickets/delete/{id}', [OpenTicketApiController::class, 'destroy']);

    Route::get('/open-ticket/chats', [OpenTicketChatApiController::class, 'index']);
    Route::post('/open-ticket/chats/store', [OpenTicketChatApiController::class, 'store']);
    Route::put('/open-ticket/chats/update/{chatId}', [OpenTicketChatApiController::class, 'update']);
    Route::delete('/open-ticket/chats/delete/{chatId}', [OpenTicketChatApiController::class, 'destroy']);

    Route::get('/creative-teachers', [CreativeTeacherApiController::class, 'index']);
    Route::get('/creative-teachers/{id}', [CreativeTeacherApiController::class, 'show']);
    Route::post('/creative-teachers/create', [CreativeTeacherApiController::class, 'store']);
    Route::put('/creative-teachers/update/{id}', [CreativeTeacherApiController::class, 'update']);
    Route::delete('/creative-teachers/delete/{id}', [CreativeTeacherApiController::class, 'destroy']);

    Route::get('/video-learnings/{id}', [VideoLearningApiController::class, 'show']);
    Route::post('/video-learnings/complete-video', [VideoLearningApiController::class, 'completeVideo']);
    Route::post('/video-learnings/quiz', [VideoLearningApiController::class, 'submitQuiz']);

    Route::get('/bookmarks', [BookmarkApiController::class, 'index']);
    Route::post('/bookmark/follow', [BookmarkApiController::class, 'store']);
    Route::delete('/bookmark/delete', [BookmarkApiController::class, 'destroy']);
});

Route::prefix('sign-up')->controller(SignUpController::class)->group(function () {
    Route::post('run', 'container');
    Route::get('data', 'container');
});

Route::prefix('sign-in')->controller(SignInController::class)->group(function () {
    Route::post('/', 'run');
});
