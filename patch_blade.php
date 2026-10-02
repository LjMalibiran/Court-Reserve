<?php

function patchBlade($file) {
    $content = file_get_contents($file);
    
    // Find the pending table block
    $search = "@forelse(\$reservations->where('status', 'pending') as \$res)\n                        <tr>\n                            <td style=\"color: var(--primary-blue); font-weight: 600;\">{{ \$res->reservation_code }}</td>";
    $replace = "@forelse(\$reservations->where('status', 'pending') as \$res)\n                        @php \$isPast = \Carbon\Carbon::parse(\$res->start_time)->isPast(); @endphp\n                        <tr style=\"{{ \$isPast ? 'background-color: #fef2f2; border-left: 4px solid #dc2626;' : '' }}\">\n                            <td style=\"color: var(--primary-blue); font-weight: 600;\">\n                                {{ \$res->reservation_code }}\n                                @if(\$isPast)\n                                    <br><span style=\"color: #dc2626; font-size: 11px; font-weight: 700; display: inline-block; margin-top: 4px;\"><i class=\"fa-solid fa-triangle-exclamation\"></i> OVERDUE</span>\n                                @endif\n                            </td>";

    if (strpos($content, $search) !== false) {
        $content = str_replace($search, $replace, $content);
        
        $btnSearch = "<button type=\"submit\" class=\"btn-outline-confirm\">Confirm</button>\n                                    </form>\n                                    <form action=\"{{ url(Request::segment(1).'/reservations/'.\$res->id.'/cancel') }}\" method=\"POST\" style=\"margin:0;\">\n                                        @csrf\n                                        <button type=\"submit\" class=\"btn-outline-cancel\" onclick=\"return confirm('Are you sure you want to cancel this reservation?');\">Cancel</button>";
        $btnReplace = "<button type=\"submit\" class=\"btn-outline-confirm\">{{ \$isPast ? 'Force Confirm' : 'Confirm' }}</button>\n                                    </form>\n                                    <form action=\"{{ url(Request::segment(1).'/reservations/'.\$res->id.'/cancel') }}\" method=\"POST\" style=\"margin:0;\">\n                                        @csrf\n                                        <button type=\"submit\" class=\"btn-outline-cancel\" onclick=\"return confirm('Are you sure you want to cancel this reservation?');\">{{ \$isPast ? 'Cancel & Refund' : 'Cancel' }}</button>";
        
        if (strpos($content, $btnSearch) !== false) {
            $content = str_replace($btnSearch, $btnReplace, $content);
            file_put_contents($file, $content);
            echo "Patched $file\n";
        } else {
            echo "Found row logic but not button logic in $file\n";
        }
    } else {
        echo "Could not find target row logic in $file\n";
    }
}

patchBlade('resources/views/admin/reservations.blade.php');
patchBlade('resources/views/cashier/reservations.blade.php');

