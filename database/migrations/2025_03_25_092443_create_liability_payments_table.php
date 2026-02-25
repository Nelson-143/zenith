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
        Schema::create('liability_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('liability_id')
                  ->constrained('liabilities')
                  ->onDelete('cascade'); // References the liabilities table
            $table->decimal('amount_paid', 10, 2); // Payment amount
            $table->timestamp('paid_at')->useCurrent(); // Timestamp of payment
            $table->unsignedBigInteger('account_id'); // References the accounts table
            $table->foreign('account_id')
                  ->references('id')
                  ->on('accounts')
                  ->onDelete('cascade'); // Cascade delete
            $table->timestamps(); // Created_at and updated_at timestamps
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liability_payments');
    }
};
