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
        Schema::create('funds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('desc');
            $table->text('policy');
            $table->text('rules');
            $table->integer('omitted_min_percent')->default(0);
            $table->boolean('status')->default(true);
            $table->text('access_users')->nullable();
            $table->string('qualification_text')->default('Для не квалифицированных инвесторов');
            $table->integer('qualification_value')->default(1);
            $table->text('destiny')->nullable();
            $table->integer('current_count_pif');
            $table->integer('last_count_pif')->default(0);
            $table->float('current_cost_one_pif', 12, 2)->default(0);
            $table->float('last_cost_one_pif', 12, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funds');
    }
};
