<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create the user_subscriptions table
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id'); // Use unsignedBigInteger to match users table
            $table->uuid('subscription_id');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade');
        });

        // Update the users table
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('subscription_id')->nullable()->after('photo');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('set null');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_id');
            $table->boolean('is_trialing')->default(false)->after('subscription_ends_at');
            $table->timestamp('trial_ends_at')->nullable()->after('is_trialing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the users table update
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['subscription_id']);
            $table->dropColumn(['subscription_id', 'subscription_ends_at', 'is_trialing', 'trial_ends_at']);
            $table->unsignedBigInteger('account_id'); // Reference to accounts table
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });

        // Drop the user_subscriptions table
        Schema::dropIfExists('user_subscriptions');
    }
};
