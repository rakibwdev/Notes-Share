<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrandUnit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'brand_id',
        'unit_id',
        'user_id',
        'quantity',
        'isEditable',
    ];

    protected function casts(): array
    {
        return [
            'isEditable' => 'boolean',
            'quantity' => 'integer',
        ];
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
