<?php
session_start();
$passcode = 'law@dmin';
$submitted = isset($_POST['passcode']);

// If correct password → store in session
if ($submitted && $_POST['passcode'] === $passcode) {
    $_SESSION['authenticated'] = true;
}

// Check session instead of POST
$authenticated = isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;

$dir = __DIR__;
$files = [];

if ($authenticated) {
    $allFiles = scandir($dir);
    foreach ($allFiles as $file) {
        if (
            pathinfo($file, PATHINFO_EXTENSION) === 'html' &&
            $file !== basename(__FILE__)
        ) {
            // Parse date from filename (assumes format: YYYY-MM-DD-name)
            $basename = pathinfo($file, PATHINFO_FILENAME);
            $parts = explode('-', $basename, 4);
            if (count($parts) >= 3) {
                $date = "{$parts[0]}-{$parts[1]}-{$parts[2]}";
                $files[] = [
                    'file' => $file,
                    'date' => $date,
                    'name' => isset($parts[3]) ? str_replace('-', ' ', $parts[3]) : 'Unknown'
                ];
            }
        }
    }

    // Sort by date descending
    usort($files, function ($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
}
// Handle delete request
if ($authenticated && isset($_POST['delete_file'])) {
    $fileToDelete = basename($_POST['delete_file']); // prevent directory traversal

    if (file_exists($dir . '/' . $fileToDelete)) {
        unlink($dir . '/' . $fileToDelete);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Enquiry Forms</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: linear-gradient(135deg, #4c6ef5, #15aabf);
    color: #fff;
}
.container {
    max-width: 600px;
}
.card {
    background-color: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 1rem;
    backdrop-filter: blur(10px);
}
.form-control, .btn {
    border-radius: 0.5rem;
}
.accordion-button {
    background-color: #0d6efd;
    color: #fff;
}
.accordion-button:not(.collapsed) {
    background-color: #0b5ed7;
    color: #fff;
}
.accordion-body {
    background-color: rgba(255,255,255,0.1);
    color: #000;
}
</style>
</head>
<body>

<div class="container py-5">
    <h2 class="mb-4 text-center fw-bold">Form Viewer</h2>

    <?php if (!$authenticated): ?>
        <?php if ($submitted): ?>
            <div class="alert alert-danger text-center">Incorrect passcode. Please try again.</div>
        <?php endif; ?>

        <div class="card p-4 shadow">
            <form method="post">
                <div class="mb-3 text-center">
                    <label for="passcode" class="form-label fs-5">Passcode</label>
                    <input type="password" name="passcode" id="passcode" class="form-control text-center" placeholder="" required>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-light btn-lg">Enter</button>
                </div>
            </form>
        </div>

    <?php else: ?>
        <div class="alert alert-success text-center">Latest Forms:</div>

        <div class="accordion" id="formsAccordion">
            <?php foreach ($files as $index => $fileInfo): ?>
                <?php
                $collapseId = 'collapse' . $index;
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading<?= $index ?>">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>" aria-expanded="false" aria-controls="<?= $collapseId ?>">
                             <?= htmlspecialchars($fileInfo['date']) ?> - <?= htmlspecialchars(ucwords($fileInfo['name'])) ?>
                        </button>
                    </h2>
                    <div id="<?= $collapseId ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $index ?>" data-bs-parent="#formsAccordion">
						<div class="accordion-body">
							<?php echo file_get_contents($fileInfo['file']); ?>
							<div class="text-end mb-2">
								<form method="post" onsubmit="return confirm('Are you sure you want to delete this form?');">
									<input type="hidden" name="delete_file" value="<?= htmlspecialchars($fileInfo['file']) ?>">
									<button type="submit" class="btn btn-danger btn-sm">Delete</button>
								</form>
							</div>							
						</div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
		
		<div class="text-center mt-4">
			<a href="enquiries.csv" class="btn btn-warning btn-lg">
				Download Enquiries
			</a>
		</div>
		
    <?php endif; ?>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
