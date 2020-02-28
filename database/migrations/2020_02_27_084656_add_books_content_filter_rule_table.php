<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBooksContentFilterRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection('mysql')->create('books_content_filter_rule', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('books_id')->nullable(false)->default(0)->comment('BookID');
            $table->text('rule')->comment('规则');
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
        Schema::connection('mysql')->drop('books_content_filter_rule');
    }
}
