<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\ForumCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Only essential administrative accounts and category structures are initialized.
     * No dummy/initial destinations, blog posts, news, or reviews are included.
     */
    public function run(): void
    {
        // 1. Create System Administrator Account from .env configuration
        $adminEmail = env('ADMIN_EMAIL', 'admin@elaheraserisara.lk');
        $adminPassword = env('ADMIN_PASSWORD', 'password123');
        $adminName = env('ADMIN_NAME', 'System Administrator');

        $admin = User::firstOrNew(['email' => $adminEmail]);
        $admin->name = $adminName;
        $admin->password = Hash::make($adminPassword);
        $admin->role = 'admin';
        $admin->phone = '+94 66 225 6100';
        $admin->bio = 'System Administrator for Elahera Serisara Tourism Platform.';
        $admin->security_question = 'What is your primary school name?';
        $admin->security_answer = Hash::make(mb_strtolower('Admin School'));
        $admin->save();

        // 2. Base Categories (Optional classification taxonomy)
        $this->call(DestinationCategorySeeder::class);

        BlogCategory::firstOrCreate(['slug' => 'travel-stories'], ['name_en' => 'Travel Stories', 'name_si' => 'සංචාරක අත්දැකීම්']);
        BlogCategory::firstOrCreate(['slug' => 'heritage-history'], ['name_en' => 'Heritage & History', 'name_si' => 'උරුමය සහ ඉතිහාසය']);
        BlogCategory::firstOrCreate(['slug' => 'nature-wildlife'], ['name_en' => 'Nature & Wildlife', 'name_si' => 'සොබාදහම සහ වනජීවී']);

        ForumCategory::firstOrCreate(['slug' => 'general-travel-advice'], ['name_en' => 'General Travel Advice', 'name_si' => 'පොදු සංචාරක උපදෙස්', 'description' => 'General queries about visiting Elahera Serisara']);
        ForumCategory::firstOrCreate(['slug' => 'public-transit-routes'], ['name_en' => 'Public Transit & Routes', 'name_si' => 'පොදු ප්‍රවාහන මාර්ග', 'description' => 'Bakamuna bus routes and connections']);
    }
}
