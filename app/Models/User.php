<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_KUNDE = 'kunde';

    const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_KUNDE,
    ];

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'is_confirmed',
        'firma',
        'tel',
        'strasse',
        'zip_stadt',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isKunde(): bool
    {
        return $this->role === self::ROLE_KUNDE;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_confirmed'      => 'boolean',
        ];
    }

    /**
     * Get all inquiries for this user.
     */
    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    /**
     * Get all chatbot quotes for this user.
     */
    public function chatbotQuotes()
    {
        return $this->hasMany(ChatbotQuote::class);
    }
}
