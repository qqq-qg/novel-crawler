<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifyBooksAddUpdateStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("alter table `books` add column `update_status` enum('LOADING','FINISHED') not null default 'LOADING' comment '更新状态' after `hits`");

        DB::statement("alter table `books_chapter` add column `is_success` tinyint(1) not null default '0' comment '采集状态' after `from_hash`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("alter table `books` drop column `update_status`");

        DB::statement("alter table `books_chapter` drop column `is_success`");
    }
}
