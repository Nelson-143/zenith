<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
    $table->decimal('amount', 10, 2);
    $table->decimal('amount_paid', 10, 2)->default(0);
    $table->date('due_date');
    $table->timestamp('paid_at')->nullable();
    $table->timestamps();
    $table->uuid('uuid')->unique();
    $table->unsignedBigInteger('account_id');
    $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('debts');
    }
}

