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
        Schema::table('accounts', function (Blueprint $table) {
            $table->string('currency')->default('TZS'); // Default currency
            $table->boolean('is_currency_active')->default(true); // Activation status
        });
    }
    
    public function down()
    {
        Schema::table('accounts', function (Blueprint $table) {
            $table->dropColumn('currency');
            $table->dropColumn('is_currency_active');
        });
    }
};
                                    