<?php

namespace Tests\Feature;

use App\Models\ShiftChangeRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ShiftChangeRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\URL::defaults(['admin_session' => str_repeat('b', 32)]);

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('status');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('default_shift_id')->nullable();
            $table->timestamps();
        });

        (require database_path('migrations/tao_bang_ca_lam_va_cham_cong.php'))->up();
        (require database_path('migrations/tao_bang_thong_bao.php'))->up();
        (require database_path('migrations/them_yeu_cau_doi_ca.php'))->up();

        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'admin'],
            ['id' => 2, 'name' => 'staff'],
        ]);
        User::create(['name' => 'Admin', 'email' => 'admin@example.test', 'status' => 'active', 'role_id' => 1]);
        User::create(['name' => 'Staff', 'email' => 'staff@example.test', 'status' => 'active', 'role_id' => 2, 'default_shift_id' => 1]);
    }

    public function test_staff_can_request_and_admin_can_approve_a_future_shift_change()
    {
        Carbon::setTestNow('2026-09-21 10:00:00');
        $staff = User::find(2);
        $admin = User::find(1);

        $this->actingAs($staff, 'admin')
            ->post(route('admin.shifts.change-requests.store'), [
                'work_date' => '2026-09-22',
                'requested_shift_id' => 2,
                'reason' => 'Có việc gia đình.',
            ])
            ->assertSessionHas('success');

        $changeRequest = ShiftChangeRequest::firstOrFail();
        $this->assertDatabaseHas('notifications', [
            'user_id' => $admin->id,
            'type' => 'shift_change_request',
        ]);

        $this->actingAs($admin, 'admin')
            ->patch(route('admin.shifts.change-requests.approve', $changeRequest))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('shift_change_requests', [
            'id' => $changeRequest->id,
            'status' => 'approved',
            'reviewed_by' => $admin->id,
        ]);
        $this->assertSame(2, (int) $staff->fresh()->assignedShiftIdForDate('2026-09-22'));
        $this->assertDatabaseHas('notifications', [
            'user_id' => $staff->id,
            'type' => 'shift_change_approved',
        ]);

        Carbon::setTestNow();
    }
}
