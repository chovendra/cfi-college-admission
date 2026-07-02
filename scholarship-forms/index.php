<?php
//Mailer
//require_once __DIR__ . '/mailer.php';
// Database Connection
$db = new SQLite3('scholarships.db');
$message = "";
// Form Submission
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $stmt = $db->prepare("INSERT INTO ScholarshipApplications(FullName,Gender,DateOfBirth,Age,MobileNumber,EmailId,ResidentialAddress,ParentName,ParentContactNumber,ParentEmailId,SchoolName,BoardOfExamination,PlusTwoStream,YearOfPassing,PreferredCourse,Subject1,MaxMarks1,MarksObtained1,Percentage1,Subject2,MaxMarks2,MarksObtained2,Percentage2,Subject3,MaxMarks3,MarksObtained3,Percentage3,Subject4,MaxMarks4,MarksObtained4,Percentage4,Subject5,MaxMarks5,MarksObtained5,Percentage5,Subject6,MaxMarks6,MarksObtained6,Percentage6,Subject7,MaxMarks7,MarksObtained7,Percentage7,Subject8,MaxMarks8,MarksObtained8,Percentage8,Subject9,MaxMarks9,MarksObtained9,Percentage9,Subject10,MaxMarks10,MarksObtained10,Percentage10,TotalAggregate,DeclarationAccepted,PlaceName,ApplicationDate) VALUES (:FullName,:Gender,:DateOfBirth,:Age,:MobileNumber,:EmailId,:ResidentialAddress,:ParentName,:ParentContactNumber,:ParentEmailId,:SchoolName,:BoardOfExamination,:PlusTwoStream,:YearOfPassing,:PreferredCourse,:Subject1,:MaxMarks1,:MarksObtained1,:Percentage1,:Subject2,:MaxMarks2,:MarksObtained2,:Percentage2,:Subject3,:MaxMarks3,:MarksObtained3,:Percentage3,:Subject4,:MaxMarks4,:MarksObtained4,:Percentage4,:Subject5,:MaxMarks5,:MarksObtained5,:Percentage5,:Subject6,:MaxMarks6,:MarksObtained6,:Percentage6,:Subject7,:MaxMarks7,:MarksObtained7,:Percentage7,:Subject8,:MaxMarks8,:MarksObtained8,:Percentage8,:Subject9,:MaxMarks9,:MarksObtained9,:Percentage9,:Subject10,:MaxMarks10,:MarksObtained10,:Percentage10,:TotalAggregate,:DeclarationAccepted,:PlaceName,:ApplicationDate)");
    foreach($_POST as $key => $value){
        $stmt->bindValue(':'.$key,trim($value));
    }
	$stmt->execute();

	/*
	// OLD SUCCESS MESSAGE METHOD (KEPT FOR REFERENCE)
	$message = "Application Submitted Successfully!";
	*/

	// TEMPORARY REDIRECT TO THANK YOU PAGE
	header("Location: ../scholarship-thankyou/");
	exit;
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

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PQ2XZB8T');</script>
<!-- End Google Tag Manager -->

</head>
<body>

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PQ2XZB8T"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

<!-- Header -->
<div class="header">
    <a href="/"><img src="https://cficollegeoflaw.in/images/logo.png" alt="CFI Logo"></a>
