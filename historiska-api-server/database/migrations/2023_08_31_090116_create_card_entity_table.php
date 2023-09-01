<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('card_entity', function (Blueprint $table) {
            $table->dropForeign(['card']);
            $table->dropForeign(['owner']);
            $table->foreign('card')->references('id')->on('card')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('owner')->references('id')->on('user')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('card_entity', function (Blueprint $table) {
            Schema::dropIfExists('card_entity');
        });
    }
};
