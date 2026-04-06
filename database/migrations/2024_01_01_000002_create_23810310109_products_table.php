<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('23810310109_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('23810310109_categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->string('image_path')->nullable();
            $table->enum('status', ['draft', 'published', 'out_of_stock'])->default('draft');
            // Trường sáng tạo: phần trăm giảm giá
            $table->unsignedTinyInteger('discount_percent')->default(0)->comment('Phần trăm giảm giá 0-100');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('23810310109_products');
    }
};
