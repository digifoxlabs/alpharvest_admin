<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->json('archived_unique_values')->nullable()->after('metadata');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->json('archived_unique_values')->nullable()->after('metadata');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->json('archived_unique_values')->nullable()->after('metadata');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->json('archived_unique_values')->nullable()->after('description');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->json('archived_unique_values')->nullable()->after('payload');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('archived_unique_values');
        });

        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn('archived_unique_values');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('archived_unique_values');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('archived_unique_values');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('archived_unique_values');
        });
    }
};
