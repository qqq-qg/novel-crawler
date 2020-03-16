<?php

use Illuminate\Database\Migrations\Migration;

class ModifyBooksTableCategory extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    DB::statement('update `books` set category = "0";');
    DB::statement('alter table `books` change `category` `cate_id` int(11) NOT NULL DEFAULT 0 comment "分类ID";');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    DB::statement('alter table `books` change `cate_id` `category` varchar(255) NOT NULL DEFAULT "" comment "分类";');
  }
}
