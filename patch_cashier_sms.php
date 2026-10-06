<?php

$file = 'app/Http/Controllers/CashierController.php';
$content = file_get_contents($file);

$cancelSearch = "\App\Models\Notification::create([\n                    'user_id' => \$reservation->user_id,\n                    'reservation_id' => \$reservation->id,\n                    'title' => 'Reservation Cancelled',\n                    'message' => 'Your reservation for Court ' . \$reservation->court_id . ' has been cancelled by the cashier.'\n                ]);\n            }";
$cancelReplace = "\App\Models\Notification::create([\n                    'user_id' => \$reservation->user_id,\n                    'reservation_id' => \$reservation->id,\n                    'title' => 'Reservation Cancelled',\n                    'message' => 'Your reservation for Court ' . \$reservation->court_id . ' has been cancelled by the cashier.'\n                ]);\n\n                // Send SMS via iProgSMS\n                if (!empty(\$reservation->user->contact)) {\n                    \$date = \Carbon\Carbon::parse(\$reservation->start_time)->format('F j, Y');\n                    \$smsMsg = \"Notice: Your reservation for Court {\$reservation->court_id} on {\$date} has been CANCELLED. If you paid online, please expect a refund.\";\n                    \App\Services\SmsService::send(\$reservation->user->contact, \$smsMsg);\n                }\n            }";

if (strpos($content, $cancelSearch) !== false) {
    $content = str_replace($cancelSearch, $cancelReplace, $content);
    file_put_contents($file, $content);
    echo "Patched cancel in $file\n";
} else {
    echo "Could not find cancel pattern in $file\n";
}
