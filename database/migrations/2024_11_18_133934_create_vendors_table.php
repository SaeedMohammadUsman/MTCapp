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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('company_name_en'); // English company name
            $table->string('company_name_fa'); // Persian company name
            $table->string('email')->unique(); // Email
            $table->string('phone_number'); // Phone number
            $table->text('address_en'); // English address
            $table->text('address_fa'); // Persian address
            $table->enum('country_name', ['Pakistan', 'India', 'Iran']);
            $table->string('currency')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
