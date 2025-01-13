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
        Schema::create('customer_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_order_id')->constrained('customer_orders')->onDelete('cascade'); // Foreign key to customer_orders
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); // Foreign key to items
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('customer_order_items');
    }
};
