<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views'));
foreach ($files as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    $content = file_get_contents($path);
    $changed = false;

    // Remove inline @if(session('success')) blocks
    if (preg_match('/@if\s*\(\s*session\(\'success\'\)\s*\)(.*?)@endif/s', $content, $matches)) {
        // Only remove if it contains some UI element like <div class="alert
        if (strpos($matches[0], '<div') !== false || strpos($matches[0], '<i') !== false || strpos($matches[0], '{{ session') !== false) {
            $content = str_replace($matches[0], '<!-- Session Success Modal Handled Globally -->', $content);
            $changed = true;
        }
    }

    // Remove inline @if(session('error')) blocks
    if (preg_match('/@if\s*\(\s*session\(\'error\'\)\s*\)(.*?)@endif/s', $content, $matches)) {
        if (strpos($matches[0], '<div') !== false || strpos($matches[0], '<i') !== false || strpos($matches[0], '{{ session') !== false) {
            $content = str_replace($matches[0], '<!-- Session Error Modal Handled Globally -->', $content);
            $changed = true;
        }
    }

    if ($changed) {
        file_put_contents($path, $content);
        echo "Cleaned inline alerts from $path\n";
    }
}
