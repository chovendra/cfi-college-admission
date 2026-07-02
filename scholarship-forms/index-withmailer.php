<?php
//Mailer
require_once __DIR__ . '/mailer.php';
// Database Connection
$db = new SQLite3('scholarships.db');
$message = "";

// Form Submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $stmt = $db->prepare("INSERT INTO ScholarshipApplications(
    FullName,Gender,DateOfBirth,Age,MobileNumber,EmailId,ResidentialAddress,
    ParentName,ParentContactNumber,ParentEmailId,
    SchoolName,BoardOfExamination,PlusTwoStream,YearOfPassing,PreferredCourse,
    Subject1,MaxMarks1,MarksObtained1,Percentage1,
    Subject2,MaxMarks2,MarksObtained2,Percentage2,
    Subject3,MaxMarks3,MarksObtained3,Percentage3,
    Subject4,MaxMarks4,MarksObtained4,Percentage4,
    Subject5,MaxMarks5,MarksObtained5,Percentage5,
    Subject6,MaxMarks6,MarksObtained6,Percentage6,
    TotalAggregate,
    DeclarationAccepted,PlaceName,ApplicationDate
    ) VALUES (
    :FullName,:Gender,:DateOfBirth,:Age,:MobileNumber,:EmailId,:ResidentialAddress,
    :ParentName,:ParentContactNumber,:ParentEmailId,
    :SchoolName,:BoardOfExamination,:PlusTwoStream,:YearOfPassing,:PreferredCourse,
    :Subject1,:MaxMarks1,:MarksObtained1,:Percentage1,
    :Subject2,:MaxMarks2,:MarksObtained2,:Percentage2,
    :Subject3,:MaxMarks3,:MarksObtained3,:Percentage3,
    :Subject4,:MaxMarks4,:MarksObtained4,:Percentage4,
    :Subject5,:MaxMarks5,:MarksObtained5,:Percentage5,
    :Subject6,:MaxMarks6,:MarksObtained6,:Percentage6,
    :TotalAggregate,
    :DeclarationAccepted,:PlaceName,:ApplicationDate
    )");
    foreach($_POST as $key => $value){
        $stmt->bindValue(':'.$key,trim($value));
    }
    $stmt->execute();
    $message = "Application Submitted Successfully!";

	/* =========================
	   ACKNOWLEDGEMENT EMAIL
	========================= */

	$applicantName = trim($_POST['FullName']);
	$applicantEmail = trim($_POST['EmailId']);

	$smtpConfig = [

		'host' => 'smtp.rediffmailpro.com',
		'port' => 587,

		'username' => 'admin@cmail.net.in',
		'password' => 'Mathew2820#@',

		'encryption' => 'tls',

		'from_email' => 'admin@cmail.net.in',
		'from_name' => 'CFI College of Law',

		'debug' => false,
	];

	$htmlMessage = '
	<!DOCTYPE html>
	<html>
	<head>
	<meta charset="UTF-8">
	<title>Application Acknowledgement</title>
	</head>

	<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,sans-serif;">

	<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9;padding:40px 0;">
	<tr>
	<td align="center">

	<table width="650" cellpadding="0" cellspacing="0" style="background:#ffffff;border-radius:12px;overflow:hidden;">

	<tr>
	<td style="background:#041c43;padding:30px;text-align:center;">
	<img src="https://cficollegeoflaw.in/images/logo.png" style="max-height:80px;">
	</td>
	</tr>

	<tr>
	<td style="padding:40px;color:#1d2a3a;font-size:16px;line-height:1.8;">

	<h2 style="margin-top:0;color:#041c43;">
	Acknowledgement of Application – CFI Scholarship Examination 2026
	</h2>

	<p>
	Dear <strong>'.$applicantName.'</strong>,
	</p>

	<p>
	Thank you for submitting your application for the CFI College of Law Scholarship Examination.
	We are pleased to confirm that we have received your application form and it is currently under review.
	</p>

	<p>
	Please note that candidates shortlisted for the examination will be intimated individually by email.
	We request you to keep a close watch on your inbox, including your spam or promotions folder,
	to ensure you do not miss any communication from us.
	</p>

	<p>
	We appreciate your interest in CFI College of Law and wish you all the best.
	</p>

	<p style="margin-top:40px;">
	Warm regards,<br>
	<strong>Admissions Office</strong><br>
	CFI College of Law
	</p>

	</td>
	</tr>

	<tr>
	<td style="background:#eef3f9;padding:20px;text-align:center;font-size:13px;color:#6b7a90;">
	CFI College of Law | Thrissur, Kerala
	</td>
	</tr>

	</table>

	</td>
	</tr>
	</table>

	</body>
	</html>
	';

	$textMessage = "

	Dear {$applicantName},

	Thank you for submitting your application for the CFI College of Law Scholarship Examination.

	We are pleased to confirm that we have received your application form and it is currently under review.

	Please note that candidates shortlisted for the examination will be intimated individually by email.

	We request you to keep a close watch on your inbox, including your spam or promotions folder.

	Warm regards,

	Admissions Office
	CFI College of Law
	";

	$mailer = new SimpleMailer($smtpConfig);

	$mailer->send([
		'to' => $applicantEmail,
		'bcc' => 'scholarship@cficollegeoflaw.in',
		'reply_to' => [
			'email' => 'scholarship@cficollegeoflaw.in',
			'name'  => 'CFI College of Law'
		],		
		'subject' => 'Acknowledgement of Application – CFI Scholarship Examination 2026',
		'html' => $htmlMessage,
		'text' => $textMessage
	]);


	
	
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Meta Tags -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Scholarship Examination 2026-2027 Application Form | CFI College of Law</title>
<meta name="description" content="Apply online for the CFI College of Law Scholarship Examination 2026-2027. Admissions open for BBA LL.B and B.Com LL.B programs in Thrissur Kerala.">
<meta name="keywords" content="CFI College of Law Scholarship, Law Scholarship Kerala, Scholarship Examination 2026, BBA LLB Admission Kerala, BCom LLB Admission Kerala">
<meta name="robots" content="index,follow">
<link rel="canonical" href="https://cficollegeoflaw.in/scholarship-form/">
<meta property="og:type" content="website">
<meta property="og:title" content="Scholarship Examination 2026-2027 Application Form | CFI College of Law">
<meta property="og:description" content="Register online for the CFI College of Law Scholarship Examination 2026-2027.">
<meta property="og:url" content="https://cficollegeoflaw.in/scholarship-form/">
<meta property="og:image" content="https://cficollegeoflaw.in/images/slider_2.jpg">
<meta property="og:site_name" content="CFI College of Law">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Scholarship Examination 2026-2027 Application Form | CFI College of Law">
<meta name="twitter:description" content="Apply online for scholarship examination admissions.">
<meta name="twitter:image" content="https://cficollegeoflaw.in/images/slider_2.jpg">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
<!-- Styles -->
<style>
body{
    background:#eef3f9;
    font-family:'Segoe UI',sans-serif;
    color:#1d2a3a;
}
.header{
    background:linear-gradient(135deg,#041c43,#0a3b82);
    padding:20px;
    text-align:center;
}
.header img{
    max-height:90px;
}
.hero-section{
    background:linear-gradient(135deg,#041c43,#0a3b82);
    color:#fff;
    padding:50px 20px;
    border-radius:0 0 25px 25px;
    margin-bottom:40px;
}
.hero-section h1{
    font-size:42px;
    font-weight:700;
}
.hero-section h3{
    font-size:22px;
    font-weight:400;
}
.hero-info{
    background:rgba(255,255,255,0.08);
    border-radius:16px;
    padding:20px;
    margin:auto;
    margin-top:30px;
}
.form-card{
    background:#fff;
    border-radius:22px;
    padding:35px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
    margin-bottom:40px;
}
.section-title{
    background:#e4e8ee;
    color:#25364d;
    padding:14px 20px;
    border-left:5px solid #0d6efd;
    border-radius:12px;
    font-size:20px;
    font-weight:700;
    margin-bottom:25px;
}

.form-label{
    color:#0b4ea2;
    font-weight:600;
}
.form-control,
.form-select{
    border-radius:12px;
    border:1px solid #d3dce8;
    padding:12px 14px;
    min-height:48px;
}
.form-control:focus,
.form-select:focus{
    border-color:#0d6efd;
    box-shadow:0 0 0 0.15rem rgba(13,110,253,0.15);
}
.table thead{
    background:#dfe8f7;
}
.table thead th{
    color:#16365f;
}
.btn-submit{
    background:linear-gradient(135deg,#041c43,#0a3b82);
    color:#fff;
    padding:14px 45px;
    border:none;
    border-radius:14px;
    font-size:18px;
    font-weight:600;
}
.footer-text{
    color:#6b7a90;
}
@media(max-width:768px){
    .hero-section h1{
        font-size:28px;
    }

    .hero-section h3{
        font-size:18px;
    }

    .form-card{
        padding:22px;
    }
}
</style>
</head>
<body>
<!-- Header -->
<div class="header">
    <img src="https://cficollegeoflaw.in/images/logo.png" alt="CFI Logo">
</div>
<!-- Hero Section -->
<div class="hero-section text-center">
<div class="container">
<h1>Scholarship Examination 2026-2027</h1>
<h3>Application Form</h3>

<div class="hero-info">
<div class="row text-center g-4">

<div class="col-md-3 col-6">
<div class="mb-3">
<i class="fa-solid fa-calendar-days fa-3x"></i>
</div>
<div><strong>Date & Time</strong></div>
<div>06/06/2026</div>
<div>11.00 AM - 12.00 PM</div>
</div>

<div class="col-md-3 col-6">
<div class="mb-3">
<i class="fa-solid fa-clock fa-3x"></i>
</div>
<div><strong>Reporting Time</strong></div>
<div>10.00 AM</div>
</div>

<div class="col-md-3 col-6">
<div class="mb-3">
<i class="fa-solid fa-location-dot fa-3x"></i>
</div>
<div><strong>Venue</strong></div>
<div>CFI College of Law</div>
<div>Poyya, Thrissur Dist.</div>
</div>

<div class="col-md-3 col-6">
<div class="mb-3">
<i class="fa-solid fa-phone-volume fa-3x"></i>
</div>
<div><strong>Contact</strong></div>
<div>+91 8590759003</div>
<div>+91 8590759004</div>
</div>

</div>
</div>

</div>
</div>

<!-- Main Form -->
<div class="container">
<div class="form-card">
<?php if($message != ""){ ?>
<div class="text-center py-5">
<div class="mb-4">
<i class="fa-solid fa-circle-check text-success" style="font-size:90px;"></i>
</div>
<h2 class="fw-bold text-success mb-3">
Application Submitted Successfully
</h2>
<p class="lead mb-4">
Thank you for applying for the Scholarship Examination 2026-2027 at CFI College of Law.
</p>
<p class="text-muted mb-5">
Our admissions team will contact you shortly with further details regarding the examination.
</p>
<a href="../index.php" class="btn btn-primary btn-lg px-5">
<i class="fa-solid fa-house me-2"></i> Back To Home
</a>

</div>

<?php } else { ?>

<form method="POST">
<!-- Candidate Profile -->
<div class="section-title">
    Section 1 : Candidate Profile
</div>
<div class="row g-4">
<div class="col-md-6">
<label class="form-label">Full Name *</label>
<input type="text" name="FullName" class="form-control" required>
</div>
<div class="col-md-6">
<label class="form-label">Gender *</label>
<select name="Gender" class="form-select" required>
<option value="">Select</option>
<option>Male</option>
<option>Female</option>
<option>Others</option>
</select>
</div>
<div class="col-md-6">
<label class="form-label">Date Of Birth *</label>
<input type="date" id="DateOfBirth" name="DateOfBirth" class="form-control" min="1975-01-01" max="2010-12-31" required>
</div>
<div class="col-md-6">
<label class="form-label">Age *</label>
<input type="text" id="Age" name="Age" class="form-control" readonly>
</div>
<div class="col-md-6">
<label class="form-label">Mobile Number *</label>
<div class="input-group">
<span class="input-group-text">+91</span>
<input type="tel" name="MobileNumber" class="form-control" pattern="[0-9]{10}" maxlength="10" minlength="10" required>
</div>
</div>
<div class="col-md-6">
<label class="form-label">Email Id *</label>
<input type="email" name="EmailId" class="form-control" required>
</div>
<div class="col-12">
<label class="form-label">Residential Address *</label>
<textarea name="ResidentialAddress" id="ResidentialAddress" class="form-control" rows="3" required></textarea>
</div>
</div>
<!-- Parent Details -->
<div class="section-title mt-5">
    Section 2 : Parent / Guardian Details
</div>
<div class="row g-4">
<div class="col-md-4">
<label class="form-label">Name *</label>
<input type="text" name="ParentName" class="form-control" required>
</div>
<div class="col-md-4">
<label class="form-label">Contact Number *</label>
<div class="input-group">
<span class="input-group-text">+91</span>
<input type="tel" name="ParentContactNumber" class="form-control" pattern="[0-9]{10}" maxlength="10" minlength="10" required>
</div>
</div>
<div class="col-md-4">
<label class="form-label">Email Id</label>
<input type="email" name="ParentEmailId" class="form-control">
</div>
</div>

<!-- Academic Details -->
<div class="section-title mt-5">
    Section 3 : Academic Details
</div>
<div class="row g-4">
<div class="col-md-6">
<label class="form-label">Name Of The School *</label>
<input type="text" name="SchoolName" class="form-control" required>
</div>
<div class="col-md-6">
<label class="form-label">Board Of Examination *</label>
<select name="BoardOfExamination" class="form-select" required>
<option>State Board</option>
<option>CBSE</option>
<option>ICSE</option>
<option>Other</option>
</select>
</div>
<div class="col-md-6">
<label class="form-label">Plus Two Stream *</label>
<select name="PlusTwoStream" class="form-select" required>
<option>Science</option>
<option>Commerce</option>
<option>Humanities</option>
<option>Others</option>
</select>
</div>
<div class="col-md-3">
<label class="form-label">Year Of Passing *</label>
<input type="number" name="YearOfPassing" class="form-control" required>
</div>
<div class="col-md-3">
<label class="form-label">Preferred Course *</label>
<select name="PreferredCourse" class="form-select" required>
<option>B.Com LL.B.</option>
<option>BBA LL.B.</option>
</select>
</div>
</div>

<!-- Subject Marks -->
<div class="section-title mt-5">
    Section 4 : Plus Two — Subject & Marks
</div>
<div class="table-responsive">
<table class="table table-bordered align-middle">
<thead>
<tr>
<th>No.</th>
<th>Subject</th>
<th>Max. Marks</th>
<th>Marks Obtained</th>
<th>Percentage (%)</th>
</tr>
</thead>
<tbody>
<?php for($i=1;$i<=6;$i++){ ?>
<tr>
<td><?php echo $i; ?></td>
<td><input type="text" name="Subject<?php echo $i; ?>" class="form-control"></td>
<td><input type="text" name="MaxMarks<?php echo $i; ?>" class="form-control"></td>
<td><input type="text" name="MarksObtained<?php echo $i; ?>" class="form-control"></td>
<td><input type="text" name="Percentage<?php echo $i; ?>" class="form-control"></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

<!-- Aggregate -->
<div class="row mt-4">
<div class="col-md-4">
<label class="form-label">Total / Aggregate</label>
<input type="text" name="TotalAggregate" class="form-control">
</div>
</div>

<!-- Declaration -->
<div class="section-title mt-5">
    Declaration
</div>
<div class="form-check mb-4">

<input class="form-check-input" type="checkbox" name="DeclarationAccepted" value="Yes" required>

<label class="form-check-label">
I hereby declare that the information provided above is true to the best of my knowledge.
</label>
</div>

<!-- Place and Date -->
<div class="row g-4">
<div class="col-md-6">
<label class="form-label">Place</label>
<input type="text" name="PlaceName" class="form-control">
</div>

<div class="col-md-6">
<label class="form-label">Date</label>
<input type="date" name="ApplicationDate" class="form-control" value="<?php echo date('Y-m-d'); ?>" readonly>
</div>
</div>

<!-- Submit -->
<div class="text-center mt-5">
<button type="submit" class="btn-submit">
    Submit Application
</button>
</div>
</form>

<?php } ?>

<hr class="my-5">
<div class="text-center footer-text">
CFI College of Law, Poyya, Thrissur Dist., Kerala | admissions@cficollegeoflaw.in
</div>
</div>
</div>

<!-- Scripts -->
<script>
const dobInput = document.getElementById('DateOfBirth');
const ageInput = document.getElementById('Age');
dobInput.addEventListener('change',function(){
    const dob = new Date(this.value);
    const referenceDate = new Date('2026-06-01');
    let years = referenceDate.getFullYear() - dob.getFullYear();
    let months = referenceDate.getMonth() - dob.getMonth();
    if(months < 0){
        years--;
        months += 12;
    }

    ageInput.value = years + ' Years ' + months + ' Months';
});

document.getElementById('ResidentialAddress').addEventListener('input',function(){
    this.value = this.value.replace(/[^A-Za-z0-9,\-\s]/g,'');
});
</script>
</body>
</html>