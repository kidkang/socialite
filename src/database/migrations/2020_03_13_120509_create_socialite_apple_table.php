<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialiteAppleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socialite_apple', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id','255')->comment('app_id');
            $table->string('sub',255)->unique()->comment('苹果唯一标示符');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socialite_apple');
    }
}
