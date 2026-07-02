<?php
if (isset($_GET['file'])) {
    $root = dirname(__DIR__);
    $requested = trim($_GET['file']);
    $requested = str_replace(['\\', '..'], ['/', ''], $requested);
    $requested = ltrim($requested, '/');
    $fullPath = $root . '/' . $requested;

    if (preg_match('/\.(html|json|txt|xml|csv)$/i', $requested) && is_file($fullPath)) {
        $realRoot = realpath($root);
        $realFile = realpath($fullPath);
        if ($realRoot !== false && $realFile !== false && strpos($realFile, $realRoot . DIRECTORY_SEPARATOR) === 0) {
            echo file_get_contents($realFile);
        }
    }
}
