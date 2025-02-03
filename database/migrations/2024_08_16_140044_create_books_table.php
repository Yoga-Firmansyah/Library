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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->integer('isbn');
            $table->string('title', 64);
            $table->integer('year');
            $table->foreignId('publisher_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('author_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('catalog_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('qty');
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
