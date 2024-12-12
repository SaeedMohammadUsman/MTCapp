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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name_en'); // English name of the item
            $table->string('item_name_fa'); // Persian name of the item
            $table->string('item_code')->unique(); // Unique code for each item
            $table->decimal('cost_price', 10, 2); // Cost price for purchasing
            $table->decimal('selling_price', 10, 2); // Price at which the item is sold
            $table->integer('quantity_in_stock')->default(0); // Track stock
            $table->date('expiration_date')->nullable(); // For perishable goods
            $table->text('description_en')->nullable(); // Optional description in English
            $table->text('description_fa')->nullable(); // Optional description in Persian
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('inventory_items');
        Schema::enableForeignKeyConstraints();
    }
};
