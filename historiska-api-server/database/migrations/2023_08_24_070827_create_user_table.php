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
            'user', function (Blueprint $table) {
                $table->id();
                $table->boolean('is_admin')->default(false);
                $table->string('username', 255)->unique();
                $table->string('email', 255)->unique();
                $table->string('password', 255);
                $table->text('token', 256)->nullable()->unique();
                $table->timestamp('token_issued_at')->nullable();
                $table->char('activation_code', 16)->nullable()->unique();
                $table->timestamp('activation_code_sent_at')->nullable();
                $table->boolean('is_activated')->default(false);
                $table->text('recovery_code', 256)->nullable()->unique();
                $table->timestamp('recovery_code_sent_at')->nullable();
                $table->timestamp('last_pack_openend_at')->nullable();
            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
