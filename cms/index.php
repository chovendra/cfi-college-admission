<?php
session_start();

// ---------------- LOGIN ----------------
if (isset($_POST['username'], $_POST['password'])) {
    if ($_POST['username'] === 'admin' && $_POST['password'] === 'CCL@dmin') {
        $_SESSION['logged_in'] = true;
    } else {
        $error = "Invalid credentials!";
    }
}

if (!isset($_SESSION['logged_in'])):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CFI CMS - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="card shadow-lg p-4" style="max-width:350px;">
        <h4 class="text-center mb-3">CFI CMS Login</h4>
        <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="post">
            <div class="mb-3">
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>
</html>
<?php exit; endif; ?>

<?php
// ---------------- FILE LISTING ----------------
function listFiles($exts) {
    $files = [];
    foreach (glob("../*") as $file) {
        if (is_file($file)) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array($ext, $exts)) {
                $files[] = basename($file);
            }
        }
    }
    return $files;
}

$htmlFiles = listFiles(['html']);
$seoFiles  = listFiles(['xml','json','txt']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CFI CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <h3 class="text-center mb-4">CFI CMS</h3>

    <ul class="nav nav-tabs">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#content">Content Editor</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#seo">SEO</button>
        </li>
    </ul>

    <div class="tab-content bg-white shadow rounded-bottom p-4">

        <!-- CONTENT TAB -->
        <div class="tab-pane fade show active" id="content">
            <div class="mb-3">
                <label class="form-label">Select HTML File</label>
                <select id="htmlSelect" class="form-select">
                    <option value="">-- Select File --</option>
                    <?php foreach($htmlFiles as $f): ?>
                        <option value="<?= $f ?>"><?= $f ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- SEO TAB -->
        <div class="tab-pane fade" id="seo">
            <div class="mb-3">
                <label class="form-label">Select SEO File</label>
                <select id="seoSelect" class="form-select">
                    <option value="">-- Select File --</option>
                    <?php foreach($seoFiles as $f): ?>
                        <option value="<?= $f ?>"><?= $f ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

    </div>

    <!-- EDITOR AREA -->
    <div class="editor-area mt-4" style="display:none;">
        <h5 id="editorTitle"></h5>

        <div id="summerEditor"></div>

        <textarea id="plainEditor" class="form-control" rows="15" style="display:none;"></textarea>

        <button id="saveBtn" class="btn btn-success mt-3">Save</button>
        <button id="closeBtn" class="btn btn-secondary mt-3">Close</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<script>
$(function(){

    let currentFile = "";
    let currentType = "";

    function loadFile(file, type){
        if(!file) return;

        currentFile = file;
        currentType = type;

        $('#editorTitle').text("Editing: " + currentFile);
        $('.editor-area').show();

        if($('#summerEditor').next('.note-editor').length){
            $('#summerEditor').summernote('destroy');
        }

        $('#plainEditor').hide();
        $('#summerEditor').hide();

        $.get('load.php',{file:currentFile}, function(data){
            if(currentType === "html"){
                $('#summerEditor').show().summernote({
                    height: 400,
                    focus: true
                });
                $('#summerEditor').summernote('code', data);
            } else {
                $('#plainEditor').show().val(data);
            }
        });
    }

    $('#htmlSelect').on('change', function(){
        loadFile($(this).val(), "html");
    });

    $('#seoSelect').on('change', function(){
        loadFile($(this).val(), "plain");
    });

    $('#saveBtn').on('click', function(){
        if(!currentFile) return;

        let content = (currentType === "html") 
                        ? $('#summerEditor').summernote('code') 
                        : $('#plainEditor').val();

        $.post('save.php',{file:currentFile,content:content}, function(resp){
            alert(resp);
        });
    });

    $('#closeBtn').on('click', function(){
        if($('#summerEditor').next('.note-editor').length){
            $('#summerEditor').summernote('destroy');
        }

        $('.editor-area').hide();
        currentFile = "";

        $('#htmlSelect').val('');
        $('#seoSelect').val('');
    });

});
</script>

</body>
</html>
