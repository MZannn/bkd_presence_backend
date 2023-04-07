<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('report_change_device', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 30);
            $table->foreign('employee_id')->references('nip')->on('employees')->onDelete('cascade');
            $table->foreignId('office_id')->constrained('offices')->onDelete('cascade');
            $table->string('status')->default('PENDING');
            $table->text('reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_change_device');
    }
};