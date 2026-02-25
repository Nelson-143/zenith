<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
//db
class CreateShelfStockLogsTable extends Migration
{
    public function up()
    {
        Schema::create('shelf_stock_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shelf_product_id')->constrained()->onDelete('cascade');
            $table->decimal('quantity_change', 10, 2); // Positive for addition, negative for deduction
            $table->string('action'); // e.g., 'add', 'deduct', 'update'
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shelf_stock_logs');
    }
}