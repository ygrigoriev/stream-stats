<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StreamTags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stream_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('stream_id');
            $table->string('tag_id');

            $table->unique(['stream_id', 'tag_id']);

            $table->foreign('stream_id')
                ->references('id')->on('streams')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stream_tags');
    }
}
