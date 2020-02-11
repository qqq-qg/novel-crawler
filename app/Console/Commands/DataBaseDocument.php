<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DataBaseDocument extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database {--name=}';

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->option('name');
        $nextLine = PHP_EOL;
        echo $nextLine;
        $dbName = \DB::getDatabaseName();
        if (!empty($name)) {
            $tables = \DB::select("SELECT table_name ,TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES
                WHERE table_type='base table' and table_schema = '{$dbName}' and table_name = '{$name}' order by table_name asc");
        } else {
            $tables = \DB::select("SELECT table_name ,TABLE_COMMENT FROM INFORMATION_SCHEMA.TABLES
                WHERE table_type='base table' and table_schema = '{$dbName}' order by table_name asc");
        }
        foreach ($tables as $table) {
            echo $nextLine;
            echo "{$table->TABLE_COMMENT} ({$table->table_name})" . $nextLine;

            $fields = \DB::select("SHOW FULL COLUMNS FROM {$table->table_name}");
            $property = [];
            foreach ($fields as $row) {
                $type = $row->Type;
                $type_text = 'string';
                if (strpos($type, 'tinyint') !== false
                    || strpos($type, 'smallint') !== false
                    || strpos($type, 'mediumint') !== false
                    || strpos($type, 'int') !== false
                    || strpos($type, 'bigint') !== false) {
                    $type_text = 'integer';
                }
                if (strpos($type, 'float') !== false
                    || strpos($type, 'double') !== false
                    || strpos($type, 'real') !== false
                    || strpos($type, 'decimal') !== false) {
                    $type_text = 'numeric';
                }
                if (strpos($type, 'datetime')) {
                    $type_text = 'date';
                }
                $property[] = rtrim(" * @property {$type_text} {$row->Field} {$row->Comment}");
            }
            echo join($nextLine, $property);
        }
        die;
    }
}
