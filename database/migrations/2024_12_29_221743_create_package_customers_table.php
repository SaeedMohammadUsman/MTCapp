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
        Schema::create('package_customers', function (Blueprint $table) {
            $table->id(); // Primary Key
            $table->foreignId('price_package_id')->constrained('price_packages')->onDelete('cascade'); // Foreign Key
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade'); // Foreign Key
            $table->timestamps(); // Created_at, Updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_customers');
    }
};
