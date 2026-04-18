<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'support_phone',
        'contact_email',
        'contact_phone',
        'description',
        'currency',
        'whatsapp_phone_number_id',
        'whatsapp_business_account_id',
        'meta_catalog_id',
        'meta_access_token',
        'whatsapp_brand_name',
        'whatsapp_welcome_text',
        'whatsapp_store_intro',
        'whatsapp_contact_text',
        'whatsapp_store_image_path',
        'settings',
        'is_active',
    ];

    protected $hidden = [
        'meta_access_token',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'whatsapp_store_image_url',
    ];

    public function categories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function orderFeedback(): HasMany
    {
        return $this->hasMany(OrderFeedback::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Order::class);
    }

    public function getWhatsappStoreImageUrlAttribute(): ?string
    {
        if (! $this->whatsapp_store_image_path) {
            return null;
        }

        if (str_starts_with($this->whatsapp_store_image_path, 'uploads/')) {
            return asset($this->whatsapp_store_image_path);
        }

        return Storage::disk('public')->url($this->whatsapp_store_image_path);
    }
}
