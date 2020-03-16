<?php
namespace Yjtec\Socialite\Commands\Databases;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Socialite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socialite', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('socialitetable_id')->comment('三方登陆表的ID');
            $table->string('socialitetable_type')->comment('三方登陆表的名称');
            $table->timestamps();
            $table->comment = '三方登陆总表';
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('socialite');
    }
}
