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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->decimal('total_price', 15, 2);
            $table->enum('status_en', ['Pending', 'Completed', 'Cancelled'])->default('Pending');
            $table->enum('status_fa', ['در انتظار', 'تکمیل شده', 'لغو شده'])->default('در انتظار');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
