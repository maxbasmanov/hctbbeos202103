<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('prices', function (Blueprint $table) {
			$table->unique(['group_id', 'client_id', 'event_id']);
			$table->id();
            $table->integer('group_id');
			$table->uuid('client_id');
			$table->integer('event_id');
			$table->integer('limit');
			$table->decimal('amount', 65, 4);
            $table->decimal('referral', 65, 4);
            $table->integer('revoked')->default(0);
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
        Schema::dropIfExists('prices');
    }
}
