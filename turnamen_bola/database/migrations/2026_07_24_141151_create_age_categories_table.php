<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('age_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. KU-10, KU-12
            $table->integer('max_birth_year'); // e.g. 2016
            $table->integer('min_birth_year')->nullable(); // optional
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('age_categories');
    }
};
