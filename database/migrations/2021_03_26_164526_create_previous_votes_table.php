<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreviousVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('previous_votes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
			$table->timestamp('start_date')->nullable();
			$table->timestamp('end_date')->nullable();
			$table->integer('all_voters')->nullable();
			$table->integer('total_votes')->nullable();
			$table->integer('correct_votes')->nullable();
			$table->integer('incorrect_votes')->nullable();
			$table->float('voting_precentage')->nullable();
			$table->integer('blank_votes')->nullable();
			$table->json('nominees_votes_JSON')->nullable();
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
        Schema::dropIfExists('previous_votes');
    }
}
