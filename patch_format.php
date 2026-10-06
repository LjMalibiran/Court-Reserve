<?php
function updateSmsFormat($file) {
    $content = file_get_contents($file);

    // Update Confirm
    $confirmSearch = "\$smsMsg = \"Good day! Your reservation for Court {\$reservation->court_id} on {\$date} at {\$start} has been officially CONFIRMED. Thank you!\";";
    $confirmReplace = "\$smsMsg = \"?? Court Reserve ??\\n\\nGood day! Your reservation is CONFIRMED. ?\\n\\n?? Court {\$reservation->court_id}\\n?? {\$date}\\n? {\$start}\\n\\nSee you at the court! Thank you.\";";
    $content = str_replace($confirmSearch, $confirmReplace, $content);

    // Update Cancel
    $cancelSearch = "\$smsMsg = \"Notice: Your reservation for Court {\$reservation->court_id} on {\$date} has been CANCELLED. If you paid online, please expect a refund.\";";
    $cancelReplace = "\$smsMsg = \"?? Court Reserve ??\\n\\nNotice: Your reservation for Court {\$reservation->court_id} on {\$date} has been CANCELLED. ?\\n\\nIf you paid online, expect a refund soon.\";";
    $content = str_replace($cancelSearch, $cancelReplace, $content);

    file_put_contents($file, $content);
    echo "Patched format in $file\n";
}

updateSmsFormat('app/Http/Controllers/AdminController.php');
updateSmsFormat('app/Http/Controllers/CashierController.php');

$remFile = 'app/Console/Commands/SendReservationReminders.php';
$remContent = file_get_contents($remFile);
$remSearch = "\$message = \"Hello {\$user->name},\\n\\nThis is an automated reminder for your {\$sport} reservation at Batangas Badminton Center.\\n\\nDate: {\$date}\\nTime: {\$start} - {\$end}\\nCourt: Court {\$reservation->court_id}\\n\\nPlease arrive on time. We look forward to seeing you!\";";
$remReplace = "\$message = \"?? Court Reserve ??\\n\\nHello {\$user->name}, this is a reminder that your {\$sport} reservation is starting soon! ?\\n\\n?? Court {\$reservation->court_id}\\n?? {\$date}\\n? {\$start} - {\$end}\\n\\nPlease arrive on time. See you!\";";
$remContent = str_replace($remSearch, $remReplace, $remContent);
file_put_contents($remFile, $remContent);
echo "Patched format in SendReservationReminders.php\n";
