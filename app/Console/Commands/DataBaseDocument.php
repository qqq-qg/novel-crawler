<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DataBaseDocument extends Command {
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'command:database';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = '生成数据库字典文档';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  public function __construct() {
    parent::__construct();
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle() {
    $nextLine = PHP_EOL;
    echo "### 持续更新  最近一次更新时间 -- " . date('Ymd') . $nextLine;
    echo $nextLine;
    $dbName = \DB::getDatabaseName();
    $tables = \DB::select("SELECT table_name ,TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES WHERE table_type='base table' and table_schema = '{$dbName}' order by table_name asc");
    foreach ($tables as $table) {
      echo $nextLine;
      echo "|{$table->TABLE_COMMENT} ({$table->table_name})| 字段类型| 字段描述|" . $nextLine;
      echo "|:----|:---|:-----|" . $nextLine;

      $fields = \DB::select("SHOW FULL COLUMNS FROM {$table->table_name}");

      foreach ($fields as $field) {
        echo " | {$field->Field}| {$field->Type} | {$field->Comment}|" . $nextLine;
      }
    }
    die;
  }
}
