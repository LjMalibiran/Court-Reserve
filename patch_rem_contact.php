<?php
$file = 'app/Console/Commands/SendReservationReminders.php';
$content = file_get_contents($file);
$content = str_replace(
    "if (!empty(\$user->contact)) {",
    "\$phone = \$user->contact ?? \$user->phone_number;\n                if (!empty(\$phone)) {",
    $content
);
$content = str_replace(
    "\App\Services\SmsService::send(\$user->contact, \$message);",
    "\App\Services\SmsService::send(\$phone, \$message);",
    $content
);
$content = str_replace(
    "{\$user->contact}",
    "{\$phone}",
    $content
);
file_put_contents($file, $content);
echo "Patched reminders.\n";
