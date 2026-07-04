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
        Schema::create('photo_albume', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en');
            $table->text('description');
            $table->text('description_en');
            $table->dateTime('date_create');
            $table->string('folder');
            $table->string('cover')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photo_albume');
    }
};