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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('set null');
            $table->decimal('amount', 9, 2);
            $table->enum('transaction_type', ['income', 'expense', 'transfer']);
            $table->enum('source', [
                'payment_to_vendor', 
                'salary_payment', 
                'daily_expense', 
                'payment_to_distributors', 
                'customer_payment_received',
                'advance_payment_for_purchasing', 
                'transfer_to_sarrafi', 
                'transfer_to_cash',
                'miscellaneous_income', 
                'other_expense',
                
            ]);
            $table->text('description')->nullable();
            $table->date('transaction_date');
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
