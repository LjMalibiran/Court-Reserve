<?php
$file = 'resources/views/home.blade.php';
$content = file_get_contents($file);
$search = "<label class=\"section-label\">Available Time Slot</label>";
$replace = "<label class=\"section-label\">Available Time Slot <span style=\"color: #94a3b8; font-size: 13px; font-weight: normal; margin-left: 5px; text-transform: none;\">(Select starting time)</span></label>";
$content = str_replace($search, $replace, $content);
file_put_contents($file, $content);
echo "Patched home.blade.php\n";
