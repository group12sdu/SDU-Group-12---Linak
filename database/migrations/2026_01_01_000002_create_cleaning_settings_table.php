<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Single-row table: the one cleaning preset + whether cleaning mode is on.
        Schema::create('cleaning_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('enabled')->default(false);       // start automatically every day
            $table->string('time', 5)->default('18:00');      // HH:MM
            $table->boolean('use_max')->default(true);        // raise to max height
            $table->unsignedSmallInteger('height')->default(125);
            $table->boolean('active')->default(false);        // cleaning mode currently on
            $table->date('last_run_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cleaning_settings');
    }
};
