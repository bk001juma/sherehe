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
        Schema::table('event_packages', function (Blueprint $table) {
            $table->string('optional_field_4')->nullable()->after('optional_field_3');
            $table->string('optional_field_5')->nullable()->after('optional_field_4');
            $table->string('optional_field_6')->nullable()->after('optional_field_5');
            $table->string('optional_field_7')->nullable()->after('optional_field_6');
            $table->string('optional_field_8')->nullable()->after('optional_field_7');
            $table->string('optional_field_9')->nullable()->after('optional_field_8');
            $table->string('optional_field_10')->nullable()->after('optional_field_9');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('event_packages', function (Blueprint $table) {
            $table->dropColumn([
                'optional_field_4',
                'optional_field_5',
                'optional_field_6',
                'optional_field_7',
                'optional_field_8',
                'optional_field_9',
                'optional_field_10',
            ]);
        });
    }
};
