<?php

namespace App\Models;

use App\Models\Concerns\PreservesUniqueAttributesOnSoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use PreservesUniqueAttributesOnSoftDelete;
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'name',
        'phone',
        'whatsapp_id',
        'preferred_language',
        'last_message_at',
        'metadata',
        'pincode',
    ];

    protected $casts = [
        'metadata' => 'array',
        'archived_unique_values' => 'array',
        'last_message_at' => 'datetime',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(OrderFeedback::class);
    }

    public function getDeliveryAddressLinesAttribute(): array
    {
        return collect(data_get($this->metadata, 'delivery.address_book', []))
            ->map(function (array $address) {
                $parts = array_filter([
                    $address['pincode'] ?? null,
                    $address['city'] ?? null,
                    $address['address'] ?? null,
                ]);

                return implode(' | ', $parts);
            })
            ->filter()
            ->values()
            ->all();
    }

    public function getUniqueSoftDeleteColumns(): array
    {
        return ['phone'];
    }
}
