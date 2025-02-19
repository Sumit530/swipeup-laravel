<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('song_id')->nullable();
            $table->longText('description')->nullable();
            $table->integer('is_view')->default(1)->comment('1 = Public || 2 = Friends || 3 = Private');
            $table->integer('is_allow_comments')->default(1)->comment('0 = No || 1 = Yes');
            $table->string('mention_ids')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('file_name')->nullable();
            $table->softDeletes();
            $table->enum('status',array(0,1))->default(1)->comment('0 = INACTIVE || 1 = ACTIVE');
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
        Schema::dropIfExists('videos');
    }
}
