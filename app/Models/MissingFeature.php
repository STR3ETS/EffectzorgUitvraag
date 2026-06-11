<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MissingFeature extends Model
{
    protected $fillable = [
        'colleague_id',
        'sort_order',
        'system_name',
        'description',
        'workaround',
        'extra_time',
    ];

    public function colleague(): BelongsTo
    {
        return $this->belongsTo(Colleague::class);
    }
}
