<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLinksubmitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('linksubmit', function(Blueprint $table) {
			$table->string('site')->default('baidu')->comment('百度|360|搜狗|谷歌');
			$table->bigInteger('bookid')->unsigned()->comment('书本ID');
			$table->integer('detailid')->unsigned()->comment('Detail表ID');
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
        Schema::connection('mysql')->drop('linksubmit');
    }
}
