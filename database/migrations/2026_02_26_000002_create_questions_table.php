<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_id');
            $table->text('text');
            $table->unsignedInteger('time_limit_seconds')->default(60);
            $table->boolean('allow_skip')->default(true);
            $table->boolean('show_skip_button')->default(true);
            $table->boolean('allow_back')->default(false);
            $table->unsignedInteger('base_points')->default(0);
            $table->timestamps();

            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}


