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
        Schema::create('not_valid_passports', function (Blueprint $table) {
            $table->id();
            $table->string('series', 4);
            $table->string('number', 6);
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['series', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('not_valid_passports');
    }
};
