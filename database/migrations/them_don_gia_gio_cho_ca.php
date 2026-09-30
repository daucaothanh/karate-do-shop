<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('staff_shift_rates', function (Blueprint $table) {
            $table->unsignedInteger('hourly_amount')->nullable();
        });
    }

    public function down()
    {
        Schema::table('staff_shift_rates', function (Blueprint $table) {
            $table->dropColumn('hourly_amount');
        });
    }
};
