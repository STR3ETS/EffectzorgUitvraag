<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManualTask extends Model
{
    protected $fillable = [
        'colleague_id',
        'sort_order',
        'description',
        'frequency',
        'time_per_task',
    ];

    public function colleague(): BelongsTo
    {
        return $this->belongsTo(Colleague::class);
    }
}
