<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
$db = new PDO('sqlite:../blogdata.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$id = $_GET['id'];
$post = $db->query("SELECT * FROM posts WHERE id = $id")->fetch();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Post</title>
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
    <h3>Edit Post</h3>
    <form action="updatepost.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
      <input type="text" name="title" class="form-control mb-2" value="<?php echo htmlspecialchars($post['title']); ?>" required>
      <textarea name="abstract" class="form-control mb-2" rows="2" required><?php echo htmlspecialchars($post['abstract']); ?></textarea>
      <textarea name="content" class="form-control mb-2" rows="6" required><?php echo htmlspecialchars($post['content']); ?></textarea>
      <div class="mb-2">
        <label><strong>Category:</strong></label><br>
        <label class="mr-2"><input type="radio" name="category" value="Blog" <?php if ($post['category'] == 'Blog') echo 'checked'; ?>> Blog</label>
        <label class="mr-2"><input type="radio" name="category" value="News" <?php if ($post['category'] == 'News') echo 'checked'; ?>> News</label>
        <label><input type="radio" name="category" value="Event" <?php if ($post['category'] == 'Event') echo 'checked'; ?>> Event</label>
      </div>
      <p>Current Image: <strong><?php echo htmlspecialchars($post['image']); ?></strong></p>
      <input type="file" name="image" class="form-control mb-2">
      <button type="submit" class="btn btn-primary">Update Post</button>
    </form>
  </div>
</body>
</html>