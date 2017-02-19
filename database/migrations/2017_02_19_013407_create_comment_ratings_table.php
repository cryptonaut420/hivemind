<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentRatingsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
	public function up()
	{
		Schema::create('comment_ratings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->index('user_id');              
			$table->integer('comment_id')->unsigned();
			$table->foreign('comment_id')->references('id')->on('post_comments')->onDelete('cascade');
			$table->index('comment_id');
            $table->tinyInteger('grade');
            $table->integer('score')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('comment_ratings');
	}
}
