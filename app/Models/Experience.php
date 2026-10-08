<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{
    protected function casts(): array
    {
        return ['is_current' => 'boolean'];
    }

    protected $fillable = ['company', 'role', 'location', 'start_date', 'end_date', 'is_current', 'description', 'position'];

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}
