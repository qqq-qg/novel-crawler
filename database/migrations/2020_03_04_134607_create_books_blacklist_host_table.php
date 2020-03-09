<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBooksBlacklistHostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql')->create('books_host_blacklist', function (Blueprint $table) {
            $table->integer('id', true)->unsigned();
            $table->string('host', 64)->default('')->unique()->comment('域名');
            $table->boolean('status')->default(1)->comment('状态,1-正常，2-无效');
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
        Schema::dropIfExists('books_host_blacklist');
    }
}
