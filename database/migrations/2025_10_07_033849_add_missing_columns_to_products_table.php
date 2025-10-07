<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan hanya jika belum ada
            if (!Schema::hasColumn('products', 'store_id')) {
                $table->foreignId('store_id')->after('id')->constrained('stores')->onDelete('cascade');
            }
            if (!Schema::hasColumn('products', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('store_id')->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('products', 'sku')) {
                $table->string('sku')->nullable()->after('name');
            }
            if (!Schema::hasColumn('products', 'category')) {
                $table->string('category')->nullable()->after('sku');
            }
            if (!Schema::hasColumn('products', 'price')) {
                $table->decimal('price', 15, 2)->default(0)->after('category');
            }
            if (!Schema::hasColumn('products', 'stock')) {
                $table->integer('stock')->default(0)->after('price');
            }
            if (!Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('stock');
            }
            if (!Schema::hasColumn('products', 'image')) {
                $table->string('image')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['store_id']);
            $table->dropColumn([
                'store_id', 'user_id', 'sku', 'category', 'price', 'stock', 'description', 'image'
            ]);
        });
    }
};
