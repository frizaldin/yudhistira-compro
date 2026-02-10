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
        ];

        foreach ($features as $feature) {
            Feature::firstOrCreate(
                ['code' => $feature['code']],
                $feature
            );
        }
    }
}
