<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('staff_shift_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shift_id')->constrained('work_shifts')->onDelete('cascade');
            $table->date('month');
            $table->unsignedInteger('amount');
            $table->timestamps();
            $table->unique(['user_id', 'shift_id', 'month']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('staff_shift_rates');
    }
};
