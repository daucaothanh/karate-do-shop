<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shift_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('work_date');
            $table->foreignId('current_shift_id')->nullable()->constrained('work_shifts')->onDelete('set null');
            $table->foreignId('requested_shift_id')->constrained('work_shifts')->onDelete('cascade');
            $table->text('reason');
            $table->string('status')->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('admin_note')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'work_date', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('shift_change_requests');
    }
};
