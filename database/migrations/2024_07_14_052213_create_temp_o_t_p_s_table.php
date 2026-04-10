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
        Schema::create('temp_o_t_p_s', function (Blueprint $table) {
            $table->id();
            $table->string('phone');
            $table->integer('otp');
            $table->dateTime('otp_expires_at')->nullable();
            $table->string('otp_session')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_o_t_p_s');
    }
};
