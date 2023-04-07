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
        Schema::create('employees', function (Blueprint $table) {
            $table->string('nip',30)->primary()->unique();
            $table->string('name');
            $table->string('password');
            $table->string('position');
            $table->string('phone_number')->nullable();
            $table->text('profile_photo_path')->nullable();
            $table->string('device_id')->nullable();
            $table->foreignId('office_id')->constrained('offices')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
