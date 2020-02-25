<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCollectionRuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('collection_rule', function(Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('title')->default('')->comment('标题');
            $table->string('host', 64)->default('')->comment('域名');
            $table->text('rule_json', 65535)->comment('采集链接');
            $table->boolean('status')->default(1)->comment('状态,1-正常，2-无效');
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
        Schema::connection('mysql')->drop('collection_rule');
    }
}
