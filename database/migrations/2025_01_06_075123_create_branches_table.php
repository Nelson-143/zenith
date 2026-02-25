<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Branch name
            $table->enum('status', ['active', 'disabled'])->default('active'); // Branch status
            $table->timestamps(); // Created and updated timestamps
            $table->unsignedBigInteger('account_id'); // Reference to accounts table
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            // Foreign key constraint
        });
    }
}