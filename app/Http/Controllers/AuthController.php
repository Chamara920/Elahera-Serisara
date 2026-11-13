<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthController
 *
 * Handles Community Member registration, login, logout, and profile updates.
 */
class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Process user login.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // If admin, they can also go to admin or stay
            return redirect()->intended(route('home'))->with('success', __('Welcome back, :name!', ['name' => Auth::user()->name]));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form.
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        $securityQuestions = User::defaultSecurityQuestions();

        return view('auth.register', compact('securityQuestions'));
    }

    /**
     * Handle registration for new community members.
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'bio' => 'nullable|string|max:1000',
            'security_question' => 'required|string|max:255',
            'security_answer' => 'required|string|min:2|max:255',
            'security_question_2' => 'required|string|max:255|different:security_question',
            'security_answer_2' => 'required|string|min:2|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'community_member',
            'bio' => $validated['bio'] ?? null,
            'security_question' => $validated['security_question'],
            'security_answer' => Hash::make(mb_strtolower(trim($validated['security_answer']))),
            'security_question_2' => $validated['security_question_2'],
            'security_answer_2' => Hash::make(mb_strtolower(trim($validated['security_answer_2']))),
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Registration successful! Welcome to the Elahera Serisara community.');
    }

    /**
     * Show forgot password page (Security Question based recovery).
     */
    public function showForgotPassword()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        $email = session('reset_user_email');
        $question = null;
        $question2 = null;

        if ($email) {
            $user = User::where('email', $email)->first();
            if ($user && ! empty($user->security_question)) {
                $question = $user->security_question;
                $question2 = $user->security_question_2;
            }
        }

        return view('auth.forgot-password', compact('email', 'question', 'question2'));
    }

    /**
     * Look up user account by email to fetch security question.
     */
    public function verifyForgotPasswordAccount(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'No account was found with this email address.',
            ])->onlyInput('email');
        }

        if (empty($user->security_question) || empty($user->security_answer)) {
            return back()->withErrors([
                'email' => 'No security questions have been set for this account. Please contact system administrators.',
            ])->onlyInput('email');
        }

        return redirect()->route('password.forgot')
            ->with('reset_user_email', $user->email)
            ->with('success', 'Account found! Please answer your security questions below to reset your password.');
    }

    /**
     * Verify security answer and reset user password.
     */
    public function resetPasswordWithSecurityAnswer(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'security_answer' => 'required|string',
            'security_answer_2' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'User account not found.',
            ]);
        }

        if (! empty($user->security_question_2)) {
            if (empty($request->security_answer_2) || ! $user->verifyBothSecurityAnswers($request->security_answer, $request->security_answer_2)) {
                return back()->withErrors([
                    'security_answer' => __('messages.both_answers_incorrect'),
                ])->with('reset_user_email', $user->email);
            }
        } else {
            if (! $user->verifySecurityAnswer($request->security_answer)) {
                return back()->withErrors([
                    'security_answer' => 'The security answer you provided is incorrect. Please try again.',
                ])->with('reset_user_email', $user->email);
            }
        }

        // Answer is correct, update password!
        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget('reset_user_email');

        return redirect()->route('login')->with('success', 'Your password has been successfully reset! You can now log in with your new password.');
    }

    /**
     * User logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been signed out.');
    }

    /**
     * User profile view and update.
     */
    public function profile()
    {
        $user = Auth::user();
        $securityQuestions = User::defaultSecurityQuestions();

        return view('user.profile', compact('user', 'securityQuestions'));
    }

    /**
     * Update user profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'password' => 'nullable|string|min:8|confirmed',
            'security_question' => 'nullable|string|max:255',
            'security_answer' => 'nullable|string|min:2|max:255',
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'] ?? null;
        $user->bio = $validated['bio'] ?? null;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if (! empty($validated['security_question']) && ! empty($validated['security_answer'])) {
            $user->security_question = $validated['security_question'];
            $user->security_answer = Hash::make(mb_strtolower(trim($validated['security_answer'])));
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }
}
