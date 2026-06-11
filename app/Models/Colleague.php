<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Colleague extends Model
{
    protected $fillable = [
        'submission_id',
        'sort_order',
        'name',
        'function',
        'wishes',
        'pain_points',
        'desired_workflow',
    ];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    public function manualTasks(): HasMany
    {
        return $this->hasMany(ManualTask::class)->orderBy('sort_order');
    }

    public function missingFeatures(): HasMany
    {
        return $this->hasMany(MissingFeature::class)->orderBy('sort_order');
    }

    public function fileUploads(): MorphMany
    {
        return $this->morphMany(FileUpload::class, 'uploadable');
    }
}
