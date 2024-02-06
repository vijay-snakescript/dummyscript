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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id')->index()->unsigned();
            $table->tinyInteger('featured')->index()->default(0)->nullable();
            $table->string('status')->index()->default('draft');
            $table->boolean('comments_status')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create(
            'post_translations',
            function (Blueprint $table) {
                $table->id();
                $table->string('locale')->index();
                $table->unsignedBigInteger('post_id');
                $table->string('slug')->index();
                $table->string('title', 150)->index();
                $table->longText('contents')->nullable();
                $table->string('meta_title')->nullable();
                $table->string('meta_description', 500)->nullable();
                $table->string('og_title')->nullable();
                $table->string('og_description', 500)->nullable();
                $table->string('excerpt', 500)->nullable();
                $table->timestamps();

                $table->unique(['post_id', 'locale']);
                $table->index(['post_id', 'locale', 'slug']);
                $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_translations');
    }
};
