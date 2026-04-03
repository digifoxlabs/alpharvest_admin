<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
    ];
}
