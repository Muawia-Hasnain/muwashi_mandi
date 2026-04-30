<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HissaRequest extends Model
{
    protected $fillable = [
        'ad_id',
        'buyer_id',
        'buyer_name',
        'buyer_phone',
        'requested_hisse',
        'status',
    ];

    public function ad(): BelongsTo
    {
        return $this->belongsTo(Ad::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }
}
