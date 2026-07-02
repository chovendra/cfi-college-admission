<?php
// list_images.php

function getImages($dir) {
    $images = [];
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
            if (in_array($ext, $allowed_ext)) {
                $sizeBytes = $file->getSize();
                $sizeKB = $sizeBytes / 1024;
                $sizeStr = ($sizeKB > 1024)
                    ? round($sizeKB / 1024, 2) . ' MB'
                    : round($sizeKB, 2) . ' KB';

                $images[] = [
                    'path' => str_replace("\\", "/", $file->getPathname()),
                    'size_kb' => $sizeKB,
                    'size_str' => $sizeStr
                ];
            }
        }
    }
    return $images;
}

$images = getImages(__DIR__);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; padding: 20px; }
        .container { background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 20px; }
        thead th { background: #0d6efd; color: white; cursor: pointer; user-select: none; }
        thead th:hover { background: #0b5ed7; }
        .small-text { font-size: 0.85em; color: #444; word-break: break-all; }
        img { border: 1px solid #ddd; padding: 2px; background: #f9f9f9; }
        .badge-green { background-color: #28a745; }      /* < 200 KB */
        .badge-yellow { background-color: #ffc107; }     /* 200–500 KB */
        .badge-blue { background-color: #0dcaf0; }       /* 500–1024 KB */
        .badge-red { background-color: #dc3545; }        /* > 1 MB */
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4 text-center text-primary"><i class="fa fa-images me-2"></i>Image Files in All Folders</h2>

    <?php if (count($images) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="imageTable">
                <thead>
                    <tr>
                        <th onclick="sortTable(0)">#</th>
                        <th onclick="sortTable(1)">Preview</th>
                        <th onclick="sortTable(2)">Image Path</th>
                        <th onclick="sortTable(3)">Size</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($images as $index => $img): 
                        $badgeClass = ($img['size_kb'] < 200) ? 'badge-green' :
                                      (($img['size_kb'] < 500) ? 'badge-yellow' :
                                      (($img['size_kb'] < 1024) ? 'badge-blue' : 'badge-red'));
                    ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td>
                                <a href="<?= htmlspecialchars($img['path']) ?>" target="_blank">
                                    <img src="<?= htmlspecialchars($img['path']) ?>" alt="" width="60" class="rounded">
                                </a>
                            </td>
                            <td>
                                <a href="<?= htmlspecialchars($img['path']) ?>" target="_blank" class="small-text text-decoration-none">
                                    <?= htmlspecialchars($img['path']) ?>
                                </a>
                            </td>
                            <td><span class="badge <?= $badgeClass ?>"><?= $img['size_str'] ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            <i class="fa fa-exclamation-circle"></i> No images found in this directory.
        </div>
    <?php endif; ?>
</div>

<script src="https://kit.fontawesome.com/a2e0e6ad8d.js" crossorigin="anonymous"></script>
<script>
// Basic table sorting by column index
function sortTable(n) {
    const table = document.getElementById("imageTable");
    let rows, switching = true, dir = "asc", switchcount = 0;

    while (switching) {
        switching = false;
        rows = table.rows;
        for (let i = 1; i < rows.length - 1; i++) {
            let shouldSwitch = false;
            let x = rows[i].getElementsByTagName("TD")[n];
            let y = rows[i + 1].getElementsByTagName("TD")[n];
            let xVal = x.textContent.trim().toLowerCase();
            let yVal = y.textContent.trim().toLowerCase();

            // Numeric comparison for size
            if (n === 3) {
                xVal = parseFloat(xVal);
                yVal = parseFloat(yVal);
            }

            if (dir === "asc" && xVal > yVal) { shouldSwitch = true; break; }
            if (dir === "desc" && xVal < yVal) { shouldSwitch = true; break; }
        }

        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount++;
        } else {
            if (switchcount === 0 && dir === "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}
</script>
</body>
</html>
