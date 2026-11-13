<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use Illuminate\Database\Seeder;

class NewsCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name_en' => 'General News',
                'name_si' => 'පොදු පුවත්',
                'slug' => 'general-news',
                'icon' => 'heroicon-o-newspaper',
                'description' => 'General regional news and public updates across Elahera and Bakamuna.',
                'sort_order' => 1,
            ],
            [
                'name_en' => 'Festivals & Events',
                'name_si' => 'උත්සව සහ විශේෂ සිදුවීම්',
                'slug' => 'festivals-events',
                'icon' => 'heroicon-o-calendar-days',
                'description' => 'Upcoming local cultural events, sports meets, and harvest celebrations.',
                'sort_order' => 2,
            ],
            [
                'name_en' => 'Road & Travel Alerts',
                'name_si' => 'මාර්ග සහ සංචාරක නිවේදන',
                'slug' => 'travel-alerts',
                'icon' => 'heroicon-o-exclamation-triangle',
                'description' => 'Important travel notices, water levels, road conditions, and emergency alerts.',
                'sort_order' => 3,
            ],
            [
                'name_en' => 'Eco & Wildlife Conservation',
                'name_si' => 'පරිසර සහ වනජීවී සුරැකුම්',
                'slug' => 'eco-wildlife',
                'icon' => 'heroicon-o-globe-asia-australia',
                'description' => 'Elephant corridor alerts, forestry conservation programs, and green initiatives.',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $cat) {
            NewsCategory::updateOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
