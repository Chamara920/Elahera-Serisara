<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\DestinationCategory;
use Illuminate\Database\Seeder;

class DestinationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cats = [
            [
                'name_en' => 'Historical & Cultural',
                'name_si' => 'ඓතිහාසික හා සංස්කෘතික',
                'slug' => 'historical-cultural',
                'icon' => 'heroicon-o-academic-cap',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name_en' => 'Nature & Wildlife',
                'name_si' => 'සොබාදහම සහ වනජීවී',
                'slug' => 'nature-wildlife',
                'icon' => 'heroicon-o-sparkles',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name_en' => 'Viewpoints',
                'name_si' => 'දසුන් නැරඹුම් ස්ථාන',
                'slug' => 'viewpoints',
                'icon' => 'heroicon-o-eye',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name_en' => 'Waterfalls',
                'name_si' => 'දියඇලි',
                'slug' => 'waterfalls',
                'icon' => 'heroicon-o-cloud',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name_en' => 'Canals & Lakes',
                'name_si' => 'ඇළ මාර්ග සහ ජලාශ',
                'slug' => 'canals-lakes',
                'icon' => 'heroicon-o-map-pin',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($cats as $c) {
            $category = DestinationCategory::updateOrCreate(['slug' => $c['slug']], $c);
            Destination::where('category', $c['name_en'])->update(['category_id' => $category->id]);
        }
    }
}
