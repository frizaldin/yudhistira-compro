<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureTeacherHubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $features = [
            ['title' => 'Artikel Guru', 'code' => 'blog_teacher_hubs', 'type' => 'master data'],
            ['title' => 'Kategori Artikel Guru', 'code' => 'category_teacher_hubs', 'type' => 'master data'],
            ['title' => 'Pengumuman Guru', 'code' => 'announcement_teacher_hubs', 'type' => 'master data'],
            ['title' => 'Kategori Pengumuman Guru', 'code' => 'category_announcement_teacher_hubs', 'type' => 'master data'],
            ['title' => 'Event Guru', 'code' => 'event_teacher_hubs', 'type' => 'master data'],
            ['title' => 'Reward Guru', 'code' => 'teacher_reward', 'type' => 'master data'],
            ['title' => 'Digital Learning Support', 'code' => 'digital_learning_support', 'type' => 'master data'],
            ['title' => 'Buku Panduan', 'code' => 'guide_book', 'type' => 'master data'],
            ['title' => 'Kategori Buku Panduan', 'code' => 'category_guide_book', 'type' => 'master data'],
            ['title' => 'Open Tiket', 'code' => 'support_centers', 'type' => 'master data'],
            ['title' => 'Open Ticket', 'code' => 'open_tickets', 'type' => 'master data'],
            ['title' => 'Request Buku Digital', 'code' => 'request_books', 'type' => 'master data'],
            ['title' => 'Creative Teacher', 'code' => 'creative_teachers', 'type' => 'master data'],
            ['title' => 'Video Learning', 'code' => 'video_learnings', 'type' => 'master data'],
            ['title' => 'Kategori Video Learning', 'code' => 'category_video_learning', 'type' => 'master data'],
            ['title' => 'Tutorial Video', 'code' => 'tutorial_videos', 'type' => 'master data'],
        ];

        foreach ($features as $feature) {
            Feature::firstOrCreate(
                ['code' => $feature['code']],
                $feature
            );
        }
    }
}
