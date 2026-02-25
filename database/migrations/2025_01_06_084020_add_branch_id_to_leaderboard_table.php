<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBranchIdToLeaderboardTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('leaderboard', function (Blueprint $table) {
            $table->unsignedBigInteger('branch_id')->nullable(); // Nullable for super admins
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('leaderboard', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('branch_id');
        });
    }
};