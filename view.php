<?php
require_once 'config.php';
require_once 'db.php';

$pdo = getPDO();
$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT bp.*, u.username FROM blog_posts bp JOIN users u ON bp.user_id = u.id WHERE bp.id=?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found.");
}

$canEdit = isLoggedIn() && $_SESSION['user_id'] == $post['user_id'];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?= htmlspecialchars($post['title']) ?> - <?= htmlspecialchars(APP_NAME) ?></title>
<style>
    body {
        font-family: "Poppins", "Segoe UI", sans-serif;
        background: #f3f5fa;
        margin: 0;
        color: #333;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    header {
        background: #0b63d1;
        color: white;
        padding: 16px 25px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
    }

    header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
    }

    header h1 a {
        color: white;
        text-decoration: none;
    }

    header nav a {
        color: white;
        text-decoration: none;
        margin-left: 15px;
        font-weight: 500;
    }

    header nav a:hover {
        color: #ffeb3b;
    }

    .btn-newpost {
      background: linear-gradient(90deg, #007bff, #0056d2);
      color: #fff;
      padding: 8px 16px;
      border-radius: 20px;
      font-weight: 500;
      text-decoration: none;
      transition: background 0.3s, transform 0.2s;
  }
  .btn-newpost:hover {
      background: linear-gradient(90deg, #0056d2, #003c8f);
      transform: translateY(-2px);
  }

   .btn-logout {
      background: linear-gradient(90deg, #f85f5ff0, #da4b4bff);
      color: #fff;
      padding: 8px 16px;
      border-radius: 25px;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s;
  }

  .btn-logout:hover {
      background: linear-gradient(90deg, #d32f2f, #b71c1c);
      transform: translateY(-2px);
  }

    .user-greet {
       background: rgba(255,255,255,0.2);
       padding: 8px 14px;
       border-radius: 20px;
       font-weight: 500;
       color: #fff;
       letter-spacing: 0.3px;
       margin-right: 10px;
       transition: background 0.3s;
    }

    .user-greet:hover {
      background: rgba(255,255,255,0.35);
    }

    main {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 60px 20px;
    }

    .post-box {
        background: white;
        max-width: 800px;
        width: 100%;
        border-radius: 14px;
        padding: 40px;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
        border-top: 6px solid #0b63d1;
    }

    .post-box h2 {
        margin-top: 0;
        font-size: 30px;
        color: #1a237e;
        font-weight: 700;
        border-bottom: 2px solid #e3f2fd;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .meta {
        color: #607d8b;
        font-size: 14px;
        margin-bottom: 25px;
    }

    .content {
        font-size: 17px;
        line-height: 1.7;
        color: #333;
        margin-bottom: 35px;
        white-space: pre-line;
    }

    .btn-group {
        display: flex;
        gap: 15px;
    }

    .btn {
        text-decoration: none;
        color: white;
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        transition: 0.3s;
        display: inline-block;
        text-align: center;
    }

    .btn-blue { background: #0b63d1; }
    .btn-blue:hover { background: #094ea6; }

    .btn-red { background: #e53935; }
    .btn-red:hover { background: #b71c1c; }

    .btn-back {
        background: #607d8b;
        text-decoration: none;
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        font-size: 14px;
        margin-top: 15px;
        display: inline-block;
        transition: background 0.3s;
    }

    .btn-back:hover { background: #455a64; }

    footer {
        background: #3d3d3eff;
        color: #fff;
        text-align: center;
        padding: 18px 0;
        font-size: 14px;
    }
</style>
</head>
<body>

<header>
    <h1><a href="index.php"><?= htmlspecialchars(APP_NAME) ?></a></h1>
    <nav>
        <?php if (isLoggedIn()): ?>
            <span class="user-greet">👋 Hi, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="create.php" class="btn-newpost">✏️ New Post</a>
            <a href="logout.php" class="btn-logout">🚪 Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>

<main>
    <div class="post-box">
        <h2><?= htmlspecialchars($post['title']) ?></h2>
        <div class="meta">
            by <?= htmlspecialchars($post['username']) ?> • <?= htmlspecialchars($post['created_at']) ?>
        </div>
        <div class="content"><?= nl2br(htmlspecialchars($post['content'])) ?></div>

        <?php if ($canEdit): ?>
        <div class="btn-group">
            <a href="edit.php?id=<?= $post['id'] ?>" class="btn btn-blue">Edit</a>
            <a href="delete.php?id=<?= $post['id'] ?>" class="btn btn-red" 
               onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
        </div>
        <?php endif; ?>

        <a href="index.php" class="btn-back">← Back to Home</a>
    </div>
</main>

<footer>
  © <?= date('Y') ?> <?= htmlspecialchars(APP_NAME) ?>. All rights reserved.
</footer>

</body>
</html>

