<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->string('label'); // e.g. "A", "B"
            $table->string('text');  // text shown to user, e.g. "210"
            $table->integer('points')->default(0); // points for this option (can be non-zero even if wrong)
            $table->boolean('is_correct')->default(false); // for normal MCQ
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('options');
    }
}


