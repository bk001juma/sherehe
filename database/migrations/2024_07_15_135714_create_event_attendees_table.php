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
        Schema::create('event_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('full_name');
            $table->string('phone');
            $table->integer('amount')->default(0);
            $table->integer('paid')->default(0);
            $table->integer('balance')->nullable();
            $table->string('status')->default('attendee');
            $table->dateTime('attended_at')->nullable();
            $table->boolean('is_committee_member')->default(0);
            $table->boolean('is_attending')->default(0);
            $table->string('qr_otp_code')->nullable()->unique();
            $table->boolean('card_received')->default(false);
            $table->integer('checkin_count')->default(0);
            $table->string('attending_response')->default('yes');
            $table->string('table_number')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_attendees');
    }
};
