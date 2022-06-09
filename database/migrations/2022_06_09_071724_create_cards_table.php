<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('game');
            $table->string('name');
            $table->text('description');
            $table->string('external_image_url', 2048);
            $table->string('internal_image_url', 2048)->nullable();
            $table->text('meta')->nullable();
            $table->timestamps();

            $table->unique(['game', 'name']);
        });
    }
};
