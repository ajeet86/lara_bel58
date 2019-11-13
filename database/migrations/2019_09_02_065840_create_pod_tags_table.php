<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePodTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pod_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pod_id')->unsigned();
            $table->unsignedBigInteger('tag_id')->unsigned();
            $table->foreign('pod_id')
                    ->references('id')
                    ->on('pod_casts')
                    ->onDelete('cascade');
            $table->foreign('tag_id')
                    ->references('id')
                    ->on('tags')
                    ->onDelete('cascade');
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
        Schema::dropIfExists('pod_tags');
    }
}
