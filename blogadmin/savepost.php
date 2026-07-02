<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
$title = $_POST['title'];
$abstract = $_POST['abstract'];
$content = $_POST['content'];
$category = $_POST['category'];
$date = date('Y-m-d');
$permalink = preg_replace('/[^a-zA-Z0-9]+/', '-', trim($title));  // preserve case for transformation
$permalink = strtolower(trim($permalink, '-')); // convert after replacing


$image = $_FILES['image']['name'];
$target = '../uploads/' . basename($image);
move_uploaded_file($_FILES['image']['tmp_name'], $target);
$db = new PDO('sqlite:../blogdata.db');
$stmt = $db->prepare("INSERT INTO posts (date, title, abstract, content, image, permalink, category) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->execute([$date, $title, $abstract, $content, $image, $permalink, $category]);
header("Location: dashboard.php");
exit;
?>