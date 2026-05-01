<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'role',
    ];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    // Override to use user_notifications table instead of notifications
    public function databaseNotificationModel(): string
    {
        return UserNotification::class;
    }

    public function notifications()
    {
        return $this->morphMany(UserNotification::class, 'notifiable')
                    ->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->morphMany(UserNotification::class, 'notifiable')
                    ->whereNull('read_at')
                    ->orderBy('created_at', 'desc');
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
