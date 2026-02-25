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
        Schema::create('liabilities', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Name of the debt (e.g., "Bank Loan")
            $table->decimal('amount', 10, 2); // Total amount owed
            $table->decimal('interest_rate', 5, 2)->default(0); // Interest rate (if applicable)
            $table->date('due_date')->nullable(); // Due date for repayment
            $table->string('priority')->default('medium'); // Priority (high, medium, low)
            $table->string('type')->default('formal'); // Type (formal, informal)
            $table->decimal('remaining_balance', 10, 2)->default(0); // Remaining balance
            $table->unsignedBigInteger('account_id'); // Link to the account
            $table->timestamps();
    
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('liabilities');
    }
};
