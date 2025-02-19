<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideoReportDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_report_data', function (Blueprint $table) {
            $table->id();
            $table->integer('report_id')->nullable();
            $table->string('file_name')->nullable();
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
        Schema::dropIfExists('video_report_data');
    }
}
