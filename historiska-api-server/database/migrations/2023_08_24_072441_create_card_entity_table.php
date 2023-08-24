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
            'card_entity', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('card');
                $table->unsignedBigInteger('owner');
                $table->string('share_code', 64)->nullable()->unique();
                $table->boolean('is_shared')->default(false);
                $table->boolean('is_gold')->default(false);

                $table->foreign('card')->references('id')->on('card');
                $table->foreign('owner')->references('id')->on('user');
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_entity');
    }
};
