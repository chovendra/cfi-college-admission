<?php

session_start();

// Passkey

$passkey = "law@dmin";

// Login Check

if(isset($_POST['passkey'])){
    if($_POST['passkey'] == $passkey){
        $_SESSION['forms_logged_in'] = true;
    }else{
        $error = "Invalid Passkey!";
    }
}

// Logout

if(isset($_GET['logout'])){
    session_destroy();
    header("Location: forms.php");
    exit;
}

// Database Connection

$db = new SQLite3('scholarships.db');


// CSV Download
if(isset($_GET['download']) && $_GET['download'] == 'csv' && isset($_SESSION['forms_logged_in'])){
    $db = new SQLite3('scholarships.db');
    $result = $db->query("SELECT * FROM ScholarshipApplications ORDER BY Id DESC");
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=scholarship-applications.csv');
    $output = fopen('php://output','w');
    $firstRow = true;
    while($row = $result->fetchArray(SQLITE3_ASSOC)){
        if($firstRow){
            fputcsv($output,array_keys($row));
            $firstRow = false;
        }
        fputcsv($output,$row);
    }
    fclose($output);
    exit;
}

// Fetch Data


$result = $db->query("SELECT * FROM ScholarshipApplications ORDER BY Id DESC");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Scholarship Applications | CFI College of Law</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>

body{
    background:#eef3f9;
}

