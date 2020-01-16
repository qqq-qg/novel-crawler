<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class JobException extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('job_exception', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('job')->default('')->comment('job类');
      $table->integer('execution_time')->default(0)->comment('执行时间');
      $table->string('code', 16)->default('')->comment('状态码');
      $table->string('message')->default('')->comment('消息');
      $table->text('exception')->comment('异常');
      $table->tinyInteger('status')->default(1)->comment('1：正常,2：异常');
      $table->timestamp('created_at')->nullable();
      $table->timestamp('updated_at')->nullable();
    });

    DB::statement('ALTER TABLE `job_exception` COMMENT="job 异常表"');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('job_exception');
  }
}
