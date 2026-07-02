<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
$db = new PDO('sqlite:../blogdata.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$posts = $db->query("SELECT * FROM posts ORDER BY id DESC");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
</head>
<body>
  <nav class="navbar navbar-dark bg-dark mb-3">
    <span class="navbar-brand">Blog Manager</span>
    <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
  </nav>
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3><i class="fas fa-newspaper"></i> Blog Posts</h3>
      <a href="addpost.php" class="btn btn-success"><i class="fas fa-plus"></i> Add New</a>
    </div>
    <?php foreach ($posts as $post): ?>
    <div class="card mb-3">
      <div class="card-body">
        <h5><?php echo htmlspecialchars($post['title']); ?></h5>
        <p class="text-muted">
          <?php echo htmlspecialchars($post['date']); ?> |
          <strong>Category:</strong> <?php echo htmlspecialchars($post['category']); ?> |
          <strong>Permalink:</strong> <?php echo htmlspecialchars($post['permalink']); ?>
        </p>
        <p><?php echo nl2br(htmlspecialchars($post['abstract'])); ?></p>
        <a href="editpost.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-info">
          <i class="fas fa-edit"></i> Edit
        </a>
        <a href="deletepost.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this post?');">
          <i class="fas fa-trash"></i> Delete
        </a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</body>
</html>