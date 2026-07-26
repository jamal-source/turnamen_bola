<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            // Status verifikasi: pending=menunggu, approved=lolos, rejected=ditolak, auto_approved=lolos otomatis
            $table->enum('status', ['pending', 'approved', 'rejected', 'auto_approved'])->default('pending');
            $table->boolean('age_valid')->default(false); // hasil cek umur otomatis
            $table->text('notes')->nullable(); // catatan penolakan/revisi
            $table->foreignId('verified_by')->nullable()->constrained('users'); // super admin user id
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('player_verifications');
    }
};
