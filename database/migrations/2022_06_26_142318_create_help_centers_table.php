<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelpCentersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('help_centers', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->longText('details')->nullable();
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
        Schema::dropIfExists('help_centers');
    }
}
