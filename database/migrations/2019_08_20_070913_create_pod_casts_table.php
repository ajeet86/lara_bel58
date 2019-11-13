<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePodCastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pod_casts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('meta_title');
            $table->text('meta_desc');
            $table->string('feature_image');
            $table->string('title');
            $table->string('audio');
            $table->longText('content');
            $table->string('slug', 255);
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
        Schema::dropIfExists('pod_casts');
    }
}
