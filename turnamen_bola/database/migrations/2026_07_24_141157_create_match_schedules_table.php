<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('age_category_id')->constrained('age_categories');
            $table->foreignId('home_team_id')->constrained('teams');
            $table->foreignId('away_team_id')->constrained('teams');
            $table->enum('round', ['penyisihan', '8besar', 'semifinal', 'final', 'perebutan_juara3']);
            $table->string('group_name')->nullable(); // Grup A, Grup B, dst
            $table->date('match_date');
            $table->time('match_time');
            $table->string('location'); // nama stadion/lapangan
            $table->integer('home_score')->nullable();
            $table->integer('away_score')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'finished', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_schedules');
    }
};
