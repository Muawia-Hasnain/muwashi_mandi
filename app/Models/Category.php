<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image_icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function ads()
    {
        return $this->hasMany(Ad::class);
    }

    public function activeAdsCount()
    {
        return $this->ads()->where('status', 'approved')->count();
    }
}
