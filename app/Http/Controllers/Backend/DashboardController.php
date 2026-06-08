<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AnnouncementTeacherHub;
use App\Models\BlogTeacherHub;
use App\Models\CategoryAnnouncementTeacherHub;
use App\Models\CategoryEventTeacherHub;
use App\Models\CategoryGuideBook;
use App\Models\CategoryTeacherHub;
use App\Models\CreativeTeacher;
use App\Models\DigitalLearningSupport;
use App\Models\EventTeacherHub;
use App\Models\GuideBook;
use App\Models\OpenTicket;
use App\Models\SupportCenter;
use App\Models\Teacher;
use App\Models\TeacherBookmark;
use App\Models\TeacherReward;
use App\Models\TutorialVideo;
use App\Models\VideoLearning;
use App\Models\VideoLearningCategory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function index()
    {
        $user = null;
        if (auth()->user()) {
            $user = auth()->user();
        } elseif (auth()->guard('company')->user()) {
            $user = auth()->guard('company')->user();
        } elseif (auth()->guard('supplier')->user()) {
            $user = auth()->guard('supplier')->user();
        }
        $data['user'] = $user;

        // Statistik untuk authority_id == 5 (menu Teacher Hub)
        $data['teacher_hub_stats'] = [];
        $data['top_teachers_by_points'] = [];
        $data['top_teachers_by_events'] = [];
        $data['top_teachers_by_video_learnings'] = [];
        $data['top_teachers_by_engagement'] = [];

        if (auth()->check() && auth()->user()->authorities_id == 5) {
            $data['teacher_hub_stats'] = [
                ['label' => 'Artikel', 'slug' => 'blog_teacher_hubs', 'count' => BlogTeacherHub::count(), 'icon' => 'iconoir-document', 'color' => 'warning'],
                ['label' => 'Kategori Artikel Guru', 'slug' => 'category_teacher_hubs', 'count' => CategoryTeacherHub::count(), 'icon' => 'iconoir-folder', 'color' => 'warning'],
                ['label' => 'Pengumuman Guru', 'slug' => 'announcement_teacher_hubs', 'count' => AnnouncementTeacherHub::count(), 'icon' => 'iconoir-megaphone', 'color' => 'info'],
                ['label' => 'Kategori Pengumuman Guru', 'slug' => 'category_announcement_teacher_hubs', 'count' => CategoryAnnouncementTeacherHub::count(), 'icon' => 'iconoir-folder', 'color' => 'info'],
                ['label' => 'Event Guru', 'slug' => 'event_teacher_hubs', 'count' => EventTeacherHub::count(), 'icon' => 'iconoir-calendar', 'color' => 'primary'],
                ['label' => 'Teacher Reward', 'slug' => 'teacher_reward', 'count' => TeacherReward::count(), 'icon' => 'iconoir-medal', 'color' => 'success'],
                ['label' => 'Kategori Event', 'slug' => 'category_event_teacher_hubs', 'count' => CategoryEventTeacherHub::count(), 'icon' => 'iconoir-folder', 'color' => 'primary'],
                ['label' => 'Guide Book', 'slug' => 'guide_book', 'count' => GuideBook::count(), 'icon' => 'iconoir-book', 'color' => 'secondary'],
                ['label' => 'Digital Learning Support', 'slug' => 'digital_learning_support', 'count' => DigitalLearningSupport::count(), 'icon' => 'iconoir-book', 'color' => 'secondary'],
                ['label' => 'Kategori Guide Book', 'slug' => 'category_guide_book', 'count' => CategoryGuideBook::count(), 'icon' => 'iconoir-folder', 'color' => 'secondary'],
                ['label' => 'Support Center', 'slug' => 'support_centers', 'count' => SupportCenter::count(), 'icon' => 'iconoir-chat-bubble', 'color' => 'info'],
                ['label' => 'Open Ticket', 'slug' => 'open_tickets', 'count' => OpenTicket::count(), 'icon' => 'iconoir-chat-bubble', 'color' => 'info'],
                ['label' => 'Creative Teacher', 'slug' => 'creative_teachers', 'count' => CreativeTeacher::count(), 'icon' => 'iconoir-folder-plus', 'color' => 'success'],
                ['label' => 'Video Learning', 'slug' => 'video_learnings', 'count' => VideoLearning::count(), 'icon' => 'iconoir-play', 'color' => 'danger'],
                ['label' => 'Kategori Video Learning', 'slug' => 'category_video_learning', 'count' => VideoLearningCategory::count(), 'icon' => 'iconoir-folder', 'color' => 'danger'],
                ['label' => 'Tutorial Video', 'slug' => 'tutorial_videos', 'count' => TutorialVideo::count(), 'icon' => 'iconoir-play', 'color' => 'danger'],
                ['label' => 'Teacher Bookmark', 'slug' => 'teacher_bookmarks', 'count' => TeacherBookmark::count(), 'icon' => 'iconoir-bookmark', 'color' => 'warning'],
                ['label' => 'Guru', 'slug' => 'teachers', 'count' => Teacher::count(), 'icon' => 'iconoir-user', 'color' => 'primary'],
            ];

            // Guru dengan point terbanyak (dari user_points)
            $data['top_teachers_by_points'] = Teacher::query()
                ->select('teachers.id', 'teachers.name', 'teachers.email', 'teachers.status')
                ->leftJoin('user_points', 'teachers.id', '=', 'user_points.user_id')
                ->groupBy('teachers.id', 'teachers.name', 'teachers.email', 'teachers.status')
                ->selectRaw('COALESCE(SUM(user_points.point), 0) as total_points')
                ->orderByDesc('total_points')
                ->limit(10)
                ->get();

            // Guru paling aktif ikut event (event_question_answers = ikut event + jawab)
            $data['top_teachers_by_events'] = Teacher::query()
                ->select('teachers.id', 'teachers.name', 'teachers.email', 'teachers.status')
                ->leftJoin('event_question_answers', 'teachers.id', '=', 'event_question_answers.user_id')
                ->groupBy('teachers.id', 'teachers.name', 'teachers.email', 'teachers.status')
                ->selectRaw('COUNT(DISTINCT event_question_answers.event_id) as events_count')
                ->orderByDesc('events_count')
                ->limit(10)
                ->get();

            // Guru paling aktif video learning (video_learning_completions)
            $data['top_teachers_by_video_learnings'] = Teacher::query()
                ->select('teachers.id', 'teachers.name', 'teachers.email', 'teachers.status')
                ->leftJoin('video_learning_completions', 'teachers.id', '=', 'video_learning_completions.user_id')
                ->groupBy('teachers.id', 'teachers.name', 'teachers.email', 'teachers.status')
                ->selectRaw('COUNT(video_learning_completions.id) as video_completions_count')
                ->orderByDesc('video_completions_count')
                ->limit(10)
                ->get();

            // Guru paling aktif bersuara: Open Ticket + Support Center + Creative Teacher
            $data['top_teachers_by_engagement'] = Teacher::query()
                ->select('teachers.id', 'teachers.name', 'teachers.email', 'teachers.status')
                ->selectRaw('
                    (SELECT COUNT(*) FROM open_tickets WHERE open_tickets.user_id = teachers.id) +
                    (SELECT COUNT(*) FROM support_centers WHERE support_centers.user_id = teachers.id) +
                    (SELECT COUNT(*) FROM creative_teachers WHERE creative_teachers.user_id = teachers.id) as engagement_count
                ')
                ->orderByDesc('engagement_count')
                ->limit(10)
                ->get();
        }

        return view('backend.dashboard.index', $data);
    }
}
