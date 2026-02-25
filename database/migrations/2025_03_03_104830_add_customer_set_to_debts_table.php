<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerSetToDebtsTable extends Migration
{
    public function up()
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->string('customer_set'); // Add the customer_set column
        });
    }

    public function down()
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->dropColumn('customer_set'); // Remove the customer_set column if rolling back
        });
    }
}
