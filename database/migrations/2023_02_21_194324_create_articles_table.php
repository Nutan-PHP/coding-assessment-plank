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
        Schema::create('articles', function (Blueprint $table) {
            $table->longText('body');
            $table->timestamps();
            $table->longText('title');
            $table->char('user_id', 2)->nullable();
            $table->id();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
