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
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
     * 管理者かどうかを判定
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * PMOマネージャーかどうかを判定
     */
    public function isPmoManager(): bool
    {
        return $this->role === 'pmo_manager';
    }

    /**
     * 一般ユーザーかどうかを判定
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * 権限の日本語名を取得
     */
    public function getRoleNameAttribute(): string
    {
        return [
            'admin' => '管理者',
            'pmo_manager' => 'PMOマネージャー',
            'user' => 'ユーザー',
        ][$this->role] ?? $this->role;
    }

    /**
     * 権限の色クラスを取得
     */
    public function getRoleColorClassAttribute(): string
    {
        return [
            'admin' => 'bg-red-100 text-red-800',
            'pmo_manager' => 'bg-blue-100 text-blue-800',
            'user' => 'bg-gray-100 text-gray-800',
        ][$this->role] ?? 'bg-gray-100 text-gray-800';
    }
}
