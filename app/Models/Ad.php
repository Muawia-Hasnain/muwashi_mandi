<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'price',
        'breed',
        'age_info',
        'city',
        'area',
        'status',
        'rejection_reason',
        'expires_at',
        'is_featured',
        'is_boosted',
        'ad_type',
        'district_id',
        'tehsil_id',
        'village',
        'org_name',
        'cnic_number',
        'total_hisse',
        'booked_hisse',
        'slug',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'expires_at' => 'datetime',
        'featured_expires_at' => 'datetime',
        'boost_expires_at' => 'datetime',
        'is_featured' => 'boolean',
        'is_boosted' => 'boolean',
        'total_hisse' => 'integer',
        'booked_hisse' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(AdImage::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function tehsil(): BelongsTo
    {
        return $this->belongsTo(Tehsil::class);
    }

    public function hissaRequests(): HasMany
    {
        return $this->hasMany(HissaRequest::class);
    }

    public function getRemainingHisseAttribute()
    {
        return max(0, $this->total_hisse - $this->booked_hisse);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'approved')
                     ->where('expires_at', '>', now())
                     ->whereHas('user', function ($q) {
                         $q->where('is_banned', false);
                     });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ad) {
            $ad->slug = \Illuminate\Support\Str::slug($ad->title);
        });

        static::updating(function ($ad) {
            if ($ad->isDirty('title')) {
                $ad->slug = \Illuminate\Support\Str::slug($ad->title);
            }
        });
    }
}
