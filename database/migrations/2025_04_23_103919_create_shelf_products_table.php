<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
//db
class CreateShelfProductsTable extends Migration
{
    public function up()
    {
        Schema::create('shelf_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('unit_name'); // e.g., Dozen
            $table->decimal('unit_price', 10, 2); // e.g., 5500.00
            $table->decimal('conversion_factor', 10, 2)->default(1); // e.g., 12
            $table->decimal('quantity', 10, 2)->default(0); // Shelf stock
            $table->foreignId('account_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shelf_products');
    }
}