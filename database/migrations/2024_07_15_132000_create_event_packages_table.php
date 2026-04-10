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
        Schema::create('event_packages', function (Blueprint $table) {
            $table->id();
            $table->string('level');
            $table->string('name');
            $table->string('desc');
            $table->string('icon');
            $table->integer('price');
            $table->integer('messages');
            $table->integer('digital_cards');
            $table->integer('attendees');
            $table->string('optional_field_1')->nullable();
            $table->string('optional_field_2')->nullable();
            $table->string('optional_field_3')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_packages');
    }
};
