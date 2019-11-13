<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Enums\BookConditionType;

class CreateBooksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('books', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->unsignedBigInteger('cat_id');
            $table->string('isbn_no')->unique();
            $table->text('book_desc')->nullable();
            $table->float('org_price');
            $table->float('listing_price')->nullable();
            $table->string('book_condition')->nullable();
            $table->boolean('publish')->default('1');
            $table->boolean('availability')->default('1');
            $table->BigInteger('user_id')->default('0');
            $table->BigInteger('admin_id')->default('0');
            $table->timestamps();
            $table->foreign('cat_id')
                    ->references('id')
                    ->on('categories')
                    ->onDelete('cascade');
            /*$table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            $table->foreign('admin_id')
                    ->references('id')
                    ->on('admins')
                    ->onDelete('cascade');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('books');
    }

}
