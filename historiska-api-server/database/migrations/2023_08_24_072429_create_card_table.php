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
        Schema::create(
            'card', function (Blueprint $table) {
                $table->id();
                $table->string('name', 64);
                $table->text('description', 8192);
                $table->float('rarity');
                $table->integer('code');
                $table->string('birth', 32)->nullable();
                $table->string('death', 32)->nullable();
                $table->string('img_path', 127);

                $table->unsignedBigInteger('category');
                $table->unsignedBigInteger('country');

                $table->foreign('category')->references('id')->on('category');
                $table->foreign('country')->references('id')->on('country');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card');
    }
};
