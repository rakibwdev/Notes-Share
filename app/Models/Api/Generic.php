<?php

namespace App\Models\Api;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Generic extends Model
{
    protected $fillable = [
        'generic_name',
    ];

    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }
}
