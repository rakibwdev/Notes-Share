<?php

namespace App\Models;

use App\Models\Api\Generic;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'brand_name',
        'description',
        'generic_id',
        'company_id',
        'price',
        'image',
        'is_discounted',
        'discount_price',
        'packsize',
        'form',
        'strength',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'is_discounted' => 'boolean',
        ];
    }

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
