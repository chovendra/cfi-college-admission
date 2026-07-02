<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Add Post</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<!-- Summernote CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>
<script>
$(document).ready(function() {
  $('textarea[name="content"]').summernote({
    height: 300
  });
});
</script>  
</head>
<body>
  <nav class="navbar navbar-dark bg-dark mb-3">
    <a href="dashboard.php" class="navbar-brand">← Back to Dashboard</a>
  </nav>
  <div class="container">
    <h3>Add New Post</h3>
    <form action="savepost.php" method="post" enctype="multipart/form-data">
      <input type="text" name="title" class="form-control mb-2" placeholder="Post Title" required>
      <textarea name="abstract" class="form-control mb-2" rows="2" placeholder="Short Abstract" required></textarea>
      <textarea name="content" class="form-control mb-2" rows="6" placeholder="Post Content" required></textarea>
      <div class="mb-2">
        <label><strong>Category:</strong></label><br>
        <label class="mr-2"><input type="radio" name="category" value="Blog" checked> Blog</label>
        <label class="mr-2"><input type="radio" name="category" value="News"> News</label>
        <label><input type="radio" name="category" value="Event"> Event</label>
      </div>
      <input type="file" name="image" class="form-control mb-2" required>
      <button type="submit" class="btn btn-primary">Save Post</button>
    </form>
  </div>
</body>
</html>