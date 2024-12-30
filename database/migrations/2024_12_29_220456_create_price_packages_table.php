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
        Schema::create('price_packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade'); 
            $table->softDeletes();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        
        Schema::dropIfExists('price_packages');
        Schema::enableForeignKeyConstraints();

    }
};
