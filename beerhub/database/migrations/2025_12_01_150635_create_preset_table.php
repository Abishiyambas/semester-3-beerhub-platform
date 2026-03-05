<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presets', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->unsignedTinyInteger('beer_type_id');

            $table->integer('quantity');
            $table->integer('speed');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('beer_type_id')
                ->references('id')
                ->on('beer_types')
                ->onDelete('restrict');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presets');
    }
};
