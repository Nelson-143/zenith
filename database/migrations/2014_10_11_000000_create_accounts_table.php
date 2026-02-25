<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration {
    public function up() {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('name'); // Account name (e.g., company name)
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('accounts');
    }
}