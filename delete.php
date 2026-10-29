
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';
require_once 'db.php';

if (!isLoggedIn()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$pdo = getPDO();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: index.php");
    exit;
}

// Check whether the post belongs to the logged-in user
$stmt = $pdo->prepare(
    "SELECT id FROM blog_posts WHERE id = ? AND user_id = ?"
);
$stmt->execute([$id, $_SESSION['user_id']]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(403);
    exit("You are not authorized to delete this post.");
}

// Delete only the owner's post
$delete = $pdo->prepare(
    "DELETE FROM blog_posts WHERE id = ? AND user_id = ?"
);
$delete->execute([$id, $_SESSION['user_id']]);

header("Location: index.php");
exit;
