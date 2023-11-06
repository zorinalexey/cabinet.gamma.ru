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
        Schema::create('user_ruble_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('bank_name');
            $table->string('bank_country');
            $table->string('bank_city');
            $table->string('bic');
            $table->string('tin');
            $table->string('cor_account');
            $table->string('payment_account');
            $table->string('payment_recipient');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_ruble_accounts');
    }
};
