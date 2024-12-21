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
        Schema::create('stock_transaction_details', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('stock_transaction_id')->constrained()->onDelete('cascade'); // Reference to stock transaction
            $table->decimal('arrival_price', 10, 2); // Arrival price (including tax, transport, etc.)
            $table->text('remarks')->nullable(); // Optional remarks for the transaction detail
            $table->timestamps(); // Created at and updated at timestamps
            $table->softDeletes(); // Soft delete
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_transaction_details');
    }
};
