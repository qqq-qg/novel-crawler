<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('links', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('名称');
            $table->string('linkurl')->comment('链接');
            $table->boolean('listorder')->default(0)->comment('排序');
            $table->boolean('status')->default(0)->comment('状态');
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
        Schema::connection('mysql')->drop('links');
    }
}
