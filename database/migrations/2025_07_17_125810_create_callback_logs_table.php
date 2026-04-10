<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('callback_logs', function (Blueprint $table) {
            $table->id();
            $table->string('source')->nullable(); // Chanzo cha callback, mfano 'M-Pesa', 'TigoPesa'
            $table->text('payload'); // Data yote iliyorudishwa na system
            $table->string('status')->nullable(); // status ya transaction
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('callback_logs');
    }
};
