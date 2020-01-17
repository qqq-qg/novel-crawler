<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ModifyBooks extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    DB::statement("alter table `books` add column `category` varchar(255) not null default '' comment '分类' after `title`");
    DB::statement("alter table `books` change column `zhangjie` `last_chapter_title` varchar(255) not null default '' comment '最新章节'");
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    DB::statement("alter table `books` drop column `category`");
    DB::statement("alter table `books` change column `last_chapter_title` `zhangjie` varchar(255) not null default '' comment '最新章节'");
  }
}
