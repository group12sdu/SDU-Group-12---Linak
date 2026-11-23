<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('desks', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('building');
            $table->unsignedSmallInteger('floor');
            $table->unsignedSmallInteger('room');
            $table->unsignedSmallInteger('desk_number'); // desk within the room
            $table->unsignedSmallInteger('height')->default(72); // cm
            $table->string('mode')->default('Sitting');          // Sitting | Standing | Custom | Cleaning
            $table->unsignedSmallInteger('previous_height')->nullable(); // restored after cleaning mode
            $table->string('previous_mode')->nullable();
            $table->timestamps();

            $table->unique(['building', 'floor', 'room', 'desk_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desks');
    }
};
