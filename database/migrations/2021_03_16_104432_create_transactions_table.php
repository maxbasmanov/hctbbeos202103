<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('transactions', function (Blueprint $table) {
			$table->id();
			$table->string('transaction_id')->nullable();
			$table->integer('group_id');
			$table->uuid('client_id');
			$table->integer('wallet_id');
			$table->integer('status');
			$table->string('uid')->nullable();
			$table->integer('event_type')->nullable();
			$table->string('event_detail')->nullable();
			$table->string('course_id')->nullable();
			$table->string('org')->nullable();
			$table->decimal('amount', 65, 4);
			$table->text('comment');
			$table->timestamps();
			$table->softDeletes();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
