<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户id');
            $table->string('identiny',20)->comment('身份证');
            $table->string('truename',20)->comment('真实姓名');
            $table->string('bank',60)->comment('银行名字');
            $table->string('bank_num',60)->comment('银行卡号');
            $table->string('weixin',20)->comment('微信帐号');
            $table->string('alipay',20)->comment('支付宝帐号');
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
        Schema::dropIfExists('infos');
    }
}
