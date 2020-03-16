<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialiteWechatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socialite_wechat', function (Blueprint $table) {
            $table->increments('id');
            $table->string('client_id','255')->comment('app_id');
            $table->char('openid',28)->unique()->comment('openid');
            $table->char('unionid',29)->default('')->comment('unionid');
            $table->string('nickname',255)->comment('昵称');
            $table->tinyInteger('sex')->comment('性别');
            $table->string('city',255)->comment('城市');
            $table->string('province',255)->comment('省份');
            $table->string('country',255)->comment('国别');
            $table->string('headimgurl',500)->comment('头像');
            $table->timestamps();
            $table->comment = '微信用户记录表';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socialite_wechat');
    }
}
