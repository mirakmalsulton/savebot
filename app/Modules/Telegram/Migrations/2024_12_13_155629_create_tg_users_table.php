<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('_tg_users', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->unique();
            $table->string('name')->nullable();
            $table->json('state')->nullable();
            $table->string('lang')->nullable();
            $table->integer('balance');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('_tg_users');
    }
};
