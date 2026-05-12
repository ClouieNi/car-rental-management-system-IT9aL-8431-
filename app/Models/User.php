<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'role',
        'security_question',
        'security_answer_hash',
    ];

    /**
     * Preset security questions
     */
    public static function securityQuestions(): array
    {
        return [
            'mother_maiden' => "What is your mother's maiden name?",
            'first_pet' => "What was the name of your first pet?",
            'birth_city' => "What city were you born in?",
            'favorite_food' => "What is your favorite food?",
            'elementary_school' => "What was the name of your elementary school?",
        ];
    }

    /**
     * Verify security answer
     */
    public function verifySecurityAnswer(string $answer): bool
    {
        return $this->security_answer_hash && password_verify(strtolower(trim($answer)), $this->security_answer_hash);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }
}