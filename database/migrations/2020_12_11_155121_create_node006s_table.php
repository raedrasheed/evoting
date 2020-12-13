<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNode006sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node006s', function (Blueprint $table) {
            $table->bigIncrements('id');
			$table->bigInteger('block_size');
			$table->json('block_header');			
			$table->json('vote');
			$table->string('block_hash');			
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
        Schema::dropIfExists('node006s');
    }
}
