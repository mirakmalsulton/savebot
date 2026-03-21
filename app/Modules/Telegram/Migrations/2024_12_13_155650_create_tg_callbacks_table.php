<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('_tg_buttons', function (Blueprint $table) {
            $table->string('key')->unique();
            $table->string('text');
            $table->string('handler');
            $table->string('method');
            $table->json('params');
        });
    }

    public function down()
    {
        Schema::dropIfExists('_tg_buttons');
    }
};
