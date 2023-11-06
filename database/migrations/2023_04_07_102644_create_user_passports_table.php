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
        Schema::create('user_passports', function (Blueprint $table) {
            $table->id();
            $table->string('series');
            $table->string('number');
            $table->integer('code')->default(21);
            $table->text('issued_by');
            $table->dateTime('when_issued');
            $table->string('division_code');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_passports');
    }
};
