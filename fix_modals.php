<?php
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('resources/views'));
foreach ($files as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    $content = file_get_contents($path);
    $changed = false;

    // Replace modal-overlay
    if (preg_match('/\.modal-overlay\s*\{[^}]*align-items:\s*center[^}]*\}/', $content, $matches)) {
        $newOverlay = preg_replace('/align-items:\s*center;?/', 'align-items: flex-start; overflow-y: auto; padding: 20px; box-sizing: border-box;', $matches[0]);
        $content = str_replace($matches[0], $newOverlay, $content);
        $changed = true;
    }

    // Replace modal-content margin if it's not already margin: auto
    if (preg_match('/\.modal-content\s*\{[^}]*\}/', $content, $matches)) {
        if (strpos($matches[0], 'margin: auto') === false) {
            $newContent = preg_replace('/margin:\s*[^;]+;/', 'margin: auto;', $matches[0]);
            // If it didn't have margin at all, just append it
            if ($newContent === $matches[0]) {
                $newContent = str_replace('}', ' margin: auto; }', $matches[0]);
            }
            $content = str_replace($matches[0], $newContent, $content);
            $changed = true;
        }
    }

    if ($changed) {
        file_put_contents($path, $content);
        echo "Patched $path\n";
    }
}
