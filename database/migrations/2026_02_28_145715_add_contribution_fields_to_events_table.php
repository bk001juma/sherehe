<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->date('contribution_deadline')->nullable()->after('venue');
            $table->text('payment_numbers')->nullable()->after('contribution_deadline');
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['contribution_deadline', 'payment_numbers']);
        });
    }
};
