<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WorkShift;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PayrollTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\URL::defaults(['admin_session' => str_repeat('a', 32)]);
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); $table->string('name');
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('email');
            $table->string('status'); $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('default_shift_id')->nullable(); $table->timestamps();
        });
        (require database_path('migrations/tao_bang_ca_lam_va_cham_cong.php'))->up();
        (require database_path('migrations/them_don_gia_ca_theo_thang.php'))->up();
        (require database_path('migrations/them_don_gia_gio_cho_ca.php'))->up();
        DB::table('roles')->insert([['id' => 1, 'name' => 'admin'], ['id' => 2, 'name' => 'staff']]);
        User::create(['name' => 'Admin', 'email' => 'admin@example.test', 'status' => 'active', 'role_id' => 1]);
        User::create(['name' => 'Staff', 'email' => 'staff@example.test', 'status' => 'active', 'role_id' => 2]);
    }

    public function test_admin_can_view_and_save_rates_independently_for_each_month()
    {
        $this->actingAs(User::find(1), 'admin');
        $this->get(route('admin.shifts.payroll', ['month' => '2026-09']))->assertOk()->assertSee('Staff');
        foreach (['2026-09' => 100000, '2026-10' => 120000] as $month => $amount) {
            $this->put(route('admin.shifts.payroll.rates'), ['month' => $month, 'staff_id' => 2, 'rates' => [1 => $amount]])
                ->assertSessionHasNoErrors()->assertRedirect();
            $this->assertDatabaseHas('staff_shift_rates', ['user_id' => 2, 'shift_id' => 1, 'month' => $month.'-01', 'hourly_amount' => $amount]);
        }
        $this->assertDatabaseCount('staff_shift_rates', 2);
        $this->get(route('admin.shifts.payroll', ['month' => 'invalid']))->assertSessionHasErrors('month');
        $this->put(route('admin.shifts.payroll.rates'), ['month' => '2026-09', 'staff_id' => 2, 'rates' => [1 => -1]])->assertSessionHasErrors('rates.1');
    }

    public function test_staff_cannot_read_or_change_payroll()
    {
        $this->actingAs(User::find(2), 'admin');
        $this->get(route('admin.shifts.payroll'))->assertRedirect(route('admin.dashboard'));
        $this->put(route('admin.shifts.payroll.rates'), ['month' => '2026-09', 'staff_id' => 2, 'rates' => [1 => 100000]])
            ->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseCount('staff_shift_rates', 0);
    }

    public function test_hourly_rate_defaults_can_be_edited_and_reset()
    {
        $this->actingAs(User::find(1), 'admin');
        $url = route('admin.shifts.payroll', ['month' => '2026-09']);
        $this->get($url)->assertViewHas('rows', fn ($rows) => $rows->first()['report']['shifts'][1]['rate'] === 20000);
        $this->put(route('admin.shifts.payroll.rates'), ['month' => '2026-09', 'staff_id' => 2, 'rates' => [1 => 25000]])->assertSessionHasNoErrors();
        $this->get($url)->assertViewHas('rows', fn ($rows) => $rows->first()['report']['shifts'][1]['rate'] == 25000);
        $this->put(route('admin.shifts.payroll.rates'), ['month' => '2026-09', 'staff_id' => 2, 'rates' => [1 => '']])->assertSessionHasNoErrors();
        $this->get($url)->assertViewHas('rows', fn ($rows) => $rows->first()['report']['shifts'][1]['rate'] === 20000);
        DB::table('staff_shift_rates')->insert(['user_id' => 2, 'shift_id' => 1, 'month' => '2026-09-01', 'amount' => 100000]);
        $this->get($url)->assertViewHas('rows', fn ($rows) => $rows->first()['report']['shifts'][1]['rate'] == 25000);
    }

    public function test_shift_detection_uses_configured_times_and_active_flag()
    {
        try {
            Carbon::setTestNow('2026-09-01 07:30:00');
            $this->assertNull(WorkShift::getCurrentShift());
            Carbon::setTestNow('2026-09-01 08:00:00');
            $this->assertSame(1, WorkShift::getCurrentShift()->id);
            Carbon::setTestNow('2026-09-01 12:00:00');
            $this->assertNull(WorkShift::getCurrentShift());
            Carbon::setTestNow('2026-09-01 13:00:00');
            $this->assertSame(2, WorkShift::getCurrentShift()->id);
            WorkShift::where('id', 2)->update(['is_active' => false]);
            $this->assertNull(WorkShift::getCurrentShift());
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_check_in_and_check_out_feed_the_monthly_report()
    {
        $staff = User::find(2);
        $staff->update(['default_shift_id' => 1]);
        $this->actingAs($staff, 'admin');
        try {
            Carbon::setTestNow('2026-09-02 09:00:00');
            $this->post(route('admin.shifts.checkin'), ['shift_id' => 1])->assertSessionHas('success');
            $this->post(route('admin.shifts.checkin'), ['shift_id' => 1])->assertSessionHas('error');
            $this->assertDatabaseCount('staff_work_logs', 1);
            Carbon::setTestNow('2026-09-02 12:00:00');
            $this->post(route('admin.shifts.checkout'))->assertSessionHas('success');
            $this->assertDatabaseHas('staff_work_logs', ['user_id' => 2, 'status' => 'completed', 'total_minutes' => 180]);
            $this->actingAs(User::find(1), 'admin');
            $this->put(route('admin.shifts.payroll.rates'), ['month' => '2026-09', 'staff_id' => 2, 'rates' => [1 => 100000]]);
            $this->get(route('admin.shifts.payroll', ['month' => '2026-09']))->assertOk()
                ->assertViewHas('rows', fn ($rows) => $rows->first()['report']['salary'] === 300000)
                ->assertSee('300.000');
        } finally {
            Carbon::setTestNow();
        }
    }
}
