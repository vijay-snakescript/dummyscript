<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->integer('parent')->index('parent')->nullable();
            $table->string('type')->index();
            $table->integer('order')->index('order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('category_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('category_id');
            $table->string('name', 100)->index('name');
            $table->string('slug', 100)->index('slug');
            $table->string('title', 250)->nullable();
            $table->longText('description')->nullable();
            $table->string('meta_title', 250)->nullable();
            $table->mediumText('meta_description')->nullable();
            $table->text('icon')->nullable();

            $table->index(['category_id', 'locale']);
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });

        Schema::create('catables', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->index();
            $table->morphs('catable');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('category_translations');
        Schema::dropIfExists('catables');
    }
};
