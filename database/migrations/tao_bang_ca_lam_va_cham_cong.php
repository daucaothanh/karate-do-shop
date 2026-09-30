<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 1. Bảng Ca làm việc (Work Shifts)
        if (!Schema::hasTable('work_shifts')) {
            Schema::create('work_shifts', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // Ca Sáng, Ca Chiều, Ca Tối, Ca Hành Chính
                $table->time('start_time'); // 08:00
                $table->time('end_time'); // 12:00
                $table->string('description')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Seed default shifts for Karate-Do shop
            DB::table('work_shifts')->insert([
                [
                    'name' => 'Ca Sáng (08:00 - 12:00)',
                    'start_time' => '08:00:00',
                    'end_time' => '12:00:00',
                    'description' => 'Ca trực sáng: Tiếp nhận đơn mới, kiểm tra tồn kho võ phục, xác nhận đơn.',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Ca Chiều (13:00 - 17:30)',
                    'start_time' => '13:00:00',
                    'end_time' => '17:30:00',
                    'description' => 'Ca trực chiều: Đóng gói võ phục, đai, giao đơn cho bưu cục, tư vấn khách.',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'Ca Tối (18:00 - 22:00)',
                    'start_time' => '18:00:00',
                    'end_time' => '22:00:00',
                    'description' => 'Ca trực tối: Hỗ trợ đặt hàng online, giải đáp tư vấn võ sinh, tổng kết ca.',
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }

        // 2. Bảng Nhật ký làm việc / Chấm công / Ca trực (Staff Work Logs)
        if (!Schema::hasTable('staff_work_logs')) {
            Schema::create('staff_work_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('shift_id')->nullable()->constrained('work_shifts')->onDelete('set null');
                $table->string('shift_name')->nullable();
                $table->dateTime('login_at');
                $table->dateTime('check_in_at')->nullable();
                $table->dateTime('check_out_at')->nullable();
                $table->integer('total_minutes')->default(0); // Tổng số phút trực ca
                $table->integer('orders_handled_count')->default(0); // Số đơn hàng nhân viên đã xử lý trong ca
                $table->decimal('total_revenue_handled', 12, 2)->default(0); // Doanh số đơn xử lý
                $table->string('ip_address')->nullable();
                $table->text('note')->nullable();
                $table->string('status')->default('active'); // active, completed, cancelled
                $table->timestamps();
            });
        }

        // 3. Bổ sung trường ghi nhận nhân viên và ca làm việc vào bảng orders
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'handled_by')) {
                    $table->foreignId('handled_by')->nullable()->after('status')->constrained('users')->onDelete('set null');
                }
                if (!Schema::hasColumn('orders', 'shift_id')) {
                    $table->foreignId('shift_id')->nullable()->after('handled_by')->constrained('work_shifts')->onDelete('set null');
                }
                if (!Schema::hasColumn('orders', 'shift_name')) {
                    $table->string('shift_name')->nullable()->after('shift_id');
                }
                if (!Schema::hasColumn('orders', 'confirmed_at')) {
                    $table->dateTime('confirmed_at')->nullable()->after('shift_name');
                }
            });
        }

        // 4. Bổ sung trường ghi nhận người thao tác và ca trực vào order_status_histories
        if (Schema::hasTable('order_status_history')) {
            Schema::table('order_status_history', function (Blueprint $table) {
                if (!Schema::hasColumn('order_status_history', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('order_id')->constrained('users')->onDelete('set null');
                }
                if (!Schema::hasColumn('order_status_history', 'shift_name')) {
                    $table->string('shift_name')->nullable()->after('user_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'handled_by')) $table->dropColumn('handled_by');
                if (Schema::hasColumn('orders', 'shift_id')) $table->dropColumn('shift_id');
                if (Schema::hasColumn('orders', 'shift_name')) $table->dropColumn('shift_name');
                if (Schema::hasColumn('orders', 'confirmed_at')) $table->dropColumn('confirmed_at');
            });
        }

        if (Schema::hasTable('order_status_history')) {
            Schema::table('order_status_history', function (Blueprint $table) {
                if (Schema::hasColumn('order_status_history', 'user_id')) $table->dropColumn('user_id');
                if (Schema::hasColumn('order_status_history', 'shift_name')) $table->dropColumn('shift_name');
            });
        }

        Schema::dropIfExists('staff_work_logs');
        Schema::dropIfExists('work_shifts');
    }
};
