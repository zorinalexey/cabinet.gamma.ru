<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_currency_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('currency');
            $table->string('cor_bank', 300);
            $table->string('country_bank');
            $table->string('city_bank');
            $table->string('swift_bank');
            $table->string('account_number_cor_bank');
            $table->string('beneficiary_name_bank', 300);
            $table->string('beneficiary_country_bank');
            $table->string('beneficiary_city_bank');
            $table->string('account_beneficiary_bank');
            $table->string('tin_bank');
            $table->string('cor_account_bank', 300);
            $table->string('pay_beneficiary', 300);
            $table->softDeletes();
            $table->text('pay_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_currency_accounts');
    }
};
