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
        Schema::create('vocabulary_results', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('percent');
            $table->integer('correct');
            $table->integer('total');
            $table->string('date')->nullable();
            $table->json('incorrect')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocabulary_results');
    }
};
