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
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->index();
            $table->string('class_name')->index();
            $table->string('icon_type')->default('class');
            $table->string('icon_class')->nullable();
            $table->tinyInteger('display')->default(0);
            $table->text('settings')->nullable();
            $table->boolean('status')->default(true)->index();
            $table->json('properties')->nullable();
            $table->timestamps();
        });

        Schema::create('tool_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('tool_id');
            $table->string('name');
            $table->string('description', 500)->nullable();
            $table->longText('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->timestamps();

            $table->unique(['tool_id', 'locale']);
            $table->index(['tool_id', 'locale']);
            $table->foreign('tool_id')->references('id')->on('tools')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tool_translations');
        Schema::dropIfExists('tools');
    }
};
