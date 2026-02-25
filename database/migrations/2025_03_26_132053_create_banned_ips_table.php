<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannedIpsTable extends Migration
{
    public function up()
    {
        Schema::create('banned_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->unique();
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('banned_by')->nullable();
            $table->timestamps();
            
            $table->foreign('banned_by')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('banned_ips');
    }
}