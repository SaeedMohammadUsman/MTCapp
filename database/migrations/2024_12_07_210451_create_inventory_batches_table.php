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
         Schema::create('inventory_batches', function (Blueprint $table) {
            $table->id();     
            $table->string('batch_number')->unique(); // Unique batch identifier
            $table->text('remark')->nullable(); // Optional remarks or notes
            $table->timestamps(); // Created and updated timestamps
            $table->softDeletes(); 
         });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_batches');
    }
};
