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
        Schema::create('event_design_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')
                ->constrained('events')
                ->onDelete('cascade');
            $table->string('double_card')->nullable();
            $table->string('single_card')->nullable();
            $table->string('complementary_card')->nullable();
            $table->string('couple_ticket')->after('complementary_card')->nullable();
            $table->string('single_ticket')->after('couple_ticket')->nullable();
            $table->string('vvip_card')->nullable(); // Adding VVIP card column
            $table->string('vip_card')->nullable(); // Adding VIP card column
            $table->string('regular_card')->nullable(); // Adding Regular card column
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_design_cards');
    }
};
