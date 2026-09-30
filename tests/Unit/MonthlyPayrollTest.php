<?php

namespace Tests\Unit;

use App\Models\StaffWorkLog;
use App\Models\WorkShift;
use App\Services\MonthlyPayroll;
use Tests\TestCase;

class MonthlyPayrollTest extends TestCase
{
    private function shifts()
    {
        return collect([
            new WorkShift(['start_time' => '08:00:00', 'end_time' => '12:00:00']),
            new WorkShift(['start_time' => '13:00:00', 'end_time' => '17:30:00']),
            new WorkShift(['start_time' => '22:00:00', 'end_time' => '06:00:00']),
        ])->each(function ($shift, $index) { $shift->id = $index + 1; });
    }

    private function log($shift, $in, $out, $status = 'completed')
    {
        return new StaffWorkLog(['shift_id' => $shift, 'check_in_at' => $in, 'check_out_at' => $out, 'status' => $status]);
    }

    public function test_partial_shifts_merge_duplicates_and_count_unique_work_dates()
    {
        $report = (new MonthlyPayroll)->summarize(collect([
            $this->log(1, '2026-09-01 09:00', '2026-09-01 11:00'),
            $this->log(1, '2026-09-01 10:00', '2026-09-01 13:00'),
            $this->log(2, '2026-09-01 13:00', '2026-09-01 19:00'),
            $this->log(1, '2026-09-02 08:00', null, 'active'),
            $this->log(1, '2026-09-03 08:00', '2026-09-03 12:00', 'cancelled'),
        ]), $this->shifts(), '2026-09', [1 => 100000, 2 => 150000]);

        $this->assertSame(1, $report['days']);
        $this->assertSame(1, $report['pending']);
        $this->assertSame(180, $report['shifts'][1]['minutes']);
        $this->assertEquals(0.75, $report['shifts'][1]['units']);
        $this->assertSame(975000, $report['salary']);
    }

    public function test_overnight_shift_belongs_to_start_month_and_default_hourly_rate_is_applied()
    {
        $report = (new MonthlyPayroll)->summarize(collect([
            $this->log(3, '2026-10-01 01:00', '2026-10-01 08:00'),
            $this->log(3, '2026-09-01 01:00', '2026-09-01 06:00'),
            $this->log(1, '2026-09-02 09:00', '2026-09-02 08:00'),
        ]), $this->shifts(), '2026-09', []);

        $this->assertSame(1, $report['days']);
        $this->assertSame('2026-09-30', $report['details'][0]['date']);
        $this->assertSame(300, $report['shifts'][3]['minutes']);
        $this->assertSame(0, $report['missing_rates']);
        $this->assertSame(1, $report['invalid']);
        $this->assertSame(100000, $report['salary']);
    }

    public function test_repeated_sessions_do_not_pay_breaks_and_zero_rate_is_configured()
    {
        $report = (new MonthlyPayroll)->summarize(collect([
            $this->log(1, '2026-09-01 08:00', '2026-09-01 09:00'),
            $this->log(1, '2026-09-01 10:00', '2026-09-01 11:00'),
        ]), $this->shifts(), '2026-09', [1 => 0]);
        $this->assertSame(120, $report['shifts'][1]['minutes']);
        $this->assertSame(0, $report['missing_rates']);
        $this->assertSame(1, $report['days']);
    }
}
