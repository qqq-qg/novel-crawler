<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('books', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->default('')->comment('标题');
            $table->string('category')->default('')->comment('分类');
            $table->string('ranking_title')->default('')->comment('榜单');
            $table->string('introduce')->default('')->comment('简介');
            $table->string('thumb')->default('')->comment('缩略图');
            $table->string('last_chapter_title')->default('')->comment('最新章节');
            $table->string('author')->default('')->comment('作者');
            $table->string('words_count')->default('')->comment('字数');
            $table->boolean('level')->default(0)->comment('等级');
            $table->integer('follow')->unsigned()->default(0)->comment('关注人数');
            $table->integer('hits')->unsigned()->default(0)->comment('浏览次数');
            $table->enum('update_status', array('LOADING','FINISHED'))->default('LOADING')->comment('更新状态');
            $table->boolean('status')->default(1)->index()->comment('状态');
            $table->integer('rule_id')->default(0)->comment('规则ID');
            $table->string('from_url')->default('')->comment('来源网址');
            $table->string('from_hash')->default('')->unique('books_fromhash_unique')->comment('来源网址hash,用来判断是否插入过');
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
        Schema::connection('mysql')->drop('books');
    }
}
