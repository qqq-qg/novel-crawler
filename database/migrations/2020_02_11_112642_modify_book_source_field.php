<?php

use Illuminate\Database\Migrations\Migration;

class ModifyBookSourceField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("alter table `books` change column `source` `rule_id` int(11) not null default 0 comment '规则ID'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("alter table `books` change column `rule_id` `source` varchar(255) not null default '' comment '来源'");
    }
}
