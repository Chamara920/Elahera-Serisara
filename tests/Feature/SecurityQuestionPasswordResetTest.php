<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\ForumCategory;
use App\Models\ForumTopic;
use App\Models\NewsCategory;
use App\Models\NewsEvent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityQuestionPasswordResetTest extends TestCase
{
    /**
     * Test community member registration with two security questions and without phone number.
     */
    public function test_user_can_register_with_security_question(): void
    {
        $response = $this->post('/register', [
            'name' => 'Ruwan Bandara',
            'email' => 'ruwan@example.com',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
            'security_question' => 'What is your primary school name?',
            'security_answer' => 'Ananda College',
            'security_question_2' => 'In what city or town were you born?',
            'security_answer_2' => 'Bakamuna',
        ]);

        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', [
            'email' => 'ruwan@example.com',
            'security_question' => 'What is your primary school name?',
            'security_question_2' => 'In what city or town were you born?',
        ]);

        $user = User::where('email', 'ruwan@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->verifySecurityAnswer('Ananda College'));
        $this->assertTrue($user->verifySecurityAnswer('ananda college')); // case-insensitive check
        $this->assertTrue($user->verifySecurityAnswer2('Bakamuna'));
        $this->assertTrue($user->verifyBothSecurityAnswers('Ananda College', 'Bakamuna'));
    }

    /**
     * Test community registration fails if same question is chosen twice.
     */
    public function test_user_cannot_register_with_identical_security_questions(): void
    {
        $response = $this->post('/register', [
            'name' => 'Duplicate Question User',
            'email' => 'duplicate@example.com',
            'password' => 'secret12345',
            'password_confirmation' => 'secret12345',
            'security_question' => 'What is your primary school name?',
            'security_answer' => 'Ananda College',
            'security_question_2' => 'What is your primary school name?',
            'security_answer_2' => 'Another College',
        ]);

        $response->assertSessionHasErrors(['security_question_2']);
    }

    /**
     * Test step 1 of forgot password: find account and retrieve question.
     */
    public function test_forgot_password_step_1_finds_account(): void
    {
        $user = User::create([
            'name' => 'Kasun Silva',
            'email' => 'kasun@example.com',
            'password' => Hash::make('oldpassword123'),
            'role' => 'community_member',
            'security_question' => 'What was the name of your first pet?',
            'security_answer' => Hash::make(mb_strtolower('Tommy')),
        ]);

        $response = $this->post(route('password.find'), [
            'email' => 'kasun@example.com',
        ]);

        $response->assertRedirect(route('password.forgot'));
        $response->assertSessionHas('reset_user_email', 'kasun@example.com');
    }

    /**
     * Test step 2 of forgot password: reset password with correct security answer.
     */
    public function test_forgot_password_resets_password_with_correct_answer(): void
    {
        $user = User::create([
            'name' => 'Saman Jayasuriya',
            'email' => 'saman@example.com',
            'password' => Hash::make('originalpassword'),
            'role' => 'community_member',
            'security_question' => 'In what city or town were you born?',
            'security_answer' => Hash::make(mb_strtolower('Bakamuna')),
        ]);

        $response = $this->post(route('password.reset.security'), [
            'email' => 'saman@example.com',
            'security_answer' => 'bakamuna', // case-insensitive
            'password' => 'newBrandNewPassword123',
            'password_confirmation' => 'newBrandNewPassword123',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('newBrandNewPassword123', $user->password));
    }

    /**
     * Test step 2 of forgot password fails when wrong answer is given.
     */
    public function test_forgot_password_fails_with_wrong_answer(): void
    {
        $user = User::create([
            'name' => 'Kavinda Fernando',
            'email' => 'kavinda@example.com',
            'password' => Hash::make('originalpassword'),
            'role' => 'community_member',
            'security_question' => 'What was the name of your first pet?',
            'security_answer' => Hash::make(mb_strtolower('Scooby')),
        ]);

        $response = $this->post(route('password.reset.security'), [
            'email' => 'kavinda@example.com',
            'security_answer' => 'WrongAnswer',
            'password' => 'newSecret987',
            'password_confirmation' => 'newSecret987',
        ]);

        $response->assertSessionHasErrors(['security_answer']);

        $user->refresh();
        $this->assertFalse(Hash::check('newSecret987', $user->password));
    }

    /**
     * Test forgot password resets successfully when both security answers are provided correctly.
     */
    public function test_forgot_password_resets_password_with_two_security_questions(): void
    {
        $user = User::create([
            'name' => 'Dilshan Perera',
            'email' => 'dilshan@example.com',
            'password' => Hash::make('originalpassword'),
            'role' => 'community_member',
            'security_question' => 'What is your primary school name?',
            'security_answer' => Hash::make(mb_strtolower('Mahanama College')),
            'security_question_2' => 'In what city or town were you born?',
            'security_answer_2' => Hash::make(mb_strtolower('Elahera')),
        ]);

        $response = $this->post(route('password.reset.security'), [
            'email' => 'dilshan@example.com',
            'security_answer' => 'mahanama college',
            'security_answer_2' => 'elahera',
            'password' => 'newSecretPass321',
            'password_confirmation' => 'newSecretPass321',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue(Hash::check('newSecretPass321', $user->password));
    }

    /**
     * Test multilingual publishing and category models for blog, news, and forum.
     */
    public function test_multilingual_posts_and_categories(): void
    {
        $user = User::first() ?? User::create([
            'name' => 'Author User',
            'email' => 'author@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Blog Category & Post
        $blogCat = BlogCategory::firstOrCreate([
            'slug' => 'wildlife-nature',
        ], [
            'name_en' => 'Wildlife & Nature',
            'name_si' => 'වනජීවී හා සොබාදහම',
        ]);

        $blogPost = BlogPost::create([
            'user_id' => $user->id,
            'category_id' => $blogCat->id,
            'language' => 'all',
            'title' => 'Bird Watching in Elahera Sanctuary',
            'title_si' => 'ඇලහැර අභයභූමියේ කුරුළු නැරඹීම',
            'slug' => 'bird-watching-elahera-sanctuary',
            'content' => 'An amazing morning birding walk along the canal...',
            'content_si' => 'ඇලහැර ඇළ මාර්ගය ඔස්සේ උදෑසන කුරුල්ලන් නැරඹීමේ චාරිකාව...',
            'is_approved' => true,
        ]);

        app()->setLocale('en');
        $this->assertEquals('Bird Watching in Elahera Sanctuary', $blogPost->getLocalizedTitle());
        app()->setLocale('si');
        $this->assertEquals('ඇලහැර අභයභූමියේ කුරුළු නැරඹීම', $blogPost->getLocalizedTitle());
        $this->assertEquals('වනජීවී හා සොබාදහම', $blogCat->getLocalizedName());

        // News Category & Event
        $newsCat = NewsCategory::firstOrCreate([
            'slug' => 'community-news',
        ], [
            'name_en' => 'Community News',
            'name_si' => 'ප්‍රජා පුවත්',
        ]);

        $news = NewsEvent::create([
            'user_id' => $user->id,
            'category_id' => $newsCat->id,
            'language' => 'all',
            'title' => 'Tree Planting Campaign 2026',
            'title_si' => '2026 රුක් රෝපණ වැඩසටහන',
            'slug' => 'tree-planting-campaign-2026',
            'type' => 'event',
            'content' => 'Join the tree planting event at Bakamuna...',
            'content_si' => 'බකමූණේ පැවැත්වෙන රුක් රෝපණ වැඩසටහනට එක්වන්න...',
            'is_approved' => true,
        ]);

        app()->setLocale('si');
        $this->assertEquals('2026 රුක් රෝපණ වැඩසටහන', $news->getLocalizedTitle());
        $this->assertEquals('ප්‍රජා පුවත්', $news->category->getLocalizedName());

        // Forum Category & Topic
        $forumCat = ForumCategory::firstOrCreate([
            'slug' => 'travel-tips',
        ], [
            'name_en' => 'Travel Tips',
            'name_si' => 'සංචාරක උපදෙස්',
        ]);

        $forum = ForumTopic::create([
            'user_id' => $user->id,
            'forum_category_id' => $forumCat->id,
            'language' => 'all',
            'title' => 'Best time to visit Heeratiya Oya?',
            'title_si' => 'හීරටිය ඔය නැරඹීමට හොඳම කාලය කුමක්ද?',
            'slug' => 'best-time-heeratiya-oya',
            'content' => 'Is it accessible during monsoon?',
            'content_si' => 'වර්ෂා කාලයේදී එහි යා හැකිද?',
        ]);

        app()->setLocale('si');
        $this->assertEquals('හීරටිය ඔය නැරඹීමට හොඳම කාලය කුමක්ද?', $forum->getLocalizedTitle());
        $this->assertEquals('සංචාරක උපදෙස්', $forum->category->getLocalizedName());

        app()->setLocale('en');
    }

    /**
     * Test header and footer enhancements (logo.jpg and centered copyright).
     */
    public function test_layout_has_new_logo_and_centered_copyright(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Logo (transparent PNG)
        $response->assertSee('images/logo.png');

        // Centered copyright without old bottom links
        $response->assertSee('footer-bottom text-center', false);
        $response->assertSee('Elahera Serisara (ඇලහැර සැරිසර)', false);
        $response->assertSee('All rights reserved.', false);
        $response->assertDontSee('>Admin Portal</a>', false);
    }
}
