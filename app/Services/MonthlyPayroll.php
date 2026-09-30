<?php

namespace App\Services;

class MonthlyPayroll
{
    public const DEFAULT_HOURLY_RATE = 20000;
    /** Each work date belongs to the date on which its scheduled shift starts. */
    public function summarize($logs, $shifts, string $month, array $rates): array
    {
        $groups = [];
        $pending = 0;
        $invalid = 0;
        foreach ($logs as $log) {
            if (!in_array($log->status, ['active', 'completed'], true)) {
                continue;
            }
            $shift = $shifts->firstWhere('id', $log->shift_id);
            $in = $log->check_in_at;
            if (!$shift || !$in) {
                $recordDate = $in ?: $log->login_at;
                if ($recordDate && $recordDate->format('Y-m') === $month) {
                    $invalid++;
                }
                continue;
            }
            $start = $in->copy()->setTimeFromTimeString($shift->start_time);
            $end = $in->copy()->setTimeFromTimeString($shift->end_time);
            if ($end->lte($start)) {
                if ($in->lt($end)) {
                    $start->subDay();
                } else {
                    $end->addDay();
                }
            }
            if ($start->format('Y-m') !== $month) {
                continue;
            }
            if ($log->status === 'active' || !$log->check_out_at) {
                $pending++;
                continue;
            }
            if ($log->status !== 'completed') {
                continue;
            }
            if ($log->check_out_at->lte($in)) {
                $invalid++;
                continue;
            }
            $from = max($in->timestamp, $start->timestamp);
            $to = min($log->check_out_at->timestamp, $end->timestamp);
            if ($to <= $from) {
                $invalid++;
                continue;
            }
            $key = $start->toDateString().'_'.$shift->id;
            $groups[$key]['date'] = $start->toDateString();
            $groups[$key]['shift_id'] = $shift->id;
            $groups[$key]['duration'] = $end->timestamp - $start->timestamp;
            $groups[$key]['intervals'][] = [$from, $to];
        }

        $details = [];
        $days = [];
        $byShift = [];
        foreach ($shifts as $shift) {
            $byShift[$shift->id] = ['days' => 0, 'units' => 0, 'minutes' => 0, 'salary' => 0, 'rate' => $rates[$shift->id] ?? self::DEFAULT_HOURLY_RATE];
        }
        ksort($groups);
        foreach ($groups as $group) {
            // Merge repeated / overlapping check-ins before calculating pay.
            sort($group['intervals']);
            $seconds = 0;
            $lastEnd = 0;
            foreach ($group['intervals'] as [$from, $to]) {
                $seconds += max(0, $to - max($from, $lastEnd));
                $lastEnd = max($lastEnd, $to);
            }
            $minutes = intdiv($seconds, 60);
            if (!$minutes) {
                continue;
            }
            $units = min(1, $minutes * 60 / $group['duration']);
            $rate = $rates[$group['shift_id']] ?? self::DEFAULT_HOURLY_RATE;
            $salary = (int) round($rate * $minutes / 60);
            $summary = &$byShift[$group['shift_id']];
            $summary['days']++;
            $summary['units'] += $units;
            $summary['minutes'] += $minutes;
            $summary['salary'] += $salary;
            unset($summary);
            $days[$group['date']] = true;
            $details[] = ['date' => $group['date'], 'shift_id' => $group['shift_id'], 'minutes' => $minutes, 'units' => $units, 'salary' => $salary, 'rate' => $rate];
        }

        return ['days' => count($days), 'shifts' => $byShift, 'details' => $details,
            'pending' => $pending, 'invalid' => $invalid,
            'salary' => array_sum(array_column($byShift, 'salary')),
            'missing_rates' => count(array_filter($byShift, fn ($s) => $s['days'] > 0 && $s['rate'] === null))];
    }
}
