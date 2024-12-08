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
        Schema::create('batch_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('inventory_batches')->onDelete('cascade');
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');
            $table->decimal('cost_price', 10, 2); // Specific cost for the item in this batch
            $table->decimal('selling_price', 10, 2); // Specific selling price for the item in this batch
            $table->integer('quantity'); // Quantity of the item in this batch
            $table->date('expiration_date')->nullable(); // Expiration date for the item in this batch
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_item');
    }
};
