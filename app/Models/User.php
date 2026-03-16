<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
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
            'password' => 'hashed',
            'is_confirmed' => 'boolean',
        ];
    }

    public function inquiries()
    {
        return $this->hasMany(\App\Models\Inquiry::class);
    }
}
