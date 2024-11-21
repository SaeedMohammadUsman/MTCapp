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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('inventory_items')->onDelete('cascade');
            $table->string('trade_name_en', 100);
            $table->string('trade_name_fa',100); 
            $table->string('used_for_en', 255)->nullable();
            $table->string('used_for_fa', 255)->nullable();
            
            $table->string('size', 50)->nullable();
            $table->string('c_size', 50)->nullable();
            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity');
            $table->decimal('total_price', 15, 2);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
