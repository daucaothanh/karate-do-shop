<?php

$step646 = file_get_contents('scratch/home_step_646.txt');
$step648 = file_get_contents('scratch/home_step_648.txt');
$step650 = file_get_contents('scratch/home_step_650.txt');

function extractLines($content) {
    $lines = explode("\n", $content);
    $clean = [];
    foreach ($lines as $l) {
        if (preg_match('/^\d+:\s?(.*)$/', rtrim($l, "\r"), $m)) {
            $clean[] = $m[1];
        }
    }
    return $clean;
}

$p1 = extractLines($step646); // lines 1-120
$p2 = extractLines($step648); // lines 120-250
$p3 = extractLines($step650); // lines 250-450

// Let's also check if there was a view for 450-651 in another step or logs
$allLogs = file('C:/Users/Admin/.gemini/antigravity-ide/brain/495ee289-1bc1-404b-a953-26b89797f89c/.system_generated/logs/transcript_full.jsonl');
$p4 = [];
foreach ($allLogs as $l) {
    $data = json_decode($l, true);
    if ($data && isset($data['content']) && strpos($data['content'], 'resources/views/clients/pages/trang_chu.blade.php') !== false) {
        $extracted = extractLines($data['content']);
        if (count($extracted) > 0) {
            echo "Step {$data['step_index']} has " . count($extracted) . " lines\n";
        }
    }
}

file_put_contents('scratch/part1.txt', implode("\n", $p1));
file_put_contents('scratch/part2.txt', implode("\n", $p2));
file_put_contents('scratch/part3.txt', implode("\n", $p3));

echo "Part 1 lines: " . count($p1) . "\n";
echo "Part 2 lines: " . count($p2) . "\n";
echo "Part 3 lines: " . count($p3) . "\n";
