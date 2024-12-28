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
        Schema::create('received_goods', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->text('remark')->nullable();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->string('bill_attachment')->nullable();
            $table->date('date')->useCurrent();
            $table->boolean('is_finalized')->default(false);
            $table->boolean('stocked_in')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('received_goods');
    }
};
