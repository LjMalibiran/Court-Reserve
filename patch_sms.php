<?php

function patchControllerSMS($file) {
    $content = file_get_contents($file);

    // 1. Confirm Reservation Patch
    $confirmSearch = "\App\Models\Notification::create([\n                    'user_id' => \$reservation->user_id,\n                    'reservation_id' => \$reservation->id,\n                    'title' => 'Reservation Confirmed',\n                    'message' => 'Your reservation for Court ' . \$reservation->court_id . ' has been confirmed.'\n                ]);\n            }";
    $confirmReplace = "\App\Models\Notification::create([\n                    'user_id' => \$reservation->user_id,\n                    'reservation_id' => \$reservation->id,\n                    'title' => 'Reservation Confirmed',\n                    'message' => 'Your reservation for Court ' . \$reservation->court_id . ' has been confirmed.'\n                ]);\n\n                // Send SMS via iProgSMS\n                if (!empty(\$reservation->user->contact)) {\n                    \$date = \Carbon\Carbon::parse(\$reservation->start_time)->format('F j, Y');\n                    \$start = \Carbon\Carbon::parse(\$reservation->start_time)->format('g:i A');\n                    \$smsMsg = \"Good day! Your reservation for Court {\$reservation->court_id} on {\$date} at {\$start} has been officially CONFIRMED. Thank you!\";\n                    \App\Services\SmsService::send(\$reservation->user->contact, \$smsMsg);\n                }\n            }";

    if (strpos($content, $confirmSearch) !== false) {
        $content = str_replace($confirmSearch, $confirmReplace, $content);
        echo "Patched confirm in $file\n";
    }

    // 2. Cancel Reservation Patch
    $cancelSearch = "\App\Models\Notification::create([\n                    'user_id' => \$reservation->user_id,\n                    'reservation_id' => \$reservation->id,\n                    'title' => 'Reservation Cancelled',\n                    'message' => 'Your reservation for Court ' . \$reservation->court_id . ' has been cancelled by the admin.'\n                ]);\n            }";
    $cancelReplace = "\App\Models\Notification::create([\n                    'user_id' => \$reservation->user_id,\n                    'reservation_id' => \$reservation->id,\n                    'title' => 'Reservation Cancelled',\n                    'message' => 'Your reservation for Court ' . \$reservation->court_id . ' has been cancelled by the admin.'\n                ]);\n\n                // Send SMS via iProgSMS\n                if (!empty(\$reservation->user->contact)) {\n                    \$date = \Carbon\Carbon::parse(\$reservation->start_time)->format('F j, Y');\n                    \$smsMsg = \"Notice: Your reservation for Court {\$reservation->court_id} on {\$date} has been CANCELLED. If you paid online, please expect a refund.\";\n                    \App\Services\SmsService::send(\$reservation->user->contact, \$smsMsg);\n                }\n            }";

    if (strpos($content, $cancelSearch) !== false) {
        $content = str_replace($cancelSearch, $cancelReplace, $content);
        echo "Patched cancel in $file\n";
    }

    file_put_contents($file, $content);
}

patchControllerSMS('app/Http/Controllers/AdminController.php');
patchControllerSMS('app/Http/Controllers/CashierController.php');
