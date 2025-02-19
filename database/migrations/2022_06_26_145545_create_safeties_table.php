<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSafetiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('safeties', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->enum('is_allow_comments',array(0,1))->default(1)->comment('0 = OFF || 1 = ON');
            $table->enum('is_allow_duets',array(0,1))->default(1)->comment('0 = OFF || 1 = ON');
            $table->enum('is_allow_messages',array(0,1))->default(1)->comment('0 = OFF || 1 = ON');
            $table->enum('is_allow_downloads',array(0,1))->default(1)->comment('0 = OFF || 1 = ON');
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
        Schema::dropIfExists('safeties');
    }
}
