<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModifyCollectionTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("alter table `collection_task` add column `current_page` int(11) not null default '0' comment '当前采集页数' after `page_limit`");
        DB::statement("alter table `collection_task` add column `retries` int(11) not null default '0' comment '尝试次数' after `status`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("alter table `collection_task` drop column `current_page`");
        DB::statement("alter table `collection_task` drop column `retries`");
    }
}
