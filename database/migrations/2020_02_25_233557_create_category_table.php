<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('category', function(Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('name', 64)->default('')->comment('分类名称');
            $table->integer('listorder')->default(99)->comment('排序，数字越小越前');
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
        Schema::connection('mysql')->drop('category');
    }
}
