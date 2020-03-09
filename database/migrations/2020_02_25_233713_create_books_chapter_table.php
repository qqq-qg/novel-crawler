<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBooksChapterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('books_chapter', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('books_id')->unsigned()->default(0)->comment('ID');
            $table->integer('chapter_index')->unsigned()->default(0)->comment('序号');
            $table->string('title')->default('')->comment('标题');
            $table->integer('hits')->unsigned()->default(0)->comment('浏览次数');
            $table->boolean('status')->default(1)->comment('状态');
            $table->string('from_url')->default('')->comment('来源链接');
            $table->string('from_hash')->default('')->unique('books_detail_fromhash_unique')->comment('来源链接hash值');
            $table->boolean('is_success')->default(0)->comment('采集状态');
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
        Schema::connection('mysql')->drop('books_chapter');
    }
}
