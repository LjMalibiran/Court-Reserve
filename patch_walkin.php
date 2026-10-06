<?php
function patchWalkinTimeSlot($file) {
    $content = file_get_contents($file);
    $search = "<label>Time<span>*</span> <span style=\"font-size:11px; color:var(--text-muted); font-weight:400; margin-left:5px;\">Available Time Slot</span></label>";
    $replace = "<label>Time<span>*</span> <span style=\"font-size:12px; color:var(--text-muted); font-weight:400; margin-left:5px;\">Available Time Slot (Select starting time)</span></label>";
    $content = str_replace($search, $replace, $content);
    file_put_contents($file, $content);
    echo "Patched $file\n";
}
patchWalkinTimeSlot('resources/views/admin/walk-in.blade.php');
patchWalkinTimeSlot('resources/views/cashier/walk-in.blade.php');
