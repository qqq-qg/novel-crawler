<?php

use Illuminate\Database\Migrations\Migration;

class AddFieldToBooksFetchTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('alter table `books_chapter` add column `fetch_times` int(11) NOT NULL DEFAULT 10 comment "获取次数" after `title`');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        DB::statement('alter table `books` drop column `fetch_times`');
    }
}
