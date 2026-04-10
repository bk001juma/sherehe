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
        Schema::create('attendee_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_attendee_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('amount');
            $table->integer('paid')->nullable();
            $table->integer('balance')->nullable();
            $table->string('status')->default('paid');
            $table->string('method');
            $table->string('transaction_id');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendee_payments');
    }
};
