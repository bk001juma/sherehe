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
            $table->foreignId('user_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('event_package_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('event_category_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->string('event_name');
            $table->string('family_name')->nullable();
            $table->longText('description');
            $table->string('image');
            $table->string('location');
            $table->string('venue');
            $table->string('media_type')->nullable();
            $table->string('video_link')->nullable();
            $table->integer('initial_payment')->default(0);
            $table->integer('final_payment')->default(0);
            $table->integer('sms_balance')->default(0);
            $table->integer('card_balance')->default(0);
            $table->integer('whatsapp_balance')->default(0);
            $table->string('contact_phone_1');
            $table->string('contact_phone_2')->nullable();
            $table->string('status')->default('pending');
            $table->dateTime('event_date');
            $table->string('event_time')->nullable();
            $table->dateTime('event_end_date')->nullable();
            $table->string('mr_name')->nullable();
            $table->string('mrs_name')->nullable();
            $table->string('church_name')->nullable();
            $table->string('church_time')->nullable();
            $table->string('dress_code')->nullable();
            $table->string('maps_location')->nullable();
            $table->string('welcome_note')->nullable();
            $table->unsignedBigInteger('card_and_ticket_id')->nullable();
            $table->foreign('card_and_ticket_id')->references('id')->on('cards_and_tickets')->onDelete('set null');
            $table->decimal('top', 5, 2)->default(35.00)->after('status');
            $table->decimal('left', 5, 2)->default(50.00)->after('top');
            $table->string('font_size')->default('42px')->after('left');
            $table->string('color')->default('#000000')->after('font_size');
            $table->timestamps();
            $table->softDeletes();
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
