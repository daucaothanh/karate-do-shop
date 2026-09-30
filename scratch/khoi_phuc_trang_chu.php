<?php

$p1 = file_get_contents('scratch/part1.txt');
$p2 = file_get_contents('scratch/part2.txt');
$p3 = file_get_contents('scratch/part3.txt');

$lines1 = explode("\n", $p1);
$lines2 = explode("\n", $p2);
$lines3 = explode("\n", $p3);

// Combine without overlapping lines
// Part 1: lines 0 to 119
// Part 2: lines 1 to 130
// Part 3: lines 1 to 179 (up to before quick view modal)

$combined = [];
foreach ($lines1 as $l) {
    $combined[] = $l;
}
for ($i = 1; $i < count($lines2); $i++) {
    $combined[] = $lines2[$i];
}
for ($i = 1; $i < count($lines3); $i++) {
    if (strpos($lines3[$i], 'MODAL AREA START (Quick View Modal)') !== false) {
        break;
    }
    $combined[] = $lines3[$i];
}

$combinedStr = implode("\n", $combined) . "\n";
$combinedStr .= "    <!-- MODAL AREA START (Quick View Modal) -->\n";
$combinedStr .= "    @include('clients.components.modals.xem_nhanh_modal')\n";
$combinedStr .= "    <!-- MODAL AREA END -->\n\n";
$combinedStr .= "    <!-- MODAL AREA START (Add To Cart Modal) -->\n";
$combinedStr .= "    @include('clients.components.modals.them_vao_gio_modal')\n";
$combinedStr .= "    <!-- MODAL AREA END -->\n\n";
$combinedStr .= "    <!-- MODAL AREA START (Wishlist Modal) -->\n";
$combinedStr .= "    @include('clients.components.modals.danh_sach_yeu_thich_modal')\n";
$combinedStr .= "    <!-- MODAL AREA END -->\n\n";
$combinedStr .= "@endsection\n";

file_put_contents('resources/views/clients/pages/trang_chu.blade.php', $combinedStr);
echo "Rebuilt home.blade.php successfully! Total lines: " . count(explode("\n", $combinedStr)) . "\n";
