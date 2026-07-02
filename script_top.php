<?php
// Default meta values
$metaTitle = "CFI College of Law – Poyya, Kerala | Accredited LL.B, Legal Studies & Admissions";
$metaDescription = "CFI College of Law in Poyya, Kerala offers accredited LL.B programmes, expert faculty and modern facilities—your gateway to quality legal education.";
$metaImage = "https://cficollegeoflaw.in/images/slider_2.jpg";
$metaURL = "https://cficollegeoflaw.in/";
$metaSiteName = "CFI College of Law";
$metaLocale = "en_IN";

// Load meta.json
$metaFile = "meta.json";
$permalink = $_GET["show"] ?? "home";

$baseHref = "/";
$scriptDir = dirname($_SERVER["SCRIPT_NAME"] ?? "/");
if ($scriptDir && $scriptDir !== "." && $scriptDir !== "/") {
    $baseHref = rtrim($scriptDir, "/") . "/";
}

if (file_exists($metaFile)) {
    $metaData = json_decode(file_get_contents($metaFile), true);
    foreach ($metaData as $entry) {
        if ($entry['permalink'] === $permalink) {
            $metaTitle = $entry['metatitle'];
            $metaDescription = $entry['description'];
            $metaURL .= ($permalink !== 'home') ? $permalink : '';
            break;
        }
    }
}
?>
<head>

<!-- Primary Meta -->
<title><?= htmlspecialchars($metaTitle) ?></title>
<meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
<link rel="canonical" href="<?= htmlspecialchars($metaURL) ?>">
<base href="<?= htmlspecialchars($baseHref) ?>">
<!-- Open Graph -->
<meta property="og:type" content="website">
<meta property="og:title" content="<?= htmlspecialchars($metaTitle) ?>">
<meta property="og:description" content="<?= htmlspecialchars($metaDescription) ?>">
<meta property="og:url" content="<?= htmlspecialchars($metaURL) ?>">
<meta property="og:image" content="<?= htmlspecialchars($metaImage) ?>">
<meta property="og:site_name" content="<?= htmlspecialchars($metaSiteName) ?>">
<meta property="og:locale" content="<?= htmlspecialchars($metaLocale) ?>">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($metaTitle) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($metaDescription) ?>">
<meta name="twitter:image" content="<?= htmlspecialchars($metaImage) ?>">

<!-- Compatibility -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

<!-- FONTS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;900&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
<!-- Font Awesome & bootstrap Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<!-- Favicons -->
<link rel="icon" href="images/favicon.png" type="image/png">
<link rel="shortcut icon" href="images/favicon.png" type="image/png">

<!-- REQUIRED CSS  -->
<link rel="stylesheet" href="assets/bootstrap.min.css">
<link rel="stylesheet" href="assets/owl.carousel.min.css">
<link rel="stylesheet" href="assets/style.css">
<!-- AOS Animations -->
<link href="assets/aos.css" rel="stylesheet"/>
<!-- REQUIRED JS  -->
<script src="assets/jquery-3.7.1.min.js"></script>
<script src="assets/bootstrap.bundle.min.js"></script>
<script src="assets/owl.carousel.min.js"></script>
<script src="assets/aos.js"></script>
<script src="assets/script.js"></script>
</head>
