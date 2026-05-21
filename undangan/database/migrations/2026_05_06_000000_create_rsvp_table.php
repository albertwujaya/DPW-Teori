<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rsvp', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('slug', 128)->unique();
            $table->enum('kehadiran', ['hadir', 'tidak', 'pending'])->default('pending');
            $table->unsignedTinyInteger('jumlah_tamu')->default(1);
            $table->text('ucapan')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rsvp');
    }
};
