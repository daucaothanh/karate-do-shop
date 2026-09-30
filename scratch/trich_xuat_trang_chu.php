<?php

$logPath = 'C:/Users/Admin/.gemini/antigravity-ide/brain/495ee289-1bc1-404b-a953-26b89797f89c/.system_generated/logs/transcript_full.jsonl';
if (!file_exists($logPath)) {
    echo "No log found";
    exit;
}

$f = fopen($logPath, 'r');
$found = [];
$step = 0;
while (($line = fgets($f)) !== false) {
    $data = json_decode($line, true);
    if ($data && isset($data['content'])) {
        if (strpos($data['content'], 'resources/views/clients/pages/trang_chu.blade.php') !== false) {
            $found[] = $data['step_index'];
            file_put_contents('scratch/home_step_' . $data['step_index'] . '.txt', $data['content']);
        }
    }
}
fclose($f);

echo "Found steps: " . implode(', ', $found) . "\n";
