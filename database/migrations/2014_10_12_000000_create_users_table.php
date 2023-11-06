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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->string('esia_id')->nullable();
            $table->string('email')->nullable();
            $table->string('code')->nullable();
            $table->integer('role')->default(1);
            $table->string('lastname');
            $table->string('name');
            $table->string('patronymic')->nullable();
            $table->string('gender')->default('не указан');
            $table->string('qualification_text')->default('Не квалифицированный инвестор');
            $table->integer('qualification_value')->default(1);
            $table->dateTime('birth_date')->default(null);
            $table->text('birth_place');
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
