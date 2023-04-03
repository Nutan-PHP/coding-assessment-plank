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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body');
            $table->string('image');
            $table->date('date_occurrence');
            $table->enum('visibility', ['0', '1'])->default('1')->comment('0 - Invisible, 1 - Visible');
            $table->bigInteger('timeline_id')->unsigned()->nullable();
            $table->timestamps();

            $table->index('timeline_id');
            $table->foreign('timeline_id')->references('id')->on('timelines');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
