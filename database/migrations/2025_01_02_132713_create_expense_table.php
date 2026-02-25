<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Links to users table
            $table->foreignId('category_id')->constrained('expense_categories')->onDelete('cascade'); // Links to expense_categories table
            $table->decimal('amount', 10, 2); // Amount spent
            $table->text('description')->nullable(); // Optional description of the expense
            $table->date('expense_date'); // Date of the expense
            $table->string('attachment')->nullable(); // Optional file attachment for receipts
            $table->unsignedBigInteger('account_id'); // Reference to accounts table
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense');
    }
}

