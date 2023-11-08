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
        Schema::create('omitted_protocols', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('omitted_id');
            $table->string('name', 255);
            $table->string('docx', 255);
            $table->string('pdf', 255);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('omitted_protocols');
    }
};
