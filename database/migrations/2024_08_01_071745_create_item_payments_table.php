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
        Schema::create('item_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_item_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('amount');
            $table->string('method');
            $table->string('status')->default('paid');
            $table->string('transaction_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_payments');
    }
};
