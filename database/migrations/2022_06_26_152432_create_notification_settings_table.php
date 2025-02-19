<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->enum('is_likes',array(0,1))->default(1)->comment('0 = OFF || 1 = ON');
            $table->enum('is_comments',array(0,1))->default(1)->comment('0 = OFF || 1 = ON');
            $table->enum('is_new_followers',array(0,1))->default(1)->comment('0 = OFF || 1 = ON');
            $table->enum('is_videos_from_follow',array(0,1))->default(1)->comment('0 = OFF || 1 = ON');
            $table->enum('is_livestreams_from_follow',array(0,1))->default(1)->comment('0 = OFF || 1 = ON');
            $table->enum('is_recommended_broadcasts',array(0,1))->default(1)->comment('0 = OFF || 1 = ON');
            $table->enum('is_customized_updates',array(0,1))->default(1)->comment('0 = OFF || 1 = ON');
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
        Schema::dropIfExists('notification_settings');
    }
}
