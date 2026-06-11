<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'datum',
        'project_name',
        'contactpersoon',
        'candidates_capabilities',
        'candidates_matching',
        'candidates_mobile',
        'employers_capabilities',
        'employers_requesting',
        'employers_portal',
        'scope_replace_or_connect',
        'scope_mvp',
        'scope_budget_deadline',
        'scope_decision_maker',
        'data_current_systems',
        'data_migration',
        'data_api_integrations',
        'compliance_checks',
        'compliance_privacy',
        'compliance_working_well',
        'compliance_numbers_success',
        'overall_workflows',
        'overall_remarks',
        'status',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
        ];
    }

    public function systemAccesses(): HasMany
    {
        return $this->hasMany(SystemAccess::class)->orderBy('sort_order');
    }

    public function colleagues(): HasMany
    {
        return $this->hasMany(Colleague::class)->orderBy('sort_order');
    }

    public function fileUploads(): MorphMany
    {
        return $this->morphMany(FileUpload::class, 'uploadable');
    }
}
