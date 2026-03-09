<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = [
        'name',
        'generic_name',
        'generic_id',
        'category_id',
        'manufacturer',
        'manufacturer_id',
        'description',
        'pieces_per_strip',
        'pieces_per_box',
        'price_per_piece',
        'status',
        'low_stock_threshold',
    ];

    /**
     * Determine if the product is in low stock state.
     */
    public function getIsLowStockAttribute(): bool
    {
        $globalThreshold = (int) Setting::getValue('global_low_stock_threshold', 10);
        $threshold = $this->low_stock_threshold ?? $globalThreshold;
        
        return $this->total_stock < $threshold;
    }

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'price_per_piece' => 'float',
        ];
    }

    public function generic(): BelongsTo
    {
        return $this->belongsTo(Generic::class);
    }

    public function manufacturerRelationship(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }

    /**
     * Get the default selling price.
     */
    public function getPriceAttribute(): float
    {
        return (float) ($this->batches->sortBy('selling_price')->first()->selling_price ?? $this->price_per_piece);
    }

    /**
     * Calculate price for a specific unit.
     */
    public function getUnitPrice(string $unitType): float
    {
        $basePrice = $this->price;
        return match ($unitType) {
            'strip' => $basePrice * $this->pieces_per_strip,
            'box' => $basePrice * $this->pieces_per_box,
            default => $basePrice,
        };
    }

    /**
     * Convert ordered quantity to base unit (pieces).
     */
    public function convertToBaseUnit(int $quantity, string $unitType): int
    {
        return match ($unitType) {
            'strip' => $quantity * $this->pieces_per_strip,
            'box' => $quantity * $this->pieces_per_box,
            default => $quantity,
        };
    }

    /**
     * Calculate total available stock from non-expired batches.
     */
    public function getTotalStockAttribute(): int
    {
        return $this->batches()
            ->where('expiry_date', '>', now())
            ->sum('quantity');
    }
}