.login-card{
    max-width:420px;
    margin:auto;
    margin-top:120px;
    background:#fff;
    border-radius:18px;
    padding:35px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

.main-card{
    background:#fff;
    border-radius:18px;
    padding:25px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

.table td,
.table th{
    vertical-align:middle;
}

.detail-table td{
    padding:10px;
}

.topbar{
    background:#041c43;
    padding:15px 0;
    margin-bottom:30px;
}

.topbar h3{
    color:#fff;
    margin:0;
}

</style>

</head>

<body>

<?php if(!isset($_SESSION['forms_logged_in'])){ ?>

<!-- Login -->

<div class="container">

<div class="login-card">

<div class="text-center mb-4">

<div class="bg-primary p-2 rounded">
<img src="https://cficollegeoflaw.in/images/logo.png" class="img-fluid">
</div>
<h4 class="mt-3">
Scholarship Forms
</h4>

</div>

<?php if(isset($error)){ ?>

<div class="alert alert-danger">
    <?php echo $error; ?>
</div>

<?php } ?>

<form method="POST">

<div class="mb-3">

<label class="form-label">
Passkey
</label>

<input type="password" name="passkey" class="form-control" required>

</div>

<button type="submit" class="btn btn-primary w-100">
<i class="fa-solid fa-lock me-2"></i> Login
</button>

</form>

</div>

</div>

<?php } else { ?>

<!-- Topbar -->

<div class="topbar">

<div class="container d-flex justify-content-between align-items-center">

<h3>
Scholarship Applications
</h3>

<a href="?logout=1" class="btn btn-light btn-sm">
<i class="fa-solid fa-right-from-bracket me-1"></i> Logout
</a>

</div>

</div>

<!-- Main -->

<div class="container mb-5">

<div class="main-card">

<div class="table-responsive">

<table class="table table-bordered align-middle">

<thead class="table-primary">

<tr>

<th width="80">Sno</th>
<th>Name</th>
<th>Course</th>
<th width="180">Date</th>
<th width="100">View</th>

</tr>

</thead>

<tbody>

<?php

$counter = 1;

while($row = $result->fetchArray(SQLITE3_ASSOC)){

?>

<tr>

<td>
<?php echo $counter++; ?>
</td>

<td>
<?php echo $row['FullName']; ?>
</td>

<td>
<?php echo $row['PreferredCourse']; ?>
</td>

<td>
<?php echo date('d-m-Y',strtotime($row['CreatedAt'])); ?>
</td>

<td>

<button class="btn btn-sm btn-primary" data-bs-toggle="collapse" data-bs-target="#form<?php echo $row['Id']; ?>">

<i class="fa-solid fa-eye"></i>

</button>

</td>

</tr>

<tr class="collapse" id="form<?php echo $row['Id']; ?>">

<td colspan="5">

<div class="p-3">

<h5 class="mb-3 text-primary">
Candidate Details
</h5>

<table class="table table-bordered detail-table">

<tr>
<th width="250">Full Name</th>
<td><?php echo $row['FullName']; ?></td>
</tr>

<tr>
<th>Gender</th>
<td><?php echo $row['Gender']; ?></td>
</tr>

<tr>
<th>Date Of Birth</th>
<td><?php echo $row['DateOfBirth']; ?></td>
</tr>

<tr>
<th>Age</th>
<td><?php echo $row['Age']; ?></td>
</tr>

<tr>
<th>Mobile Number</th>
<td>+91 <?php echo $row['MobileNumber']; ?></td>
</tr>

<tr>
<th>Email Id</th>
<td><?php echo $row['EmailId']; ?></td>
</tr>

<tr>
<th>Residential Address</th>
<td><?php echo nl2br($row['ResidentialAddress']); ?></td>
</tr>

</table>

<h5 class="mb-3 mt-4 text-primary">
Parent / Guardian Details
</h5>

<table class="table table-bordered detail-table">

<tr>
<th width="250">Name</th>
<td><?php echo $row['ParentName']; ?></td>
</tr>

<tr>
<th>Contact Number</th>
<td>+91 <?php echo $row['ParentContactNumber']; ?></td>
</tr>

<tr>
<th>Email Id</th>
<td><?php echo $row['ParentEmailId']; ?></td>
</tr>

</table>

<h5 class="mb-3 mt-4 text-primary">
Academic Details
</h5>

<table class="table table-bordered detail-table">

<tr>
<th width="250">School Name</th>
<td><?php echo $row['SchoolName']; ?></td>
</tr>

<tr>
<th>Board Of Examination</th>
<td><?php echo $row['BoardOfExamination']; ?></td>
</tr>

<tr>
<th>Plus Two Stream</th>
<td><?php echo $row['PlusTwoStream']; ?></td>
</tr>

<tr>
<th>Year Of Passing</th>
<td><?php echo $row['YearOfPassing']; ?></td>
</tr>

<tr>
<th>Preferred Course</th>
<td><?php echo $row['PreferredCourse']; ?></td>
</tr>

</table>

<h5 class="mb-3 mt-4 text-primary">
Subject & Marks
</h5>

<table class="table table-bordered">

<thead class="table-light">

<tr>

<th>No.</th>
<th>Subject</th>
<th>Max Marks</th>
<th>Marks Obtained</th>
<th>Percentage</th>

</tr>

</thead>

<tbody>

<?php for($i=1;$i<=10;$i++){ ?>

<tr>

<td><?php echo $i; ?></td>

<td><?php echo $row['Subject'.$i]; ?></td>

<td><?php echo $row['MaxMarks'.$i]; ?></td>

<td><?php echo $row['MarksObtained'.$i]; ?></td>

<td><?php echo $row['Percentage'.$i]; ?></td>

</tr>

<?php } ?>

</tbody>

</table>

<table class="table table-bordered detail-table mt-4">

<tr>
<th width="250">Total Aggregate</th>
<td><?php echo $row['TotalAggregate']; ?></td>
</tr>

<tr>
<th>Place</th>
<td><?php echo $row['PlaceName']; ?></td>
</tr>

<tr>
<th>Application Date</th>
<td><?php echo $row['ApplicationDate']; ?></td>
</tr>

</table>

</div>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<p class="text-center">
<a href="?download=csv" class="btn btn-success btn-sm me-2">
<i class="fa-solid fa-file-csv me-1"></i> Download CSV
</a>
</p>

<?php } ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



</body>

</html>