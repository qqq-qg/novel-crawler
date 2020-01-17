<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionTaskTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {

    Schema::create('collection_rule', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('title', 255)->nullable(false)->default('')->comment('标题');
      $table->text('rule_json')->comment('采集规则');
      $table->tinyInteger('status')->nullable(false)->default(1)->unsigned()->comment('状态,1-正常，2-无效');
      $table->timestamps();
    });


    Schema::create('collection_task', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('title', 255)->nullable(false)->default('')->comment('标题');
      $table->string('from_url', 255)->nullable(false)->default('')->comment('采集链接');
      $table->string('from_hash', 255)->nullable(false)->default('')->comment('采集链接');
      $table->integer('rule_id')->nullable(false)->default(1)->comment('采集规则');
      $table->integer('page_limit')->nullable(false)->default(2)->comment('采集页数');
      $table->integer('task_code')->nullable(false)->default(1)->comment('采集状态，1-就绪，2-采集中，200-已完成');
      $table->tinyInteger('status')->nullable(false)->default(1)->unsigned()->comment('状态,1-正常，2-无效');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('collection_rule');
    Schema::dropIfExists('collection_task');
  }
}
