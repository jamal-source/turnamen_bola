<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('age_category_id')->constrained('age_categories');
            $table->string('name');
            $table->string('nik', 20)->nullable();
            $table->date('birth_date');
            $table->string('birth_place')->nullable();
            $table->integer('jersey_number')->nullable();
            $table->string('position')->nullable(); // Kiper, Bek, Gelandang, Penyerang
            $table->string('registration_number')->nullable()->unique(); // auto-generated
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
