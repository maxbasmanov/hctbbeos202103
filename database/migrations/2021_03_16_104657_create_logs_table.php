<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('logs', function (Blueprint $table) {
			$table->id();
			$table->integer('method');
			$table->integer('status');
			$table->text('host');
            $table->integer('group_id');
			$table->uuid('client_id');
			$table->text('url');
			$table->text('request');
			$table->text('response');
			$table->datetime('created_at');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
