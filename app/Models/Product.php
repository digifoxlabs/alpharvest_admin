<?php

namespace App\Models;

use App\Models\Concerns\PreservesUniqueAttributesOnSoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;
    use PreservesUniqueAttributesOnSoftDelete;
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'product_category_id',
        'name',
        'slug',
        'sku',
        'meta_retailer_id',
        'description',
        'color',
        'size',
        'shipping_weight',
        'image_path',
        'price',
        'sale_price',
        'inventory_quantity',
        'metadata',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'metadata' => 'array',
        'archived_unique_values' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'shipping_weight' => 'decimal:2',
    ];

    protected $appends = [
        'image_url',
        'display_price',
        'has_discount',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id')->withTrashed();
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        if (str_starts_with($this->image_path, 'uploads/')) {
            return asset($this->image_path);
        }

        return Storage::disk('public')->url($this->image_path);
    }

    public function getDisplayPriceAttribute(): float
    {
        return (float) ($this->sale_price ?? $this->price);
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->sale_price !== null && (float) $this->sale_price < (float) $this->price;
    }

    public function getUniqueSoftDeleteColumns(): array
    {
        return ['slug', 'sku'];
    }
}
