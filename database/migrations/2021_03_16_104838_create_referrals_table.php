<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id');
            $table->integer('invited_id');
            $table->integer('referral_id');
            $table->string('referral_type')->nullable();
			$table->string('referral_hash')->nullable();
			$table->string('event_type_verbose')->nullable();
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
        Schema::dropIfExists('referrals');
    }
}
