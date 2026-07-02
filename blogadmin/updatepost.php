<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}
$id = $_POST['id'];
$title = $_POST['title'];
$abstract = $_POST['abstract'];
$content = $_POST['content'];
$category = $_POST['category'];
$permalink = preg_replace('/[^a-zA-Z0-9]+/', '-', trim($title));  // preserve case for transformation
$permalink = strtolower(trim($permalink, '-')); // convert after replacing


$db = new PDO('sqlite:../blogdata.db');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!empty($_FILES['image']['name'])) {
    $image = $_FILES['image']['name'];
    $target = '../uploads/' . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);
    $stmt = $db->prepare("UPDATE posts SET title=?, abstract=?, content=?, image=?, permalink=?, category=? WHERE id=?");
    $stmt->execute([$title, $abstract, $content, $image, $permalink, $category, $id]);
} else {
    $stmt = $db->prepare("UPDATE posts SET title=?, abstract=?, content=?, permalink=?, category=? WHERE id=?");
    $stmt->execute([$title, $abstract, $content, $permalink, $category, $id]);
}
header("Location: dashboard.php");
exit;
?>