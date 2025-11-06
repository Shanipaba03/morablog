<?php

require_once 'config.php';
require_once 'db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

$pdo = getPDO();
$id = $_GET['id'] ?? 0;

// Verify post exists
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id=?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found.");
}

// Delete post
$delete = $pdo->prepare("DELETE FROM blog_posts WHERE id=?");
$delete->execute([$id]);

header("Location: index.php");
exit;
