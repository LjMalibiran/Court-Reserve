<?php
$files = [
    'resources/views/login.blade.php',
    'resources/views/register.blade.php',
    'resources/views/admin/login.blade.php',
    'resources/views/cashier/login.blade.php',
    'resources/views/verify.blade.php',
    'resources/views/verify-reset.blade.php'
];

$swalCode = "
<!-- SweetAlert2 Library for Global Modals -->
<script src=\"https://cdn.jsdelivr.net/npm/sweetalert2@11\"></script>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Success!',
            text: \"{!! addslashes(session('success')) !!}\",
            icon: 'success',
            confirmButtonColor: '#1557c0'
        });
    });
</script>
@endif

@if(session('error') || \$errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Error!',
            text: \"{!! addslashes(session('error') ?? \$errors->first()) !!}\",
            icon: 'error',
            confirmButtonColor: '#dc2626'
        });
    });
</script>
@endif
";

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'sweetalert2') === false) {
            $content = str_replace('</body>', $swalCode . "\n</body>", $content);
            file_put_contents($file, $content);
            echo "Added SweetAlert to $file\n";
        }
    }
}
