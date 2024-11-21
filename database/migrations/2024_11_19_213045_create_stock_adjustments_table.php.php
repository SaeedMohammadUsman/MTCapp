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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('inventory_items')->onDelete('cascade'); 
            
            $table->enum('adjustment_type_en', ['damaged', 'returns'])->comment('Reason for adjustment in English');
            $table->enum('adjustment_type_fa', ['خرابی', 'بازگشت'])->comment('Reason for adjustment in Persian');
            $table->integer('quantity'); // Number of items adjusted
            $table->text('reason_en')->nullable(); // Detailed explanation in English
            $table->text('reason_fa')->nullable(); // Detailed explanation in Persian
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
