<?php

namespace App\Models;

use App\Models\Concerns\PreservesUniqueAttributesOnSoftDelete;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use PreservesUniqueAttributesOnSoftDelete;
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'customer_id',
        'conversation_id',
        'cart_id',
        'order_number',
        'status',
        'payment_status',
        'currency',
        'subtotal',
        'total',
        'notes',
        'metadata',
        'placed_at',
        'paid_at',
        'delivered_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'archived_unique_values' => 'array',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'placed_at' => 'datetime',
        'paid_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function feedback(): HasMany
    {
        return $this->hasMany(OrderFeedback::class);
    }

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class, 'source_id')
            ->where('source_type', static::class);
    }

    public function getUniqueSoftDeleteColumns(): array
    {
        return ['order_number'];
    }
}
