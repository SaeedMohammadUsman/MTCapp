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
            $table->enum('transaction_type', [1, 2, 3, 4])
                ->comment('1: Stock In, 2: Stock Out, 3: Return, 4: Damaged');
            $table->foreignId('received_good_id')->nullable()->constrained('received_goods')->onDelete('set null'); 
            //[for customer order id] add foreign key to customer_orders table null
            $table->text('remarks')->nullable(); 
            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamps(); 
            $table->softDeletes(); 
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
       