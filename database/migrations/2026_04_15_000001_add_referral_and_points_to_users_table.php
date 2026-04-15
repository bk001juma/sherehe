<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('referral_code', 20)->unique()->nullable()->after('phone');
            $table->integer('loyalty_points')->default(0)->after('referral_code');
            $table->boolean('has_used_referral')->default(false)->after('loyalty_points');
            $table->unsignedBigInteger('referred_by')->nullable()->after('has_used_referral');
            
            $table->foreign('referred_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['referral_code', 'loyalty_points', 'has_used_referral', 'referred_by']);
        });
    }
};
