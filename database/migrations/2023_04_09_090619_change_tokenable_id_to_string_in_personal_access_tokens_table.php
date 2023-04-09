<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->string('tokenable_id')->change();
        });

        DB::table('personal_access_tokens')
            ->orderBy('id')
            ->chunk(100, function ($tokens) {
                foreach ($tokens as $token) {
                    DB::table('personal_access_tokens')
                        ->where('id', $token->id)
                        ->update(['tokenable_id' => (string) $token->tokenable_id]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            $table->unsignedBigInteger('tokenable_id')->change();
        });
    }
};