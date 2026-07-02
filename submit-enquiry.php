<?php

// Only allow POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: /");
    exit;
}

// Sanitize input
$name     = isset($_POST['Name']) ? trim($_POST['Name']) : '';
$course   = isset($_POST['Course']) ? trim($_POST['Course']) : '';
$phone    = isset($_POST['Phone']) ? trim($_POST['Phone']) : '';
$email    = isset($_POST['Email']) ? trim($_POST['Email']) : '';
$location = isset($_POST['Location']) ? trim($_POST['Location']) : '';

// Basic validation
if (empty($name) || empty($phone) || empty($email) || empty($course)) {
    header("Location: /");
    exit;
}

// CSV file path
$file = __DIR__ . "/forms/enquiries.csv";

// Create directory if not exists
if (!file_exists(__DIR__ . "/forms")) {
    mkdir(__DIR__ . "/forms", 0777, true);
}

// Check if file exists (to add header)
$file_exists = file_exists($file);

// Open file
$fp = fopen($file, 'a');

// Add header if new file
if (!$file_exists) {
    fputcsv($fp, ['Date', 'Name', 'Course', 'Phone', 'Email', 'Location']);
}

// Prepare data
$data = [
    date("Y-m-d H:i:s"),
    $name,
    $course,
    $phone,
    $email,
    $location
];

// Write to CSV
fputcsv($fp, $data);

fclose($fp);

// Redirect to thank you page
header("Location: /thanks-for-enquiry");
exit;
?>