<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

/**
 * Class User
 *
 * Represents both registered community members and administrative users.
 * Supports Filament authentication control.
 */
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // 'admin' or 'community_member'
        'avatar',
        'phone',
        'bio',
        'security_question',
        'security_answer',
        'security_question_2',
        'security_answer_2',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'security_answer',
        'security_answer_2',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Filament Admin Panel access check.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * User's saved trips.
     */
    public function savedTrips(): HasMany
    {
        return $this->hasMany(SavedTrip::class);
    }

    /**
     * User's community blog posts.
     */
    public function blogPosts(): HasMany
    {
        return $this->hasMany(BlogPost::class);
    }

    /**
     * User's forum topics.
     */
    public function forumTopics(): HasMany
    {
        return $this->hasMany(ForumTopic::class);
    }

    /**
     * User's forum replies.
     */
    public function forumReplies(): HasMany
    {
        return $this->hasMany(ForumReply::class);
    }

    /**
     * User's reviews on destinations.
     */
    public function destinationReviews(): HasMany
    {
        return $this->hasMany(DestinationReview::class);
    }

    /**
     * Predefined security questions for community users.
     *
     * @return array<string, array{en: string, si: string}>
     */
    public static function defaultSecurityQuestions(): array
    {
        return [
            'What is your primary school name?' => [
                'en' => 'What is your primary school name?',
                'si' => 'ඔබේ ප්‍රාථමික පාසලේ නම කුමක්ද?',
            ],
            'What is your mother\'s maiden name?' => [
                'en' => 'What is your mother\'s maiden name?',
                'si' => 'ඔබේ මවගේ විවාහයට පෙර නම කුමක්ද?',
            ],
            'What was the name of your first pet?' => [
                'en' => 'What was the name of your first pet?',
                'si' => 'ඔබේ පළමු සුරතලාගේ නම කුමක්ද?',
            ],
            'What is your favorite travel destination in Sri Lanka?' => [
                'en' => 'What is your favorite travel destination in Sri Lanka?',
                'si' => 'ඔබ ප්‍රියතම ශ්‍රී ලාංකික සංචාරක ගමනාන්තය කුමක්ද?',
            ],
            'In what city or town were you born?' => [
                'en' => 'In what city or town were you born?',
                'si' => 'ඔබ උපන් නගරය කුමක්ද?',
            ],
        ];
    }

    /**
     * Check if provided answer matches the first hashed security answer (case-insensitive & trimmed).
     */
    public function verifySecurityAnswer(?string $answer): bool
    {
        if (empty($this->security_answer) || empty($answer)) {
            return false;
        }

        $normalized = mb_strtolower(trim($answer));

        return Hash::check($normalized, $this->security_answer);
    }

    /**
     * Check if provided answer matches the second hashed security answer (case-insensitive & trimmed).
     */
    public function verifySecurityAnswer2(?string $answer): bool
    {
        if (empty($this->security_answer_2) || empty($answer)) {
            return false;
        }

        $normalized = mb_strtolower(trim($answer));

        return Hash::check($normalized, $this->security_answer_2);
    }

    /**
     * Verify both security answers. If the user only has question 1 set (legacy), verify question 1.
     */
    public function verifyBothSecurityAnswers(?string $answer1, ?string $answer2): bool
    {
        if (! $this->verifySecurityAnswer($answer1)) {
            return false;
        }

        // If second question is configured, require and verify second answer too
        if (! empty($this->security_question_2)) {
            return $this->verifySecurityAnswer2($answer2);
        }

        return true;
    }

    /**
     * User's submitted news and events.
     */
    public function newsEvents(): HasMany
    {
        return $this->hasMany(NewsEvent::class);
    }
}
