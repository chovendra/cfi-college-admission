<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    function clean($data) {
        // Accept nulls safely and ensure we always operate on a string
        $data = $data ?? '';
        return htmlspecialchars(stripslashes(trim((string)$data)));
    }

    function slugify($text) {
        $text = preg_replace('/[^A-Za-z0-9]+/', '-', $text);
        $text = preg_replace('/-+/', '-', $text);
        return strtolower(trim($text, '-')) ?: 'file';
    }

    function uploadFile($fieldName, $folder, $studentName) {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] != 0) {
            die("Please upload " . $fieldName);
        }

        $allowed = ['jpg', 'jpeg', 'png', 'pdf'];
        $file = $_FILES[$fieldName];

        if ($file['size'] > 1048576) {
            die($fieldName . " size should not exceed 1 MB.");
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            die("Invalid file type for " . $fieldName);
        }

        $folderPath = rtrim(__DIR__ . '/' . ltrim($folder, '/'), '/');

        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $namePart = slugify($studentName);
        $datePart = date('Ymd');
        $newName = $namePart . '-' . $fieldName . '-' . $datePart . '-' . uniqid() . "." . $ext;
        $destination = $folder . "/" . $newName;

        if (!move_uploaded_file($file['tmp_name'], $folderPath . "/" . $newName)) {
            die("Failed to upload " . $fieldName);
        }

        return $destination;
    }

    // Collect and sanitize all form inputs
    $studentName = clean($_POST['studentName'] ?? '');
    $dob = clean($_POST['dob'] ?? '');
    $course = clean($_POST['course'] ?? '');
    $birthPlace = clean($_POST['birthPlace'] ?? '');
    $nationality = clean($_POST['nationality'] ?? '');
    $sslcDetails = clean($_POST['sslcDetails'] ?? '');
    $lastInstitution = clean($_POST['lastInstitution'] ?? '');
    $qualifyingExam = clean($_POST['qualifyingExam'] ?? '');
    $noOfChances = clean($_POST['noOfChances'] ?? '');
    $religionCommunity = clean($_POST['religionCommunity'] ?? '');
    $category = clean($_POST['category'] ?? '');

    // Subjects
    $sub1Title = clean($_POST['sub1Title'] ?? '');
    $maxSub1 = clean($_POST['maxSub1'] ?? '');
    $marksSub1 = clean($_POST['marksSub1'] ?? '');

    $sub2Title = clean($_POST['sub2Title'] ?? '');
    $maxSub2 = clean($_POST['maxSub2'] ?? '');
    $marksSub2 = clean($_POST['marksSub2'] ?? '');

    $sub3Title = clean($_POST['sub3Title'] ?? '');
    $maxSub3 = clean($_POST['maxSub3'] ?? '');
    $marksSub3 = clean($_POST['marksSub3'] ?? '');

    $sub4Title = clean($_POST['sub4Title'] ?? '');
    $maxSub4 = clean($_POST['maxSub4'] ?? '');
    $marksSub4 = clean($_POST['marksSub4'] ?? '');

    $sub5Title = clean($_POST['sub5Title'] ?? '');
    $maxSub5 = clean($_POST['maxSub5'] ?? '');
    $marksSub5 = clean($_POST['marksSub5'] ?? '');

    $fatherName = clean($_POST['fatherName'] ?? '');
    $motherName = clean($_POST['motherName'] ?? '');
    $fatherOccupation = clean($_POST['fatherOccupation'] ?? '');
    $annualIncome = clean($_POST['annualIncome'] ?? '');
    $guardianName = clean($_POST['guardianName'] ?? '');
    $guardianRelation = clean($_POST['guardianRelation'] ?? '');
    $address = clean($_POST['address'] ?? '');
    $phone = clean($_POST['phone'] ?? '');
    $email = clean($_POST['email'] ?? '');

    $photo = uploadFile('photo', 'uploads/admission-documents/photo', $studentName);
    $marksheet10 = uploadFile('marksheet10', 'uploads/admission-documents/marksheet10', $studentName);
    $marksheet12 = uploadFile('marksheet12', 'uploads/admission-documents/marksheet12', $studentName);
    $idproof = uploadFile('idproof', 'uploads/admission-documents/idproof', $studentName);

    // Create HTML table
    $htmlContent = "
    <html>
    <head><title>Admission Form Submission</title></head>
    <body>
    <h2>Admission Form Submission</h2>
    <table border='1' cellspacing='0' cellpadding='5'>
      <tr><th>Field</th><th>Value</th></tr>
	  <tr><td>Selected Course</td><td>$course</td></tr> 	  
      <tr><td>Name</td><td>$studentName</td></tr>
      <tr><td>Date of Birth</td><td>$dob</td></tr>
      <tr><td>Place of Birth</td><td>$birthPlace</td></tr>
      <tr><td>Nationality</td><td>$nationality</td></tr>
      <tr><td>SSLC Details</td><td>$sslcDetails</td></tr>
      <tr><td>Last Institution</td><td>$lastInstitution</td></tr>
      <tr><td>Qualifying Exam</td><td>$qualifyingExam</td></tr>
      <tr><td>No. of Chances</td><td>$noOfChances</td></tr>
      <tr><td>Religion/Community</td><td>$religionCommunity</td></tr>
      <tr><td>Category</td><td>$category</td></tr>

      <tr><td>$sub1Title Max Marks</td><td>$maxSub1</td></tr>
      <tr><td>$sub1Title Marks Secured</td><td>$marksSub1</td></tr>

      <tr><td>$sub2Title Max Marks</td><td>$maxSub2</td></tr>
      <tr><td>$sub2Title Marks Secured</td><td>$marksSub2</td></tr>

      <tr><td>$sub3Title Max Marks</td><td>$maxSub3</td></tr>
      <tr><td>$sub3Title Marks Secured</td><td>$marksSub3</td></tr>

      <tr><td>$sub4Title Max Marks</td><td>$maxSub4</td></tr>
      <tr><td>$sub4Title Marks Secured</td><td>$marksSub4</td></tr>

      <tr><td>$sub5Title Max Marks</td><td>$maxSub5</td></tr>
      <tr><td>$sub5Title Marks Secured</td><td>$marksSub5</td></tr>

      <tr><td>Father's Name</td><td>$fatherName</td></tr>
      <tr><td>Father's Occupation</td><td>$fatherOccupation</td></tr>
      <tr><td>Mother's Name & Occupation</td><td>$motherName</td></tr>
      <tr><td>Annual Income</td><td>$annualIncome</td></tr>
      <tr><td>Guardian</td><td>$guardianName</td></tr>
      <tr><td>Guardian Relation</td><td>$guardianRelation</td></tr>
      <tr><td>Address</td><td>$address</td></tr>
      <tr><td>Phone</td><td>$phone</td></tr>
      <tr><td>Email</td><td>$email</td></tr>
      <tr>
        <td>Passport Photo</td>
        <td><a href='../$photo' target='_blank'>View Photo</a></td>
        </tr>

        <tr>
            <td>10th Marksheet</td>
            <td><a href='../$marksheet10' target='_blank'>View Marksheet</a></td>
        </tr>

        <tr>
            <td>12th Marksheet</td>
            <td><a href='../$marksheet12' target='_blank'>View Marksheet</a></td>
        </tr>

        <tr>
            <td>Identity Proof</td>
            <td><a href='../$idproof' target='_blank'>View Document</a></td>
        </tr>
    </table>
    </body>
    </html>
    ";


    // Create filename
    $date = date("Y-m-d");
    $fileName = strtolower(str_replace(' ', '-', "$date-$studentName.html"));

    if (!is_dir(__DIR__ . '/forms')) {
        mkdir(__DIR__ . '/forms', 0777, true);
    }

    // Save HTML content to file
    $filePath = __DIR__ . "/forms/" . $fileName;

    if (file_put_contents($filePath, $htmlContent)) {
        header("Location: index.php?show=thank-you");
        exit();
    } else {
        header("Location: index.php?show=error-page");
        exit();
    }

} else {
    header("Location: index.php?show=error-page");
    exit();
}
?>
