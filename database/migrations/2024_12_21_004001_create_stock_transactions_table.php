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
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->enum('transaction_type', [1, 2, 3, 4]) // Enum for transaction types
            ->comment('1: Stock In, 2: Stock Out, 3: Return, 4: Damaged');
            $table->unsignedBigInteger('reference_id')->nullable(); // Generic reference ID
            $table->string('reference_type')->nullable(); // Indicates the source table (e.g., 'received_goods', 'sells', 'damage_reports')
            $table->text('remarks')->nullable(); // Additional information about the transaction
            $table->timestamp('transaction_date')->useCurrent(); // Timestamp of the transaction
            $table->timestamps(); // Created at and updated at timestamps
            $table->softDeletes(); // Soft delete


       
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
