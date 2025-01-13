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
            $table->id();
            $table->foreignId('stock_transaction_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('item_id')
                ->constrained('items')  
                ->onDelete('cascade');
            $table->integer('quantity'); 
            $table->decimal('price', 10, 2); 
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
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
