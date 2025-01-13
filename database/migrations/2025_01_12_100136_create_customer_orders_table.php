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
        Schema::create('customer_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('price_package_id')->nullable()->constrained('price_packages')->onDelete('set null');
            $table->enum('status', ['pending', 'completed'])->default('pending');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamp('order_date')->useCurrent();
            $table->softDeletes();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_orders');
    }
};
