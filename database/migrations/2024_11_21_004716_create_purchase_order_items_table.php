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
            $table->foreignId('purchase_order_id')->constrained('purchase_orders')->onDelete('cascade'); // Cascade delete with purchase orders
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); // Reference to items table
            $table->integer('quantity'); // Quantity of the item
            $table->text('remarks')->nullable(); // Remarks field
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('purchase_order_items');
        Schema::enableForeignKeyConstraints();
    }
    
};
