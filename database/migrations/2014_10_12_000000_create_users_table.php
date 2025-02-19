<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('social_id')->nullable();
            $table->string('name')->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('country_code')->nullable();
            $table->string('page_name')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('otp')->nullable();
            $table->string('device_id')->nullable();
            $table->string('otp_expired')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->integer('language_id')->nullable();
            $table->enum('allow_find_me',array(0,1))->default(1)->comment('0 = OFF || 1 = ON');
            $table->enum('private_account',array(0,1))->default(0)->comment('0 = NO || 1 = YES');
            $table->enum('is_vip',array(0,1))->default(0)->comment('0 = NO || 1 = YES');
            $table->integer('type')->default(1)->comment('1 = Normal || 2 = Google || 3 = Facebook');
            $table->enum('status',array(0,1))->default(1)->comment('0 = INACTIVE || 1 = ACTIVE');
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}
