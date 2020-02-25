<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCollectionTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('collection_task', function(Blueprint $table) {
			$table->bigInteger('id', true)->unsigned();
			$table->string('title')->default('')->comment('标题');
			$table->string('from_url')->default('')->comment('采集链接');
			$table->string('from_hash')->default('')->comment('采集链接');
			$table->integer('rule_id')->default(1)->comment('采集规则');
			$table->integer('page_limit')->default(2)->comment('采集页数');
			$table->integer('current_page')->default(0)->comment('当前采集页数');
			$table->integer('task_code')->default(1)->comment('采集状态，1-就绪，2-采集中，200-已完成');
			$table->boolean('status')->default(1)->comment('状态,1-正常，2-无效');
			$table->integer('retries')->default(0)->comment('尝试次数');
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
        Schema::connection('mysql')->drop('collection_task');
    }
}
