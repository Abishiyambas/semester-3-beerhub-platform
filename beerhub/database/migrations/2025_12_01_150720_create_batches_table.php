<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('beer_type_id');
            $table->integer('speed');
            $table->integer('quantity');
            $table->integer('defective');
            $table->float('avg_temperature');
            $table->float('avg_humidity');
            $table->float('avg_vibration');
            $table->foreignId('preset_id')->nullable()->constrained('presets');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('beer_type_id')
                ->references('id')
                ->on('beer_types')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
