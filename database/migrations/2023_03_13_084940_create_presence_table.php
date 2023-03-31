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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id',30);
            $table->foreign('employee_id')->references('nip')->on('employees')->onDelete('cascade');
            $table->foreignId('office_id')->constrained('offices')->onDelete('cascade');
            $table->time('attendance_clock')->nullable();
            $table->time('attendance_clock_out')->nullable();
            $table->date('presence_date');
            $table->string('attendance_entry_status')->nullable();
            $table->string('attendance_exit_status')->nullable();
            $table->string('entry_position')->nullable();
            $table->float('entry_distance')->nullable();
            $table->string('exit_position')->nullable();
            $table->float('exit_distance')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presence');
    }
};
