<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableForSubscriptions extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add subscription fields
            if (!Schema::hasColumn('users', 'subscription_id')) {
                $table->unsignedBigInteger('subscription_id')->nullable()->after('photo');
                $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('set null');
            }
            if (!Schema::hasColumn('users', 'subscription_ends_at')) {
                $table->timestamp('subscription_ends_at')->nullable()->after('subscription_id');
            }
            if (!Schema::hasColumn('users', 'is_trialing')) {
                $table->boolean('is_trialing')->default(false)->after('subscription_ends_at');
            }
            if (!Schema::hasColumn('users', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('is_trialing');
                $table->uuid('account_id')->nullable(); // Add account_id
                $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade'); // Add foreign key directly
            }
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            if (!Schema::hasColumn('subscriptions', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop subscription fields
            $table->dropForeign(['subscription_id']);
            $table->dropColumn(['subscription_id', 'subscription_ends_at', 'is_trialing', 'trial_ends_at']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
?>
