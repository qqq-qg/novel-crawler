<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('managers', function(Blueprint $table) {
			$table->increments('id');
			$table->string('username', 50)->comment('用户名');
			$table->string('password', 60)->comment('密码');
			$table->string('truename', 50)->comment('真实姓名');
			$table->string('email', 100)->comment('邮箱号码');
			$table->string('salt', 4)->comment('校验码');
			$table->string('lastip')->comment('上一次登录ip');
			$table->integer('lasttime')->unsigned()->comment('最后登录时间');
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
		});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql')->drop('managers');
    }
}
