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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bulk_s_m_s_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('event_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->double('amount');
            $table->string('phone');
            $table->string('status')->default('pending');

            $table->string('order_id');
            $table->string('response_id')->default('pending');
            $table->string('response_message')->default('pending');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
