<?php

namespace App\Models;

use App\Models\Api\Generic;
use App\Models\Company;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public function generic()
    {
        return $this->belongsTo(Generic::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function brandUnits()
    {
        return $this->hasMany(BrandUnit::class);
    }
    public function units()
    {
        return $this->belongsToMany(Unit::class, 'brand_units')
            ->withPivot('quantity', 'user_id', 'isEditable')
            ->withTimestamps();
    }
}
