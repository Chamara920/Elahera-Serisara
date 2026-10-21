<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogComment;
use App\Models\BusRoute;
use App\Models\BusRouteHalt;
use App\Models\Destination;
use App\Models\DestinationReview;
use App\Models\EmergencyContact;
use App\Models\ForumCategory;
use App\Models\ForumTopic;
use App\Models\ForumReply;
use App\Models\HeroSlider;
use App\Models\NewsEvent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create System Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@elaherasirisara.lk'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '+94 66 225 6100',
                'bio' => 'System Administrator for Elahera Sirisara Tourism Platform.',
            ]
        );

        // 2. Create Community User
        $user1 = User::firstOrCreate(
            ['email' => 'chamara@elaherasirisara.lk'],
            [
                'name' => 'Chamara Premasinghe',
                'password' => Hash::make('password123'),
                'role' => 'community_member',
                'phone' => '+94 77 123 4567',
                'bio' => 'Passionate traveler, explorer of ancient irrigation marvels and nature photographer in Elahera.',
            ]
        );

        $user2 = User::firstOrCreate(
            ['email' => 'nimal@elaherasirisara.lk'],
            [
                'name' => 'Nimal Perera',
                'password' => Hash::make('password123'),
                'role' => 'community_member',
                'phone' => '+94 71 987 6543',
                'bio' => 'Local tour guide and wildlife enthusiast based in Bakamuna.',
            ]
        );

        // 3. Hero Sliders (Admin managed)
        HeroSlider::create([
            'title_en' => 'Welcome to Elahera Sirisara',
            'title_si' => 'ඇලහැර සිරිසර වෙත සාදරයෙන් පිළිගනිමු',
            'subtitle_en' => 'Discover ancient irrigation wonders, lush green canals, and peaceful wilderness along the Amban River.',
            'subtitle_si' => 'අඹන් ගඟ සහ ඓතිහාසික ඇලහැර යෝධ ඇළ ආශ්‍රිත ස්වභාවික සිරිය, වනජීවී අසිරිය සහ අභිමානවත් වාරි ශිෂ්ටාචාරය විඳගන්න.',
            'image' => 'images/sliders/slider1.jpg',
            'button1_text_en' => 'Smart Trip Planner',
            'button1_text_si' => 'ස්මාර්ට් චාරිකා සැලසුම්කරු',
            'button1_url' => '/trip-planner',
            'button2_text_en' => 'Explore Destinations',
            'button2_text_si' => 'ගමනාන්ත ගවේෂණය',
            'button2_url' => '/destinations',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        HeroSlider::create([
            'title_en' => 'Elahera Ancient Anicut & Yodha Ela',
            'title_si' => 'පුරාණ ඇලහැර අමුණ සහ යෝධ ඇළ ගවේෂණය',
            'subtitle_en' => 'Marvel at the visionary hydrology of King Mahasen, connecting civilizations through sacred waters.',
            'subtitle_si' => 'මහසෙන් රජුගේ මහා වාරි ආශ්චර්යය හා සරුසාර කෙත්වතු පෝෂණය කළ මහා ඇළ මාර්ගයේ අසිරිය.',
            'image' => 'images/sliders/slider2.jpg',
            'button1_text_en' => 'Plan Your Visit',
            'button1_text_si' => 'චාරිකාව සැලසුම් කරන්න',
            'button1_url' => '/trip-planner',
            'button2_text_en' => 'Interactive Map',
            'button2_text_si' => 'සජීවී සිතියම',
            'button2_url' => '/map',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        HeroSlider::create([
            'title_en' => 'Angamedilla Wilderness & Canals',
            'title_si' => 'අංගමැඩිල්ල වන අරණ සහ වනජීවී අසිරිය',
            'subtitle_en' => 'Spot wild elephants, endemic bird species, and tranquil waterways where nature thrives.',
            'subtitle_si' => 'වන අලින්, පක්ෂි විශේෂ සහ හරිත වනපෙත මැදින් ගලා බසිනා නිස්කලංක දිය දහරාවන්.',
            'image' => 'images/sliders/slider3.jpg',
            'button1_text_en' => 'Explore Nature',
            'button1_text_si' => 'ස්වභාව සෞන්දර්යය',
            'button1_url' => '/destinations',
            'button2_text_en' => 'Bus Timetable',
            'button2_text_si' => 'බස් කාලසටහන',
            'button2_url' => '/public-transport',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // 4. Destination Categories
        $this->call(DestinationCategorySeeder::class);

        // 5. Destinations
        // Destination 1: Elahera Ancient Anicut (Has SI translation)
        $d1 = Destination::create([
            'title_en' => 'Elahera Ancient Anicut',
            'title_si' => 'ඇලහැර පුරාණ අමුණ',
            'slug' => 'elahera-ancient-anicut',
            'short_description_en' => 'Built across Amban Ganga by King Mahasen, this stone dam is one of Sri Lanka’s greatest ancient hydraulic masterworks.',
            'short_description_si' => 'මහසෙන් රජු විසින් අඹන් ගඟ හරහා ඉදිකළ ඓතිහාසික ගල් අමුණ සහ යෝධ ඇළෙහි ආරම්භක මහා වාරි විස්මිතය.',
            'description_en' => 'The Elahera Anicut is a monumental stone dam built across the Amban Ganga, the main tributary of the Mahaweli River. Constructed originally in the 3rd century AD by King Mahasen and renovated by King Parakramabahu the Great, it diverts water into the 40-kilometer Elahera Canal (Yodha Ela) feeding ancient reservoirs Minneriya, Kaudulla, and Kantale. Visitors can walk along the historical canal embankments, view massive ancient granite stone masonry, and experience cool river breezes.',
            'description_si' => 'ඇලහැර අමුණ යනු මහවැලි ගඟෙහි ප්‍රධාන අතු ගංගාවක් වන අඹන් ගඟ හරහා ක්‍රි.ව. 3 වන සියවසේදී මහසෙන් රජතුමා විසින් ඉදිකරවන ලද මහා වාරි කර්මාන්තයකි. පසුව මහා පරාක්‍රමබාහු රජු විසින් මෙය ප්‍රතිසංස්කරණය කරන ලදී. කිලෝමීටර් 40කට අධික දිගින් යුතු ඇලහැර යෝධ ඇළ ආරම්භ වන්නේ මෙතැනිනි. මෙමඟින් මින්නේරිය, කවුඩුල්ල සහ කන්තලේ දක්වා ජලය රැගෙන යයි. දැවැන්ත ගල් කුට්ටි භාවිතයෙන් නිමවූ මෙම අමුණ සහ සුන්දර ඇළ ඉවුර සංචාරකයින්ගේ විස්මයට හේතු වේ.',
            'category' => 'Historical & Cultural',
            'category_si' => 'ඓතිහාසික හා සංස්කෘතික',
            'route_zone' => 'Zone B - Elahera Anicut',
            'featured_image' => 'images/destinations/anicut.jpg',
            'gallery_images' => ['images/destinations/anicut1.jpg', 'images/destinations/anicut2.jpg'],
            'distance_km' => 4.2,
            'travel_time_minutes' => 12,
            'stay_duration_minutes' => 75,
            'safety_alerts_en' => '⚠️ Slippery rocks near sluice area. Do not bathe in deep channels with strong water currents during discharge hours.',
            'safety_alerts_si' => '⚠️ වාන් දොරටු අසල ගල් ලිස්සන සුළුය. ජලය නිකුත් කරන වේලාවන්හි ගැඹුරු ඇළ මාර්ග තුළ දියනෑමෙන් වළකින්න.',
            'packing_list_en' => '🎒 Drinking water, sun hat, comfortable walking shoes, insect repellent.',
            'packing_list_si' => '🎒 පානීය ජලය, හිරු ආවරණ තොප්පි, පහසු පාවහන්, කෘමි විකර්ශක.',
            'dress_code_en' => '👗 Modest clothing suitable for historical cultural heritage site.',
            'dress_code_si' => '👗 ඓතිහාසික ස්ථාන සඳහා ගැලපෙන විනීත ඇඳුම්.',
            'nearest_bus_stop' => 'Elahera Anicut Junction',
            'bus_route_numbers' => 'Route 422, Route 45/1',
            'latitude' => 7.9150,
            'longitude' => 80.7950,
            'is_featured' => true,
            'featured_priority' => 10,
            'is_active' => true,
            'views_count' => 1240,
        ]);

        // Destination 2: Bakamuna River Walk & Eco Park (Has SI translation)
        $d2 = Destination::create([
            'title_en' => 'Bakamuna River Walk & Eco Park',
            'title_si' => 'බකමුණ ගංඟා මිටියාවත සහ පරිසර උද්‍යානය',
            'slug' => 'bakamuna-river-walk-eco-park',
            'short_description_en' => 'Tranquil riverside park in Bakamuna town center with giant kumbuk trees, shaded benches, and gentle water streams.',
            'short_description_si' => 'බකමුණ නගරය ආසන්නයේ දැවැන්ත කුඹුක් ගස් සෙවණේ පිහිටි සන්සුන් පරිසර උද්‍යානය සහ ගංඟා ඉවුර.',
            'description_en' => 'Located just moments from the Bakamuna town bus terminal, the Bakamuna River Walk provides an idyllic retreat beneath towering Kumbuk trees that line the Amban River. The park features paved pedestrian walkways, stone picnic tables, and safe shallow natural river pools where travelers often take a refreshing dip before continuing their journeys. It is a favored stop for family picnics and nature walks.',
            'description_si' => 'බකමුණ නගරයේ සිට ඉතා ආසන්නව පිහිටි මෙම සොඳුරු ඉසව්ව දැවැන්ත කුඹුක් සහ මී ගස් වලින් වටවී ඇත. අඹන් ගඟ අසබඩ පිහිටි මෙම පරිසර උද්‍යානයෙහි ඇවිදින මංතීරු, ගිමන් හැරීමේ ස්ථාන සහ ආරක්ෂිත ස්වභාවික දිය තටාක පිහිටා ඇත. පවුලේ සැමට විවේකීව කාලය ගත කිරීමටත්, ගමනේ විඩාව නිවාගැනීමටත් ඉතා කදිම තෝතැන්නකි.',
            'category' => 'Nature & Wildlife',
            'category_si' => 'සොබාදහම සහ වනජීවී',
            'route_zone' => 'Zone A - Bakamuna Route',
            'featured_image' => 'images/destinations/bakamuna_park.jpg',
            'gallery_images' => ['images/destinations/bakamuna1.jpg', 'images/destinations/bakamuna2.jpg'],
            'distance_km' => 1.0,
            'travel_time_minutes' => 5,
            'stay_duration_minutes' => 60,
            'safety_alerts_en' => '⚠️ Supervise children near river banks. Flash floods may occur after heavy mountain rains.',
            'safety_alerts_si' => '⚠️ ගං ඉවුර අසල දරුවන් පිළිබඳ සැලකිලිමත් වන්න. ඉහළ කඳුකරයට අධික වැසි පවතින විට ගඟේ ජල මට්ටම ක්ෂණිකව ඉහළ යා හැක.',
            'packing_list_en' => '🎒 Towel, extra clothes for bathing, waterproof bag, snacks.',
            'packing_list_si' => '🎒 තුවායක්, අමතර ඇඳුම්, ජලරෝධක බෑග් සහ කෙටි ආහාර.',
            'dress_code_en' => '👗 Casual outdoor attire.',
            'dress_code_si' => '👗 සුවපහසු එළිමහන් ඇඳුම්.',
            'nearest_bus_stop' => 'Bakamuna Central Bus Stand',
            'bus_route_numbers' => 'All Bakamuna Routes',
            'latitude' => 7.9866,
            'longitude' => 80.8144,
            'is_featured' => true,
            'featured_priority' => 9,
            'is_active' => true,
            'views_count' => 980,
        ]);

        // Destination 3: Angamedilla National Park & Sluice Gate (Has SI translation)
        $d3 = Destination::create([
            'title_en' => 'Angamedilla National Park',
            'title_si' => 'අංගමැඩිල්ල ජාතික වනෝද්‍යානය',
            'slug' => 'angamedilla-national-park',
            'short_description_en' => 'Pristine wildlife sanctuary protecting the catchment of Parakrama Samudra, home to wild elephant herds, sambar deer, and rare birds.',
            'short_description_si' => 'පරාක්‍රම සමුද්‍රයේ පෝෂක ප්‍රදේශය සුරකින, වන අලි, ගෝනුන් සහ දුර්ලභ පක්ෂීන්ගේ නිජබිම වූ වන අරණ.',
            'description_en' => 'Declared as a national park in 2006, Angamedilla encompasses 7,529 hectares of dry evergreen and mixed tropical forest. It serves as an ecological corridor connecting Wasgamuwa and Minneriya national parks. The ancient Angamedilla sluice gate redirects water along ancient stone stone-masonry canals towards the majestic Parakrama Samudra reservoir.',
            'description_si' => '2006 වසරේදී ජාතික වනෝද්‍යානයක් ලෙස නම් කරන ලද අංගමැඩිල්ල, වස්ගමුව සහ මින්නේරිය ජාතික වනෝද්‍යාන එකිනෙක යා කරන අලි සංක්‍රමණික මාවතකි. පරාක්‍රම සමුද්‍රයට ජලය සපයන පුරාණ අංගමැඩිල්ල වාන් දොරටුව මෙහි පිහිටා ඇති අතර, වන අලි රංචු, ගෝනුන්, මුව සහ නේකවිධ පක්ෂී විශේෂ මෙහිදී සමීපව දැකගත හැක.',
            'category' => 'Nature & Wildlife',
            'category_si' => 'සොබාදහම සහ වනජීවී',
            'route_zone' => 'Zone C - Wildlife & Angamedilla',
            'featured_image' => 'images/destinations/angamedilla.jpg',
            'gallery_images' => ['images/destinations/anga1.jpg', 'images/destinations/anga2.jpg'],
            'distance_km' => 14.5,
            'travel_time_minutes' => 30,
            'stay_duration_minutes' => 120,
            'safety_alerts_en' => '⚠️ Wild elephant crossings are frequent, especially after 4:00 PM. Do not walk alone on forest edges.',
            'safety_alerts_si' => '⚠️ සවස 4:00න් පසු වන අලි සැරිසැරීම බහුල බැවින් වන මායිමේ තනිව ඇවිදීමෙන් වළකින්න.',
            'packing_list_en' => '🎒 Binoculars, telephoto camera lens, earth-tone clothing, hydration pack.',
            'packing_list_si' => '🎒 දුරදක්නය, කැමරාව, පරිසරයට ගැලපෙන ඇඳුම්, පානීය ජලය.',
            'dress_code_en' => '👗 Safari / field attire in khaki, brown, or olive green colors.',
            'dress_code_si' => '👗 ඛාකි හෝ තද කොළ පැහැති සෆාරි ඇඳුම්.',
            'nearest_bus_stop' => 'Angamedilla Junction',
            'bus_route_numbers' => 'Route 422 (Bakamuna - Polonnaruwa)',
            'latitude' => 7.9015,
            'longitude' => 80.9022,
            'is_featured' => true,
            'featured_priority' => 8,
            'is_active' => true,
            'views_count' => 1520,
        ]);

        // Destination 4: Moragahakanda Reservoir Dam Viewpoint (HAS ONLY ENGLISH - Demonstrates Sinhala fallback warning!)
        $d4 = Destination::create([
            'title_en' => 'Moragahakanda Reservoir Dam Viewpoint',
            'title_si' => null, // Deliberately null to test fallback
            'slug' => 'moragahakanda-reservoir-dam-viewpoint',
            'short_description_en' => 'Spectacular panoramic viewpoint overlooking Sri Lanka’s newest massive reservoir, surrounded by Knuckles mountain ridges.',
            'short_description_si' => null, // Deliberately null to test fallback
            'description_en' => 'The Moragahakanda Dam Viewpoint sits on an elevated terrace providing a breathtaking 360-degree vista across the vast reservoir expanse and the southern ridges of the Knuckles mountain range. The sheer scale of the engineering feat and the shimmering waters against dramatic mountain backdrops make this one of the most photographed vantage points in the Matale-Elahera corridor. Sunsets here are especially captivating with twilight hues reflecting on the water surface.',
            'description_si' => null, // Deliberately null to test fallback
            'category' => 'Viewpoints',
            'category_si' => null,
            'route_zone' => 'Zone D - Moragahakanda Viewpoints',
            'featured_image' => 'images/destinations/moragahakanda.jpg',
            'gallery_images' => ['images/destinations/mora1.jpg', 'images/destinations/mora2.jpg'],
            'distance_km' => 18.0,
            'travel_time_minutes' => 35,
            'stay_duration_minutes' => 60,
            'safety_alerts_en' => '⚠️ Windy plateau. Maintain safe distance from protective barriers. Flying drones requires prior civil aviation and defense permits.',
            'safety_alerts_si' => null,
            'packing_list_en' => '🎒 Windbreaker jacket, camera tripod, sunglasses, wide-angle lens.',
            'packing_list_si' => null,
            'dress_code_en' => '👗 Casual warm layer recommended for brisk evening winds.',
            'dress_code_si' => null,
            'nearest_bus_stop' => 'Moragahakanda Viewpoint Stop',
            'bus_route_numbers' => 'Route 45/1 (Naula - Elahera)',
            'latitude' => 7.7050,
            'longitude' => 80.7810,
            'is_featured' => true,
            'featured_priority' => 7,
            'is_active' => true,
            'views_count' => 840,
        ]);

        // Destination 5: Heeratiya Oya Forest Cascades (Has SI translation)
        $d5 = Destination::create([
            'title_en' => 'Heeratiya Oya Cascades',
            'title_si' => 'හීරැටිය ඔය ස්වභාවික දිය ඇලි පෙළ',
            'slug' => 'heeratiya-oya-cascades',
            'short_description_en' => 'Secret multi-tiered forest cascades nestled in wilderness with crystal-clear bathing pools and shaded canopy.',
            'short_description_si' => 'ඝන වනාන්තරය මැදින් ගලා බසිනා ස්ඵටිකයක් බඳු පිරිසිදු දිය දහරාවන්ගෙන් සමන්විත සොඳුරු දියඇලි පෙළ.',
            'description_en' => 'Hidden among the foothills connecting Elahera with the eastern slopes of the mountain ranges, Heeratiya Oya features a succession of natural rock pools and gentle cascading falls. The water is pristine, filtered naturally through mountain forests. Visitors will find natural rock slides, calm jacuzzi-like pools, and dense tropical foliage providing all-day shade.',
            'description_si' => 'ඇලහැර ප්‍රදේශයේ කඳු පාමුල ඝන වනපෙත මැද සැඟවී ඇති හීරැටිය ඔය, ස්වභාවික පාෂාණ තටාක සහ පියගැට බඳු දියඇලි කිහිපයකින් සමන්විත මනස්කාන්ත පරිසරයකි. ගලා බසිනා පිරිසිදු සිහිල් ජලයේ බැස දිය නෑමටත්, වනපෙතේ නිහඬතාවය විඳගැනීමටත් පරිසර ලෝලීන් මෙහි නිතර පැමිණෙති.',
            'category' => 'Waterfalls',
            'category_si' => 'දියඇලි',
            'route_zone' => 'Zone B - Elahera Anicut',
            'featured_image' => 'images/destinations/heeratiya.jpg',
            'gallery_images' => ['images/destinations/heera1.jpg'],
            'distance_km' => 8.5,
            'travel_time_minutes' => 20,
            'stay_duration_minutes' => 90,
            'safety_alerts_en' => '⚠️ Mossy rocks can be extremely slippery. Keep clear of rapids during rain.',
            'safety_alerts_si' => '⚠️ පාසි බැඳුණු ගල් ලිස්සන බැවින් ප්‍රවේශම් වන්න. වර්ෂා සහිත දිනවල දියට බැසීමෙන් වළකින්න.',
            'packing_list_en' => '🎒 Non-slip trekking sandals, quick-dry clothes, waterproof phone pouch, leech socks.',
            'packing_list_si' => '🎒 ලිස්සන්නේ නැති පාවහන්, ඉක්මනින් වියළෙන ඇඳුම්, කූඩැල්ලන්ගෙන් ආරක්ෂා වීමේ ආලේපන.',
            'dress_code_en' => '👗 Hiking swimwear with t-shirt.',
            'dress_code_si' => '👗 දියනෑම සඳහා සුදුසු සුවපහසු ඇඳුම්.',
            'nearest_bus_stop' => 'Heeratiya Junction',
            'bus_route_numbers' => 'Route 422',
            'latitude' => 7.8820,
            'longitude' => 80.8250,
            'is_featured' => true,
            'featured_priority' => 6,
            'is_active' => true,
            'views_count' => 690,
        ]);

        // Destination 6: Bowatenna - Elahera Headworks Canal (Has SI translation)
        $d6 = Destination::create([
            'title_en' => 'Bowatenna - Elahera Water Feeder Channel',
            'title_si' => 'බෝවතැන්න - ඇලහැර ජල පෝෂක ඇළ මාර්ගය',
            'slug' => 'bowatenna-elahera-feeder-channel',
            'short_description_en' => 'A marvelous modern and historical junction where mountain runoff is channeled precisely into the ancient dry-zone agricultural plains.',
            'short_description_si' => 'මධ්‍යම කඳුකරයේ ජලය වියළි කලාපයේ සාරවත් කෙත්වතු වෙත හරවා යවන මනරම් ජල පාලක පෙදෙස.',
            'description_en' => 'This strategic waterway represents the harmonious convergence of modern Mahaweli development with ancient Sinhala engineering wisdom. Water released from the Bowatenna reservoir travels through mountain tunnels and opens into broad canals lined with coconut groves and paddy fields, creating a tranquil rural landscape perfect for cycling and leisurely afternoon strolls.',
            'description_si' => 'මෙම සුවිශේෂී ජල මාර්ගය පුරාණ හෙළ වාරි තාක්ෂණය හා නූතන මහවැලි යෝජනා ක්‍රමයේ සුසංයෝගය මනාව පෙන්නුම් කරයි. උමං මාර්ග ඔස්සේ ගලා එන ජලය සරුසාර කෙත්වතු හා ගම්බද පරිසරය මැදින් ඉදිරියට ඇදෙන්නේ සිත් ඇදගන්නා සුළු දසුනක් මවමිනි. බයිසිකල් සවාරි සහ සවස ඇවිදීම සඳහා ඉතා ප්‍රශස්ත ස්ථානයකි.',
            'category' => 'Canals & Lakes',
            'category_si' => 'ඇළ මාර්ග සහ ජලාශ',
            'route_zone' => 'Zone B - Elahera Anicut',
            'featured_image' => 'images/destinations/canal.jpg',
            'gallery_images' => ['images/destinations/canal1.jpg'],
            'distance_km' => 11.0,
            'travel_time_minutes' => 22,
            'stay_duration_minutes' => 45,
            'safety_alerts_en' => '⚠️ Canal embankments can be narrow; watch oncoming motorbikes and farming tractors.',
            'safety_alerts_si' => '⚠️ ඇළ ඉවුරු පටු බැවින් ගමන් ගන්නා යතුරුපැදි සහ ට්‍රැක්ටර් පිළිබඳ සැලකිලිමත් වන්න.',
            'packing_list_en' => '🎒 Camera, sunglasses, water bottle.',
            'packing_list_si' => '🎒 කැමරාව, හිරු ආවරණ කණ්ණාඩි, වතුර බෝතලයක්.',
            'dress_code_en' => '👗 Light summer clothes.',
            'dress_code_si' => '👗 සැහැල්ලු කපු ඇඳුම්.',
            'nearest_bus_stop' => 'Elahera Bridge Halt',
            'bus_route_numbers' => 'Route 422, Route 45/1',
            'latitude' => 7.8450,
            'longitude' => 80.7920,
            'is_featured' => true,
            'featured_priority' => 5,
            'is_active' => true,
            'views_count' => 580,
        ]);

        // 5. Destination Reviews
        DestinationReview::create([
            'destination_id' => $d1->id,
            'user_id' => $user1->id,
            'rating' => 5,
            'comment' => 'Astonishing piece of ancient engineering! Walking along King Mahasen’s stone works filled me with awe. A must-visit place in Elahera!',
            'is_approved' => true,
        ]);

        DestinationReview::create([
            'destination_id' => $d2->id,
            'user_id' => $user2->id,
            'rating' => 5,
            'comment' => 'The Kumbuk trees at Bakamuna river walk are massive. Calm river water, safe for dipping feet after a long journey.',
            'is_approved' => true,
        ]);

        // 6. Bus Routes & Chronological Timetable (From 05:30 AM to 06:45 PM)
        $r1 = BusRoute::create([
            'route_number' => '422',
            'origin' => 'Matale',
            'destination' => 'Bakamuna',
            'service_type' => 'Normal',
            'origin_departure_time' => '05:30 AM',
            'via_cities' => 'Naula, Elahera Anicut, Bakamuna',
            'remarks' => 'Daily regular passenger service. Scheduled departure from Matale stand.',
            'is_active' => true,
        ]);

        BusRouteHalt::create([
            'bus_route_id' => $r1->id,
            'stop_name' => 'Matale Stand',
            'arrival_time' => '05:25 AM',
            'departure_time' => '05:30 AM',
            'stop_order' => 1,
            'remarks' => 'Origin departure',
        ]);
        BusRouteHalt::create([
            'bus_route_id' => $r1->id,
            'stop_name' => 'Naula Junction',
            'arrival_time' => '06:15 AM',
            'departure_time' => '06:20 AM',
            'stop_order' => 2,
            'remarks' => 'Transfer point for Dambulla buses',
        ]);
        BusRouteHalt::create([
            'bus_route_id' => $r1->id,
            'stop_name' => 'Elahera Anicut Junction',
            'arrival_time' => '06:50 AM',
            'departure_time' => '06:55 AM',
            'destination_id' => $d1->id,
            'nearest_spot_name' => 'Elahera Ancient Anicut (500m walk)',
            'stop_order' => 3,
            'remarks' => 'Step down here for Anicut & Yodha Ela',
        ]);
        BusRouteHalt::create([
            'bus_route_id' => $r1->id,
            'stop_name' => 'Bakamuna Town Terminal',
            'arrival_time' => '07:15 AM',
            'departure_time' => '07:20 AM',
            'destination_id' => $d2->id,
            'nearest_spot_name' => 'Bakamuna River Eco Park',
            'stop_order' => 4,
            'remarks' => 'Central Hub Terminal',
        ]);

        $r2 = BusRoute::create([
            'route_number' => '45/1',
            'origin' => 'Bakamuna',
            'destination' => 'Polonnaruwa',
            'service_type' => 'Semi-Luxury',
            'origin_departure_time' => '07:30 AM',
            'via_cities' => 'Kottapitiya, Angamedilla, Jayanthipura',
            'remarks' => 'Connects Elahera division directly with Polonnaruwa ancient city.',
            'is_active' => true,
        ]);

        BusRouteHalt::create([
            'bus_route_id' => $r2->id,
            'stop_name' => 'Bakamuna Town Terminal',
            'arrival_time' => '07:25 AM',
            'departure_time' => '07:30 AM',
            'destination_id' => $d2->id,
            'stop_order' => 1,
        ]);
        BusRouteHalt::create([
            'bus_route_id' => $r2->id,
            'stop_name' => 'Angamedilla Junction',
            'arrival_time' => '08:05 AM',
            'departure_time' => '08:10 AM',
            'destination_id' => $d3->id,
            'nearest_spot_name' => 'Angamedilla National Park Entrance',
            'stop_order' => 2,
        ]);
        BusRouteHalt::create([
            'bus_route_id' => $r2->id,
            'stop_name' => 'Polonnaruwa Main Bus Stand',
            'arrival_time' => '08:45 AM',
            'departure_time' => '08:50 AM',
            'stop_order' => 3,
        ]);

        $r3 = BusRoute::create([
            'route_number' => '422/A',
            'origin' => 'Dambulla',
            'destination' => 'Bakamuna',
            'service_type' => 'Normal',
            'origin_departure_time' => '09:15 AM',
            'via_cities' => 'Naula, Elahera, Bakamuna',
            'remarks' => 'Direct connection from Dambulla economic center.',
            'is_active' => true,
        ]);

        BusRouteHalt::create([
            'bus_route_id' => $r3->id,
            'stop_name' => 'Dambulla Central Bus Stand',
            'arrival_time' => '09:05 AM',
            'departure_time' => '09:15 AM',
            'stop_order' => 1,
        ]);
        BusRouteHalt::create([
            'bus_route_id' => $r3->id,
            'stop_name' => 'Elahera Anicut Junction',
            'arrival_time' => '10:10 AM',
            'departure_time' => '10:15 AM',
            'destination_id' => $d1->id,
            'nearest_spot_name' => 'Elahera Ancient Anicut',
            'stop_order' => 2,
        ]);
        BusRouteHalt::create([
            'bus_route_id' => $r3->id,
            'stop_name' => 'Bakamuna Bus Stand',
            'arrival_time' => '10:35 AM',
            'departure_time' => '10:40 AM',
            'destination_id' => $d2->id,
            'stop_order' => 3,
        ]);

        $r4 = BusRoute::create([
            'route_number' => '422',
            'origin' => 'Matale',
            'destination' => 'Bakamuna',
            'service_type' => 'Normal',
            'origin_departure_time' => '11:45 AM',
            'via_cities' => 'Naula, Elahera, Bakamuna',
            'remarks' => 'Midday service.',
            'is_active' => true,
        ]);

        BusRouteHalt::create([
            'bus_route_id' => $r4->id,
            'stop_name' => 'Matale Stand',
            'arrival_time' => '11:35 AM',
            'departure_time' => '11:45 AM',
            'stop_order' => 1,
        ]);
        BusRouteHalt::create([
            'bus_route_id' => $r4->id,
            'stop_name' => 'Elahera Anicut Junction',
            'arrival_time' => '01:05 PM',
            'departure_time' => '01:10 PM',
            'destination_id' => $d1->id,
            'stop_order' => 2,
        ]);
        BusRouteHalt::create([
            'bus_route_id' => $r4->id,
            'stop_name' => 'Bakamuna Terminal',
            'arrival_time' => '01:30 PM',
            'departure_time' => '01:35 PM',
            'destination_id' => $d2->id,
            'stop_order' => 3,
        ]);

        $r5 = BusRoute::create([
            'route_number' => '422/EXP',
            'origin' => 'Bakamuna',
            'destination' => 'Matale',
            'service_type' => 'Express',
            'origin_departure_time' => '02:30 PM',
            'via_cities' => 'Elahera, Naula, Matale',
            'remarks' => 'Fast return connection towards Matale and Kandy.',
            'is_active' => true,
        ]);

        BusRouteHalt::create([
            'bus_route_id' => $r5->id,
            'stop_name' => 'Bakamuna Terminal',
            'arrival_time' => '02:20 PM',
            'departure_time' => '02:30 PM',
            'stop_order' => 1,
        ]);
        BusRouteHalt::create([
            'bus_route_id' => $r5->id,
            'stop_name' => 'Elahera Junction',
            'arrival_time' => '02:45 PM',
            'departure_time' => '02:48 PM',
            'destination_id' => $d1->id,
            'stop_order' => 2,
        ]);
        BusRouteHalt::create([
            'bus_route_id' => $r5->id,
            'stop_name' => 'Matale Stand',
            'arrival_time' => '03:55 PM',
            'departure_time' => '04:00 PM',
            'stop_order' => 3,
        ]);

        $r6 = BusRoute::create([
            'route_number' => '422',
            'origin' => 'Matale',
            'destination' => 'Bakamuna',
            'service_type' => 'Normal',
            'origin_departure_time' => '04:15 PM',
            'via_cities' => 'Naula, Elahera, Bakamuna',
            'remarks' => 'Evening commute route.',
            'is_active' => true,
        ]);

        $r7 = BusRoute::create([
            'route_number' => '422/Night',
            'origin' => 'Bakamuna',
            'destination' => 'Naula / Matale',
            'service_type' => 'Normal',
            'origin_departure_time' => '06:45 PM',
            'via_cities' => 'Elahera, Naula',
            'remarks' => 'Last evening bus departing Bakamuna hub.',
            'is_active' => true,
        ]);

        BusRouteHalt::create([
            'bus_route_id' => $r7->id,
            'stop_name' => 'Bakamuna Terminal',
            'arrival_time' => '06:35 PM',
            'departure_time' => '06:45 PM',
            'destination_id' => $d2->id,
            'stop_order' => 1,
        ]);
        BusRouteHalt::create([
            'bus_route_id' => $r7->id,
            'stop_name' => 'Elahera Junction',
            'arrival_time' => '07:05 PM',
            'departure_time' => '07:10 PM',
            'destination_id' => $d1->id,
            'stop_order' => 2,
        ]);

        // 7. Blog Categories & Blog Posts
        $cat1 = BlogCategory::create(['name_en' => 'Travel Guides & Itineraries', 'name_si' => 'සංචාරක මාර්ගෝපදේශ', 'slug' => 'travel-guides']);
        $cat2 = BlogCategory::create(['name_en' => 'Historical Marvels', 'name_si' => 'ඓතිහාසික විස්මිත', 'slug' => 'historical-marvels']);
        $cat3 = BlogCategory::create(['name_en' => 'Wildlife & Nature', 'name_si' => 'වනජීවී හා සොබාදහම', 'slug' => 'wildlife-nature']);

        // Approved Post 1
        BlogPost::create([
            'user_id' => $user1->id,
            'category_id' => $cat2->id,
            'title' => 'Walking the Footsteps of King Mahasen: The Legacy of Elahera Canal',
            'slug' => 'walking-the-footsteps-of-king-mahasen-elahera-canal',
            'content' => 'The Elahera irrigation canal is not just an ancient waterway; it is living testimony to the hydraulic engineering genius of ancient Sri Lanka. During our weekend cycling expedition along the canal bund from Elahera Anicut towards Kottapitiya, we were amazed by how steadily water flows across varying gradients. Giant trees provide natural shade, while local villagers welcome travelers with fresh king coconuts.',
            'featured_image' => 'images/blog/blog1.jpg',
            'is_approved' => true, // Approved by admin
            'views_count' => 312,
        ]);

        // Approved Post 2
        BlogPost::create([
            'user_id' => $user2->id,
            'category_id' => $cat3->id,
            'title' => 'Birdwatching at Dawn along Amban River & Bakamuna Canals',
            'slug' => 'birdwatching-at-dawn-along-amban-river-bakamuna',
            'content' => 'If you arrive at Bakamuna before sunrise, head straight to the river walkway near the old irrigation bridge. Between 6:00 AM and 7:30 AM, you can spot over 30 bird species including Stork-billed Kingfishers, White-bellied Sea Eagles soaring overhead, and colorful Little Green Bee-eaters darting over the water surface.',
            'featured_image' => 'images/blog/blog2.jpg',
            'is_approved' => true, // Approved by admin
            'views_count' => 185,
        ]);

        // Pending Post (requires admin approval)
        BlogPost::create([
            'user_id' => $user1->id,
            'category_id' => $cat1->id,
            'title' => 'A Weekend Solo Camping Experience near Heeratiya Forest Stream',
            'slug' => 'weekend-solo-camping-heeratiya-forest-stream',
            'content' => 'I spent a memorable night camping near the upper cascades of Heeratiya Oya. The sound of running mountain water and clear starlit skies made it one of my favorite escapes in the North Central province.',
            'featured_image' => 'images/blog/blog3.jpg',
            'is_approved' => false, // Pending moderation!
            'views_count' => 12,
        ]);

        // 8. Forum Categories, Topics, and Replies (Direct publication - no approval needed)
        $fc1 = ForumCategory::create([
            'name_en' => 'Destinations & Sightseeing',
            'name_si' => 'ගමනාන්ත හා නැරඹුම් ස්ථාන',
            'slug' => 'destinations-sightseeing',
            'icon' => 'map-pin',
            'description' => 'Discussions, tips, and queries about visiting tourist spots around Elahera.',
        ]);

        $fc2 = ForumCategory::create([
            'name_en' => 'Public Transport & Buses',
            'name_si' => 'පොදු ප්‍රවාහනය හා බස් රථ',
            'slug' => 'public-transport-buses',
            'icon' => 'truck',
            'description' => 'Timetable advice, connecting routes, and tips for bus commuters.',
        ]);

        $fc3 = ForumCategory::create([
            'name_en' => 'Trekking, Camping & Safety',
            'name_si' => 'පාගමන්, කඳවුරු බැඳීම සහ ආරක්ෂාව',
            'slug' => 'trekking-camping-safety',
            'icon' => 'shield-check',
            'description' => 'Safety alerts, trail advice, weather updates, and gear recommendations.',
        ]);

        $ft1 = ForumTopic::create([
            'forum_category_id' => $fc2->id,
            'user_id' => $user1->id,
            'title' => 'What is the best way to travel from Matale to Bakamuna by morning bus?',
            'slug' => 'best-way-to-travel-from-matale-to-bakamuna-morning-bus',
            'content' => 'Hello everyone! I am planning a day trip this Saturday. What time is the first direct bus from Matale, and does it stop directly in front of the Elahera Anicut road?',
            'views_count' => 74,
        ]);

        ForumReply::create([
            'forum_topic_id' => $ft1->id,
            'user_id' => $user2->id,
            'content' => 'The first bus Route 422 leaves Matale Stand at 05:30 AM sharp. Ask the conductor for Elahera Anicut Junction. You will reach there by around 06:50 AM, perfect for morning photography!',
        ]);

        // 9. News & Events (Community submission with approval)
        NewsEvent::create([
            'user_id' => $admin->id,
            'title' => 'Annual Elahera Irrigation Canal Maintenance and Water Release Schedule',
            'slug' => 'annual-elahera-canal-maintenance-water-release-schedule',
            'type' => 'news',
            'event_date' => now()->addDays(5),
            'location' => 'Elahera Headworks & Yodha Ela',
            'content' => 'The Mahaweli Authority and Department of Irrigation have announced the seasonal water release schedule for the Yodha Ela canal to support Yala cultivation. Visitors are advised to exercise extra caution around main canal sluices during high flow rates.',
            'featured_image' => 'images/news/news1.jpg',
            'is_approved' => true,
            'views_count' => 450,
        ]);

        NewsEvent::create([
            'user_id' => $user1->id,
            'title' => 'Elahera Heritage Eco-Bicycle Tour 2026',
            'slug' => 'elahera-heritage-eco-bicycle-tour-2026',
            'type' => 'event',
            'event_date' => now()->addDays(20),
            'location' => 'Starting from Bakamuna Town Eco Park',
            'content' => 'Join fellow cyclists for a scenic 25-kilometer ride along the shade-covered canal banks of Elahera. Registration is free for all registered community members.',
            'featured_image' => 'images/news/event1.jpg',
            'is_approved' => true,
            'views_count' => 280,
        ]);

        // 10. Emergency Contacts
        EmergencyContact::create([
            'institution_name_en' => 'Bakamuna Base Hospital',
            'institution_name_si' => 'බකමුණ මූලික රෝහල',
            'category' => 'Hospital',
            'phone_primary' => '+94 66 225 6261',
            'phone_secondary' => '1990 (Suwa Seriya Ambulance)',
            'location' => 'Hospital Road, Bakamuna',
            'sort_order' => 1,
        ]);

        EmergencyContact::create([
            'institution_name_en' => 'Elahera Police Station',
            'institution_name_si' => 'ඇලහැර පොලිස් ස්ථානය',
            'category' => 'Police',
            'phone_primary' => '+94 66 225 6222',
            'phone_secondary' => '119 (Police Emergency)',
            'location' => 'Main Street, Elahera',
            'sort_order' => 2,
        ]);

        EmergencyContact::create([
            'institution_name_en' => 'Bakamuna Wildlife Range Office (DWC)',
            'institution_name_si' => 'වනජීවී අඩවි කාර්යාලය - බකමුණ',
            'category' => 'Wildlife & Forest',
            'phone_primary' => '+94 66 225 6344',
            'phone_secondary' => '1992 (DWC Hotline)',
            'location' => 'Angamedilla Road, Bakamuna',
            'sort_order' => 3,
        ]);

        EmergencyContact::create([
            'institution_name_en' => 'Disaster Management Centre - Polonnaruwa / Elahera',
            'institution_name_si' => 'ආපදා කළමනාකරණ මධ්‍යස්ථානය',
            'category' => 'Disaster Management',
            'phone_primary' => '+94 27 222 2588',
            'phone_secondary' => '117 (DMC Emergency Hotline)',
            'location' => 'District Secretariat Complex',
            'sort_order' => 4,
        ]);
    }
}
