<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            if (! Schema::hasColumn('product_categories', 'store_id')) {
                $table->foreignId('store_id')->nullable()->after('id')->constrained('stores')->nullOnDelete();
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'store_id')) {
                $table->foreignId('store_id')->nullable()->after('id')->constrained('stores')->nullOnDelete();
            }
        });

        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'store_id')) {
                $table->foreignId('store_id')->nullable()->after('id')->constrained('stores')->nullOnDelete();
            }
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('phone');
            $table->string('whatsapp_id')->nullable();
            $table->string('preferred_language', 5)->default('en');
            $table->timestamp('last_message_at')->nullable();
            $table->json('metadata')->nullable();
            $table->string('pincode')->nullable();
            $table->timestamps();
            $table->unique(['store_id', 'phone']);
        });

        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('source')->default('whatsapp');
            $table->string('status')->default('open');
            $table->timestamp('last_message_at')->nullable();
            $table->json('context')->nullable();
            $table->timestamps();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->string('direction');
            $table->string('type')->default('text');
            $table->string('whatsapp_message_id')->nullable()->unique();
            $table->text('body')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('conversation_id')->nullable()->constrained('conversations')->nullOnDelete();
            $table->string('status')->default('active');
            $table->string('currency', 10)->default('INR');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamp('checked_out_at')->nullable();
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->timestamps();
            $table->unique(['cart_id', 'product_id']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('provider')->default('manual_link');
            $table->string('reference')->unique();
            $table->string('status')->default('pending');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10);
            $table->text('payment_url')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        Schema::create('webhook_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
            $table->string('provider')->default('whatsapp_cloud');
            $table->string('event_type')->default('message');
            $table->string('external_id')->nullable();
            $table->string('status')->default('received');
            $table->text('error_message')->nullable();
            $table->json('payload');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('webhook_events');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('customers');

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'store_id')) {
                $table->dropConstrainedForeignId('store_id');
            }
        });

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'store_id')) {
                $table->dropConstrainedForeignId('store_id');
            }
        });

        Schema::table('product_categories', function (Blueprint $table) {
            if (Schema::hasColumn('product_categories', 'store_id')) {
                $table->dropConstrainedForeignId('store_id');
            }
        });
    }
};
