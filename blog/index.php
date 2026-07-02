<?php
$db = new PDO('sqlite:../blogdata.db');
$permalink = $_GET['permalink'] ?? '';
$title = "Blogs | Best Law Colleges in Thrissur | CFI College of Law";
$abstract  = "Stay updated with blogs from CFI College of Law - best law college in Thrissur, featuring legal insights, case studies, comprehensive guides & exam guidance.";
// Fetch all blog posts in 'Blog' category
$all = $db->query("SELECT title, permalink, image FROM posts WHERE category = 'Blog' ORDER BY id DESC")
          ->fetchAll(PDO::FETCH_ASSOC);

if (!$permalink) {
    // Show blog list if no permalink provided
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
      <title><?=$title?></title>
      <meta name="description" content="<?=$abstract?>" />

      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

      <!-- Bootstrap Icons -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

      <style>
        body { background-color: #f4f4f4; }
        .blog-card {
          background: white;
          border-radius: 8px;
          padding: 15px;
          margin-bottom: 20px;
          display: flex;
          align-items: center;
        }
        .blog-card img {
          width: 100px;
          height: 100px;
          object-fit: cover;
          border-radius: 6px;
          margin-right: 15px;
        }
        .blog-card-title {
          font-size: 1.1rem;
          font-weight: 500;
          margin: 0;
        }
        footer { background-color: #1c2331; }
      </style>
    </head>
    <body>

    <header class="bg-light py-3 border-bottom">
      <div class="container d-flex justify-content-between align-items-center">
        <a href="/"><img src="https://cficollegeoflaw.in/images/law-logo.svg" alt="CFI Logo" height="50"></a>
        <a href="../" class="btn btn-outline-warning" title="Home">
          <i class="bi bi-house-door-fill"></i>
        </a>
      </div>
    </header>

    <div class="container mt-4 mb-5">
      <h1 class="mb-4 text-center border-bottom">CFI College of Law - Blog Posts</h1>
      <?php foreach ($all as $item): ?>
        <div class="blog-card">
          <img src="../uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
          <div>
            <p class="blog-card-title mb-1"><a href="<?= htmlspecialchars($item['permalink']) ?>" class='text-dark'><?= htmlspecialchars($item['title']) ?></a></p>
            <a href="<?= htmlspecialchars($item['permalink']) ?>" class="btn btn-sm btn-outline-primary mt-2">Read More</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <footer class="text-white text-center py-3">
      Copyright 2025, CFI College of Law. All Rights Reserved.
    </footer>

    </body>
    </html>
    <?php
    exit;
}

// Fetch main blog post
$stmt = $db->prepare("SELECT * FROM posts WHERE permalink = :permalink AND category = 'Blog'");
$stmt->execute([':permalink' => $permalink]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Blog not found or not in category 'Blog'.");
}

$title = htmlspecialchars($post['title']);
$abstract = htmlspecialchars($post['abstract']);
$image = "https://cficollegeoflaw.in/uploads/" . $post['image'];
$url = "https://cficollegeoflaw.in/blog/" . $post['permalink'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= $title ?></title>
  <meta name="description" content="<?= $abstract ?>">
  <meta property="og:title" content="<?= $title ?>" />
  <meta property="og:description" content="<?= $abstract ?>" />
  <meta property="og:image" content="<?= $image ?>" />
  <meta property="og:url" content="<?= $url ?>" />
  <meta property="og:type" content="article" />

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

  <style>
    body {
      background-color: #f4f4f4;
    }
    .blog-sidebar {
      background-color: #1c2331;
      color: #f8f9fa;
      border-radius: 8px;
      padding: 20px;
    }
    .blog-sidebar a {
      color: #ffc107;
      text-decoration: none;
    }
    .blog-sidebar a:hover {
      text-decoration: underline;
    }
    .blog-thumbnail {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
    }
    .sidebar-item {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      border-bottom: 1px solid #343a40;
      padding-bottom: 10px;
    }
    .sidebar-item-title {
      margin-left: 10px;
      font-weight: 500;
      font-size: 0.9rem;
    }
    .share-btns .btn {
      margin-right: 10px;
      margin-bottom: 10px;
      padding: 6px 10px;
    }
    footer {
      background-color: #1c2331;
    }
  </style>
</head>
<body>

<header class="bg-light py-3 border-bottom">
  <div class="container d-flex justify-content-between align-items-center">
    <a href="/"><img src="https://cficollegeoflaw.in/images/law-logo.svg" alt="CFI Logo" height="50"></a>
    <a href="../" class="btn btn-outline-warning" title="Home">
      <i class="bi bi-house-door-fill"></i>
    </a>
  </div>
</header>

<div class="container mt-4 mb-5">
  <div class="row">
    <!-- Blog Content -->
    <div class="col-md-8 mb-4">
      <h1 class="mb-3"><?= $title ?></h1>
      <img src="<?= $image ?>" alt="<?= $title ?>" class="img-fluid rounded mb-4" />
      <div><?= $post['content'] ?></div>

      <!-- Share Buttons (Icons Only using Bootstrap Icons) -->
      <div class="share-btns mt-4">
        <strong>Share:</strong><br><br>
        <a href="https://wa.me/?text=<?= urlencode($title . ' ' . $url) ?>" target="_blank" class="btn btn-success btn-sm" title="WhatsApp">
          <i class="bi bi-whatsapp"></i>
        </a>
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($url) ?>" target="_blank" class="btn btn-primary btn-sm" title="Facebook">
          <i class="bi bi-facebook"></i>
        </a>
        <a href="https://twitter.com/intent/tweet?url=<?= urlencode($url) ?>&text=<?= urlencode($title) ?>" target="_blank" class="btn btn-info btn-sm" title="Twitter">
          <i class="bi bi-twitter-x"></i>
        </a>
      </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
      <div class="blog-sidebar">
        <h5 class="text-warning mb-4">Recent Blog Posts</h5>
        <?php foreach ($all as $item): ?>
          <div class="sidebar-item">
            <img src="../uploads/<?= htmlspecialchars($item['image']) ?>" class="blog-thumbnail" alt="<?= htmlspecialchars($item['title']) ?>">
            <div class="sidebar-item-title">
              <a href="<?= htmlspecialchars($item['permalink']) ?>"><?= htmlspecialchars($item['title']) ?></a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<footer class="text-white text-center py-3">
  Copyright 2025, CFI College of Law. All Rights Reserved.
</footer>

</body>
</html>
