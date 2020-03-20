<?php

use Illuminate\Database\Migrations\Migration;

class AddFieldCollectionTask extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    DB::statement('alter table `collection_task` add column `cate_id` int(11) NOT NULL DEFAULT 0 comment "分类ID" after `title`;');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {

    DB::statement('alter table `collection_task` drop column `cate_id`;');
  }
}
