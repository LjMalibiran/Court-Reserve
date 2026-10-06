<?php
function patchControllerContact($file) {
    $content = file_get_contents($file);

    // Replace $reservation->user->contact with ($reservation->user->contact ?? $reservation->user->phone_number)
    $content = str_replace(
        "if (!empty(\$reservation->user->contact)) {", 
        "\$phone = \$reservation->user->contact ?? \$reservation->user->phone_number;\n                if (!empty(\$phone)) {", 
        $content
    );
    $content = str_replace(
        "\App\Services\SmsService::send(\$reservation->user->contact, \$smsMsg);", 
        "\App\Services\SmsService::send(\$phone, \$smsMsg);", 
        $content
    );

    file_put_contents($file, $content);
    echo "Patched $file\n";
}

patchControllerContact('app/Http/Controllers/AdminController.php');
patchControllerContact('app/Http/Controllers/CashierController.php');
patchControllerContact('app/Console/Commands/SendReservationReminders.php');
