<?php

// Check part 3 lines
$lines3 = file('scratch/part3.txt');
echo "End of part 3:\n";
for ($i = max(0, count($lines3) - 30); $i < count($lines3); $i++) {
    echo ($i + 250) . ": " . $lines3[$i];
}
