<?php
include 'auth.php';
include 'db.php';

$id = $_GET['id'] ?? 0;
$stmt = $db->prepare("DELETE FROM posts WHERE id = ?");
$stmt->execute([$id]);

header("Location: dashboard.php");
exit;
?>