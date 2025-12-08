<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DynamicQr extends Model
{
    protected $fillable = ['code', 'name', 'target_url', 'user_id', 'scans_count', 'logo_path'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
