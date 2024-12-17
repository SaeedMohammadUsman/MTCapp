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
            $table->string('order_number', 50)->unique(); // Auto-generated order number
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade'); // Vendor selection
            $table->enum('status_en', ['Pending', 'Completed', 'Cancelled'])->default('Pending');
            $table->enum('status_fa', ['در انتظار', 'تکمیل شده', 'لغو شده'])->default('در انتظار'); // Synchronized with status_en
            $table->text('remarks')->nullable(); // Remarks field
            $table->softDeletes(); // Soft delete functionality
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        
        Schema::dropIfExists('purchase_orders');
        Schema::enableForeignKeyConstraints();
    }
    
};
