<?php

function patchReminders() {
    $file = 'app/Console/Commands/SendReservationReminders.php';
    $content = file_get_contents($file);
    
    // Replace semaphore block
    $search = "// Send SMS via Semaphore\n                try {\n                    \$apiKey = env('SEMAPHORE_API_KEY');\n                    \$senderName = env('SEMAPHORE_SENDER_NAME', '');\n\n                    \$payload = [\n                        'apikey' => \$apiKey,\n                        'number' => \$user->contact,\n                        'message' => \$message,\n                    ];\n\n                    if (!empty(\$senderName)) {\n                        \$payload['sendername'] = \$senderName;\n                    }\n\n                    Http::post('https://api.semaphore.co/api/v4/messages', \$payload);\n                    error_log(\"[Reminder] SMS sent to {\$user->contact}\");\n                } catch (\Exception \$e) {\n                    error_log(\"[Reminder] SMS failed: \" . \$e->getMessage());\n                }";

    $replace = "// Send SMS via iProgSMS\n                if (!empty(\$user->contact)) {\n                    \$success = \App\Services\SmsService::send(\$user->contact, \$message);\n                    if (\$success) {\n                        error_log(\"[Reminder] SMS sent to {\$user->contact}\");\n                    } else {\n                        error_log(\"[Reminder] SMS failed for {\$user->contact}\");\n                    }\n                }";

    if (strpos($content, "SEMAPHORE") !== false) {
        $content = str_replace($search, $replace, $content);
        file_put_contents($file, $content);
        echo "Patched SendReservationReminders.php\n";
    } else {
        echo "Semaphore string not found.\n";
    }
}

patchReminders();
