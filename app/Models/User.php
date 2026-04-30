<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'phone',
        'city',
        'avatar_path',
        'role',
        'is_banned',
        'password',
        'google_id',
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
            'is_banned' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function ads(): HasMany
    {
        return $this->hasMany(Ad::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function chatsAsBuyer(): HasMany
    {
        return $this->hasMany(Chat::class, 'buyer_id')->where('type', 'ad');
    }

    public function chatsAsSeller(): HasMany
    {
        return $this->hasMany(Chat::class, 'seller_id')->where('type', 'ad');
    }

    public function supportChat(): HasOne
    {
        return $this->hasOne(Chat::class, 'buyer_id')->where('type', 'support');
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function hissaRequests(): HasMany
    {
        return $this->hasMany(HissaRequest::class, 'buyer_id');
    }

    public function receivedHissaRequests()
    {
        return $this->hasManyThrough(HissaRequest::class, Ad::class, 'user_id', 'ad_id');
    }

    public function unreadMessagesCount(): int
    {
        return Message::whereHas('chat', function ($q) {
            $q->where('buyer_id', $this->id)->orWhere('seller_id', $this->id);
        })
        ->where('sender_id', '!=', $this->id)
        ->where('is_read', false)
        ->count();
    }

    public function hasExceededFreeAdsLimit(): bool
    {
        // Each user can post only 5 free ads. 6th ad requires payment.
        // We count total ads the user has ever posted (excluding those that are payment_pending and never paid?)
        // Or simply count all ads. Let's count all ads.
        return $this->ads()->count() >= 5;
    }
}
