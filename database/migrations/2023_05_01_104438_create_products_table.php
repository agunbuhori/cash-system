<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->char('code', 20)->unique();
            $table->foreignId('product_category_id')
                ->constrained();
            $table->string('name');
            $table->unsignedBigInteger('purchase_price');
            $table->unsignedBigInteger('price');
            $table->unsignedSmallInteger('amount')->default(1);
            $table->text('description')->nullable();
            $table->string('picture')->nullable();
            $table->boolean('update_cash')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
