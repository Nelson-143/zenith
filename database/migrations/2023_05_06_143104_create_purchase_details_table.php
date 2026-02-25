<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\Purchase::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(\App\Models\Product::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->integer('quantity');
            $table->integer('unitcost');
            $table->integer('total');
            $table->unsignedBigInteger('account_id'); // Reference to accounts table
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->integer('previous_stock')->default(0); // Added previous_stock column
            $table->integer('current_stock')->default(0);  // Added current_stock column
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_details');
    }
};
