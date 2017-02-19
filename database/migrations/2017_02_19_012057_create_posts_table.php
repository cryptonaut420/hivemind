<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *  
     * @return void
     */
	public function up()
	{
		Schema::create('posts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->index('user_id');
			$table->string('title');
			$table->string('slug');
            $table->index('slug');
            $table->string('post_link');
            $table->index('post_link');
            $table->longText('self_text')->nullable();
			$table->tinyInteger('state')->default(0); //0 = live, 1 = archived, 2 = deleted
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('posts');
	}
}
