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
        Schema::create('votings', function (Blueprint $table) {
            $table->id();
            $table->integer('fund_id');
            $table->integer('omitted_id');
            $table->string('type_transaction', 300);
            $table->string('parties_transaction', 300);
            $table->string('subject_transaction', 300);
            $table->float('cost_transaction', 20, 2);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voitings');
    }
};
