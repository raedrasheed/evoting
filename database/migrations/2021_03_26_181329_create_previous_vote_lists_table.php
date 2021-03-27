<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreviousVoteListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('previous_vote_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->integer('previous_vote_id');
			$table->integer('nominee_list_id');
			$table->string('name');
			$table->string('photo');
			$table->string('description')->nullable();
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
        Schema::dropIfExists('previous_vote_lists');
    }
}
