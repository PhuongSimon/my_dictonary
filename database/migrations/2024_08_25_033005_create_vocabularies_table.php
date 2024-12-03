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
        Schema::create('vocabularies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('word');
            $table->enum('word_type', ['n', 'v', 'adj', 'adv', 'pre', 'phr'])
                  ->comment('n: noun, v: verb, adj: adjective, adv: adverb, pre: preposition, phr: phrase');
            $table->string('meaning');
            $table->string('ipa')->nullable();
            $table->text('example')->nullable();
            $table->string('date');
            $table->integer('category_id')->nullable();
            $table->integer('sub_category_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vocabularies');
    }
};
