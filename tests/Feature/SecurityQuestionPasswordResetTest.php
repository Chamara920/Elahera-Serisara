<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityQuestionPasswordResetTest extends TestCase
{
    /**
     * Test community member registration with security question.
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
        ]);

        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', [
            'email' => 'ruwan@example.com',
            'security_question' => 'What is your primary school name?',
        ]);

        $user = User::where('email', 'ruwan@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->verifySecurityAnswer('Ananda College'));
        $this->assertTrue($user->verifySecurityAnswer('ananda college')); // case-insensitive check
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
     * Test header and footer enhancements (logo.jpg and centered copyright).
     */
    public function test_layout_has_new_logo_and_centered_copyright(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        // Logo
        $response->assertSee('images/logo.jpg');

        // Centered copyright without old bottom links
        $response->assertSee('footer-bottom text-center', false);
        $response->assertSee('Elahera Serisara (ඇලහැර සැරිසර)', false);
        $response->assertSee('All rights reserved.', false);
        $response->assertDontSee('>Admin Portal</a>', false);
    }
}
