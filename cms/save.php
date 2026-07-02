<?php
if (isset($_POST['file'], $_POST['content'])) {
    $root = dirname(__DIR__);
    $requested = trim($_POST['file']);
    $requested = str_replace(['\\', '..'], ['/', ''], $requested);
    $requested = ltrim($requested, '/');
    $fullPath = $root . '/' . $requested;

    if (preg_match('/\.(html|json|txt|xml|csv)$/i', $requested) && is_file($fullPath)) {
        $realRoot = realpath($root);
        $realFile = realpath($fullPath);
        if ($realRoot !== false && $realFile !== false && strpos($realFile, $realRoot . DIRECTORY_SEPARATOR) === 0) {
            $archiveDir = $root . '/archives';
            if (!is_dir($archiveDir)) {
                mkdir($archiveDir, 0777, true);
            }

            $ext = strtolower(pathinfo($realFile, PATHINFO_EXTENSION));
            $base = pathinfo($realFile, PATHINFO_FILENAME);
            $date = date('Ymd-His');
            $rand = mt_rand(1000, 9999);
            $backup = $archiveDir . '/' . $base . '-' . $date . '-' . $rand . '.' . $ext;

            copy($realFile, $backup);
            file_put_contents($realFile, $_POST['content']);
            echo 'Saved successfully! Backup created: ' . basename($backup);
        } else {
            echo 'Access denied.';
        }
    } else {
        echo 'File not found or not editable.';
    }
}
