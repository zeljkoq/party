<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateSongPartyTable
 */
class CreateSongPartyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('song_party', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('song_id');
            $table->foreign('song_id')->references('id')->on('songs')->onDelete('cascade');
            $table->unsignedInteger('party_id');
            $table->foreign('party_id')->references('id')->on('parties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('song_party');
    }
}
