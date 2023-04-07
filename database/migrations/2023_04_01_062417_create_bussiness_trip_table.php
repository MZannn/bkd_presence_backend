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
        Schema::create('bussiness_trip', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id',30);
            $table->foreign('employee_id')->references('nip')->on('employees')->onDelete('cascade');
            $table->foreignId('office_id')->constrained('offices')->onDelete('cascade');
            $table->foreignId('presence_id')->constrained('presences')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('file');
            $table->string('status')->default('PENDING');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bussiness_trip');
    }
};
