<?php
session_start();

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
    <div class="card shadow-lg p-4" style="max-width:370px;">
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
function listEditableFiles() {
    $root = dirname(__DIR__);
    $files = [];
    $allowedExts = ['html', 'json', 'txt', 'xml', 'csv'];
    $excluded = ['.DS_Store', 'error_log'];

    foreach (glob($root . '/*') as $path) {
        if (!is_file($path)) {
            continue;
        }

        $name = basename($path);
        if (in_array($name, $excluded, true)) {
            continue;
        }

        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExts, true)) {
            continue;
        }

        $group = 'Content Files';
        if (in_array($ext, ['json', 'xml', 'txt', 'csv'], true)) {
            $group = 'Data Files';
        }

        $files[] = [
            'path' => ltrim(str_replace($root . '/', '', $path), '/'),
            'name' => $name,
            'group' => $group,
            'ext' => $ext
        ];
    }

    usort($files, function ($a, $b) {
        return strcmp($a['name'], $b['name']);
    });

    return $files;
}

$editableFiles = listEditableFiles();
$grouped = [];
foreach ($editableFiles as $file) {
    $grouped[$file['group']][] = $file;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CFI CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        body { background: #f4f7fb; }
        .card { border: 0; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,.08); }
        .file-list { max-height: 500px; overflow: auto; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">CFI CMS</h3>
            <p class="text-muted mb-0">Edit pages, SEO data, and website content safely from one place.</p>
        </div>
        <a href="../" class="btn btn-outline-secondary btn-sm">Back to Site</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Editable Files</h5>
                    <input id="fileSearch" type="text" class="form-control mb-3" placeholder="Search files...">
                    <div class="file-list">
                        <?php foreach ($grouped as $groupName => $files): ?>
                            <div class="mb-3">
                                <div class="fw-bold text-primary mb-2"><?= htmlspecialchars($groupName) ?></div>
                                <?php foreach ($files as $file): ?>
                                    <button type="button"
                                            class="btn btn-outline-secondary btn-sm w-100 text-start mb-2 file-item"
                                            data-file="<?= htmlspecialchars($file['path']) ?>"
                                            data-type="<?= $file['ext'] === 'html' ? 'html' : 'plain' ?>">
                                        <?= htmlspecialchars($file['name']) ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="card-title mb-1">Editor</h5>
                            <p id="editorInfo" class="text-muted mb-0">Select a file to start editing.</p>
                        </div>
                    </div>

                    <div class="editor-area" style="display:none;">
                        <h6 id="editorTitle" class="mb-3"></h6>
                        <div id="summerEditor"></div>
                        <textarea id="plainEditor" class="form-control" rows="18" style="display:none;"></textarea>

                        <div class="mt-3 d-flex gap-2">
                            <button id="saveBtn" class="btn btn-success">Save</button>
                            <button id="closeBtn" class="btn btn-secondary">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
        $('#editorInfo').text('Editing file: ' + file);
        $('#editorTitle').text(file);
        $('.editor-area').show();

        if($('#summerEditor').next('.note-editor').length){
            $('#summerEditor').summernote('destroy');
        }

        $('#plainEditor').hide();
        $('#summerEditor').hide();

        $.get('load.php',{file:currentFile}, function(data){
            if(currentType === 'html'){
                $('#summerEditor').show().summernote({height: 420, focus: true});
                $('#summerEditor').summernote('code', data);
            } else {
                $('#plainEditor').show().val(data);
            }
        });
    }

    $('.file-item').on('click', function(){
        loadFile($(this).data('file'), $(this).data('type'));
    });

    $('#fileSearch').on('keyup', function(){
        const term = $(this).val().toLowerCase();
        $('.file-item').each(function(){
            const text = $(this).text().toLowerCase();
            $(this).toggle(text.includes(term));
        });
    });

    $('#saveBtn').on('click', function(){
        if(!currentFile) return;
        const content = (currentType === 'html')
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
        currentFile = '';
        currentType = '';
        $('#editorInfo').text('Select a file to start editing.');
        $('#editorTitle').text('');
    });
});
</script>
</body>
</html>
