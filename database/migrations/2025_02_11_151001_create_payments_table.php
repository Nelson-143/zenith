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
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('debt_id')->constrained()->onDelete('cascade');
        $table->decimal('amount_paid', 10, 2);
        $table->timestamp('paid_at')->useCurrent();
        $table->unsignedBigInteger('account_id'); // Reference to accounts table
        $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');

    
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
