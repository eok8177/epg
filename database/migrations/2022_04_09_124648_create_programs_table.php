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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('chanel_id');

            $table->unsignedInteger('start')->nullable();
            $table->unsignedInteger('stop')->nullable();

            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();

            $table->index('chanel_id');
            $table->index('start');
            $table->index('stop');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('programs');
    }
};
