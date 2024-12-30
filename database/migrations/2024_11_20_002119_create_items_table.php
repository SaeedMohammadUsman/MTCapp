<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_code')->unique();  // Unique item code
            $table->string('trade_name_en');
            $table->string('trade_name_fa');
            $table->string('used_for_en');
            $table->string('used_for_fa');
            $table->string('size');
            $table->text('description_en');
            $table->text('description_fa');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Assuming you have a 'categories' table
            $table->softDeletes();  // Soft delete column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('items');
        
        Schema::enableForeignKeyConstraints();

    }
};
