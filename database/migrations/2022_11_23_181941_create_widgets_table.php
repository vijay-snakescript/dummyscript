<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('title');
            $table->string('description')->nullable();
            $table->tinyInteger('order')->nullable()->default(0)->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('widgets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('widget_area_id');
            $table->string('name');
            $table->string('title')->nullable();
            $table->tinyInteger('order')->nullable()->default(0)->index();
            $table->tinyInteger('status')->default(1)->index();
            $table->tinyInteger('web')->default(1)->index();
            $table->tinyInteger('mobile')->default(1)->index();
            $table->text('settings')->nullable();
            $table->tinyInteger('ajax')->nullable()->default(1)->index();
            $table->timestamps();

            $table->foreign('widget_area_id')->references('id')->on('widget_areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('widgets');
        Schema::dropIfExists('widget_areas');
    }
};
