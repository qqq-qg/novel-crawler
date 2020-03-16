<?php

use Illuminate\Database\Migrations\Migration;

class ModifySettingTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    DB::statement('ALTER TABLE `mars`.`settings`   
  ADD COLUMN `id` INT NOT NULL AUTO_INCREMENT FIRST,
  ADD KEY(`id`), 
  DROP PRIMARY KEY,
  CHANGE `item` `key` varchar(255) not null;');
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    DB::statement('ALTER TABLE `mars`.`settings`   
  DROP COLUMN `id`, 
  DROP INDEX `id`,
  CHANGE `key` `item` varchar(255) not null;');
  }
}
