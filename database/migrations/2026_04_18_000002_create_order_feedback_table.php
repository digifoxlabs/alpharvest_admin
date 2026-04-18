<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->unsignedTinyInteger('score');
            $table->string('channel')->default('whatsapp');
            $table->json('payload')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
            $table->unique('order_id');
            $table->index(['store_id', 'responded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_feedback');
    }
};
