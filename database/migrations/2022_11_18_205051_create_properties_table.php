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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(true);
            $table->string('type')->nullable();
            $table->string('field_type')->nullable();
            $table->string('prop_key')->nullable();
            $table->string('value')->nullable();
            $table->timestamps();
        });

        Schema::create('property_translations', function (Blueprint $table)
        {
            $table->bigIncrements('id');
            $table->string('locale')->index();
            $table->unsignedBigInteger('property_id');
            $table->string('name', 100)->index('name');
            $table->mediumText('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
        Schema::dropIfExists('property_translations');
    }
};