</div>
<!-- Hero Section -->
<div class="hero-section text-center">
<div class="container">
<h1>Scholarship Examination 2026-2027</h1>
<h3>Application Form</h3>
<div class="hero-info">
<div class="row text-center g-4">
<div class="col-md-3 col-6">
<div class="mb-3"><i class="fa-solid fa-calendar-days fa-3x"></i></div>
<div><strong>Reporting Date</strong></div>
<div>13th June 2026</div>
</div>
<div class="col-md-3 col-6">
<div class="mb-3"><i class="fa-solid fa-clock fa-3x"></i></div>
<div><strong>Reporting Time</strong></div>
<div>09.30 AM</div>
</div>
<div class="col-md-3 col-6">
<div class="mb-3"><i class="fa-solid fa-location-dot fa-3x"></i></div>
<div><strong>Venue</strong></div>
<div>CFI College of Law</div>
<div>Poyya, Thrissur Dist.</div>
</div>
<div class="col-md-3 col-6">
<div class="mb-3"><i class="fa-solid fa-phone-volume fa-3x"></i></div>
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
<div class="mb-4"><i class="fa-solid fa-circle-check text-success" style="font-size:90px;"></i></div>
<h2 class="fw-bold text-success mb-3">Application Submitted Successfully</h2>
<p class="lead mb-4">Thank you for applying for the CFI College of Law Scholarship Examination 2026–2027.</p>
<p class="text-muted mb-5">Shortlisted candidates will be contacted by our admissions team with further details regarding the examination.</p>
<a href="../index.php" class="btn btn-primary btn-lg px-5"><i class="fa-solid fa-house me-2"></i> Back To Home</a>
</div>
<?php } else { ?>
<form method="POST">
<!-- Candidate Profile -->
<div class="section-title">Section 1 : Candidate Profile</div>
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
<div class="section-title mt-5">Section 2 : Parent / Guardian Details</div>
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
<div class="section-title mt-5">Section 3 : Academic Details</div>
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
<div class="section-title mt-5">Section 4 : Plus Two — Subject & Marks<br>Enter your Plus Two Board Exam marks. Enter all subjects.</div>
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
<?php for($i=1;$i<=10;$i++){ ?>
<tr>
<td><?php echo $i; ?></td>
<td><input type="text" name="Subject<?php echo $i; ?>" class="form-control" <?php echo ($i<=4)?'required':''; ?>></td>
<td><input type="number" name="MaxMarks<?php echo $i; ?>" id="MaxMarks<?php echo $i; ?>" class="form-control mark-input" min="1" <?php echo ($i<=4)?'required':''; ?>></td>
<td><input type="number" name="MarksObtained<?php echo $i; ?>" id="MarksObtained<?php echo $i; ?>" class="form-control mark-input" min="0" <?php echo ($i<=4)?'required':''; ?>></td>
<td><input type="text" name="Percentage<?php echo $i; ?>" id="Percentage<?php echo $i; ?>" class="form-control" readonly></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<!-- Aggregate -->
<div class="row mt-4">
<div class="col-md-4">
<label class="form-label">Total / Aggregate</label>
<input type="text" name="TotalAggregate" id="TotalAggregate" class="form-control" >
</div>
</div>
<!-- Declaration -->
<div class="section-title mt-5">Declaration</div>
<div class="form-check mb-4">
<input class="form-check-input" type="checkbox" name="DeclarationAccepted" value="Yes" required>
<label class="form-check-label">I hereby declare that the information provided above is true to the best of my knowledge.</label>
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
<button type="submit" class="btn-submit">Submit Application</button>
</div>
</form>
<?php } ?>
<hr class="my-5">
<div class="text-center footer-text">CFI College of Law, Poyya, Thrissur Dist., Kerala | admissions@cficollegeoflaw.in</div>
</div>
</div>
<!-- Scripts -->
<script>
const dobInput = document.getElementById('DateOfBirth');
const ageInput = document.getElementById('Age');
if(dobInput){
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
}
const addressInput = document.getElementById('ResidentialAddress');
if(addressInput){
    addressInput.addEventListener('input',function(){
        this.value = this.value.replace(/[^A-Za-z0-9,\-\s]/g,'');
    });
}
function calculateMarks(){
    let totalMax = 0;
    let totalObtained = 0;
    for(let i=1;i<=10;i++){
        let maxMarks = parseFloat(document.getElementById('MaxMarks'+i).value) || 0;
        let obtained = parseFloat(document.getElementById('MarksObtained'+i).value) || 0;
        let percentageField = document.getElementById('Percentage'+i);
        if(maxMarks > 0 && obtained >= 0){
            if(obtained > maxMarks){
                document.getElementById('MarksObtained'+i).value = maxMarks;
                obtained = maxMarks;
            }
            let percentage = (obtained / maxMarks) * 100;
            percentageField.value = percentage.toFixed(2);
            totalMax += maxMarks;
            totalObtained += obtained;
        }else{
            percentageField.value = "";
        }
    }

}
document.querySelectorAll('.mark-input').forEach(function(input){
    input.addEventListener('input',calculateMarks);
});
</script>


</body>
</html>