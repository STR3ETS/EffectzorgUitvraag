<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemAccess extends Model
{
    protected $fillable = [
        'submission_id',
        'system_name',
        'sort_order',
        'action',
        'test_account',
        'demo_url',
        'access_contact',
        'modules',
        'remarks',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }
}
