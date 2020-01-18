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
        DB::statement("alter table `books` add column `update_statis` enum('LOADING','FINISHED') not null default 'LOADING' comment '更新状态' after `hits`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("alter table `books` drop column `update_statis`");
    }
}
