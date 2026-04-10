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
        Schema::create('event_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('scanned_by')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('event_attendee_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('img');
            $table->string('code');
            $table->string('qr_code');
            $table->string('uid')->unique();
            $table->boolean('attended')->default(false);
            $table->dateTime('scanned_at')->nullable();
            $table->string('scanned_ip_address')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_cards');
    }
};
