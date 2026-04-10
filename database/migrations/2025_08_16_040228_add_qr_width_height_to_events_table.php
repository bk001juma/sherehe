<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->integer('qr_width')->default(300);
            $table->integer('qr_height')->default(300);
            $table->integer('qr_code_font_size')->default(36); // size ya qr_otp_code
            $table->integer('card_type_font_size')->default(36); // size ya cardType
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'qr_width')) {
                $table->dropColumn('qr_width');
            }
            if (Schema::hasColumn('events', 'qr_height')) {
                $table->dropColumn('qr_height');
            }
            if (Schema::hasColumn('events', 'qr_code_font_size')) {
                $table->dropColumn('qr_code_font_size');
            }
            if (Schema::hasColumn('events', 'card_type_font_size')) {
                $table->dropColumn('card_type_font_size');
            }
        });
    }
};
