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
        Schema::create('notification_s_m_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_notification_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('event_attendee_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('phone');
            $table->longText('sms');
            $table->integer('characters');
            $table->integer('used_messages')->default(0);
            $table->string('status')->default('pending');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_s_m_s');
    }
};
