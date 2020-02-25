<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJobExceptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('job_exception', function(Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('job')->default('')->comment('job类');
            $table->integer('execution_time')->default(0)->comment('执行时间');
            $table->string('code', 16)->default('')->comment('状态码');
            $table->string('message')->default('')->comment('消息');
            $table->text('exception', 65535)->comment('异常');
            $table->boolean('status')->default(1)->comment('1：正常,2：异常');
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
        Schema::connection('mysql')->drop('job_exception');
    }
}
