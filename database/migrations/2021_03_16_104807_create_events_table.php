<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('events', function (Blueprint $table) {
			$table->unique(['group_id', 'event_id']);
			$table->id();
			$table->integer('group_id');
			$table->integer('event_id');
			$table->integer('primary')->default(0);
			$table->string('name');
			$table->integer('revoked')->default(0);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
