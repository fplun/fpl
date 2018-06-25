<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('miners', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('level')->comment('矿机等级 1微型 2小型 3中型 4大型 5超级');
            $table->double('yield',15,8)->comment('每小时产量');
            $table->integer('cycle')->comment('生命周期');
            $table->double('price',10,2)->comment('矿机价格');
            $table->string('img')->comment('矿机图片');
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
        Schema::dropIfExists('miners');
    }
}
