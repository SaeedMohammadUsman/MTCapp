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
        Schema::create('price_package_details', function (Blueprint $table) {
            $table->id(); 
            $table->foreignId('price_package_id')->constrained('price_packages')->onDelete('cascade'); 
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade'); 
            $table->decimal('discount', 5, 2)->nullable(); 
            $table->decimal('price', 10, 2)->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_package_details');
    }
};
