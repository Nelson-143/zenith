<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /** 
     * Run the migrations. 
     */
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('username')->nullable()->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string("store_name")->nullable();
            $table->string("store_address")->nullable();
            $table->string("store_phone")->nullable();
            $table->string("store_email")->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('account_id');
            
            // New fields for enhanced functionality
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_banned')->default(false);
            $table->datetime('last_login')->nullable();
            $table->string('last_ip')->nullable();
            $table->string('timezone')->default('UTC');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
        
    }

    /** 
     * Reverse the migrations. 
     */
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
        });
        Schema::dropIfExists('users');
    }
};