<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdminMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('admin_menus', function(Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('pid')->unsigned()->comment('父ID');
            $table->string('name')->comment('菜单名称');
            $table->string('prefix')->comment('路由前缀');
            $table->string('route')->comment('详细路由');
            $table->string('ico')->comment('图标名称');
            $table->smallInteger('listorder')->unsigned()->comment('排序');
            $table->boolean('items')->comment('子分类数量');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->drop('admin_menus');
    }
}
