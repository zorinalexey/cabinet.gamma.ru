<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('votings', function (Blueprint $table) {
            $table->text('type_transaction')->change();
            $table->text('parties_transaction')->change();
            $table->text('subject_transaction')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votings', function (Blueprint $table) {
            $table->string('type_transaction', 300)->change();
            $table->string('parties_transaction', 300)->change();
            $table->string('subject_transaction', 300)->change();
        });
    }
};
