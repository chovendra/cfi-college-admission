<?php
if(isset($_POST['file'], $_POST['content'])){
    $file = "../".basename($_POST['file']);
    $ext  = pathinfo($file, PATHINFO_EXTENSION);

    if(file_exists($file)){
        // Make sure archives folder exists
        $archiveDir = "../archives";
        if(!is_dir($archiveDir)){
            mkdir($archiveDir, 0777, true);
        }

        // Create archive filename: name-date-random.ext
        $base   = pathinfo($file, PATHINFO_FILENAME);
        $date   = date("Ymd-His");
        $rand   = mt_rand(1000,9999);
        $backup = "$archiveDir/{$base}-{$date}-{$rand}.{$ext}";

        // Copy original to archive
        copy($file, $backup);

        // Save new content
        file_put_contents($file, $_POST['content']);
        echo "Saved successfully! Backup created: " . basename($backup);
    } else {
        echo "File not found!";
    }
}
