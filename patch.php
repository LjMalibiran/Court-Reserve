<?php

function patchController($file) {
    $content = file_get_contents($file);
    
    $search = "            \$reservation->status = 'cancelled';\n            \$reservation->save();";
    $replace = "            \$reservation->status = 'cancelled';\n\n            // Automatically flag for full refund if paid online (Admin/Cashier cancellation)\n            if (in_array(\$reservation->payment_type, ['full', 'half'])) {\n                \$reservation->refund_status = 'pending';\n                \$paid = (float)\$reservation->amount_paid;\n                if (\$paid == 0) {\n                    \$paid = (\$reservation->payment_type == 'half') ? ((float)\$reservation->total_price / 2) : (float)\$reservation->total_price;\n                }\n                \$reservation->refund_amount = \$paid;\n            }\n\n            \$reservation->save();";
    
    if (strpos($content, $search) !== false) {
        $content = str_replace($search, $replace, $content);
        file_put_contents($file, $content);
        echo "Patched $file\n";
    } else {
        echo "Could not find target string in $file\n";
    }
}

patchController('app/Http/Controllers/AdminController.php');
patchController('app/Http/Controllers/CashierController.php');

