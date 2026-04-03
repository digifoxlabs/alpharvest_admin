<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('support_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('description')->nullable();
            $table->string('currency', 10)->default('INR');
            $table->string('whatsapp_phone_number_id')->nullable();
            $table->string('whatsapp_business_account_id')->nullable();
            $table->string('meta_catalog_id')->nullable();
            $table->text('meta_access_token')->nullable();
            $table->string('whatsapp_brand_name')->nullable();
            $table->text('whatsapp_welcome_text')->nullable();
            $table->text('whatsapp_store_intro')->nullable();
            $table->text('whatsapp_contact_text')->nullable();
            $table->string('whatsapp_store_image_path')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
