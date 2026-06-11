<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FileUpload extends Model
{
    protected $fillable = [
        'uploadable_type',
        'uploadable_id',
        'original_name',
        'file_path',
        'file_size',
        'mime_type',
    ];

    public function uploadable(): MorphTo
    {
        return $this->morphTo();
    }
}
