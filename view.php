
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config.php';
require_once 'db.php';

$pdo = getPDO();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(404);
    exit("Post not found.");
}

$stmt = $pdo->prepare(
    "SELECT bp.*, u.username
     FROM blog_posts bp
     JOIN users u ON bp.user_id = u.id
     WHERE bp.id = ?"
);
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    http_response_code(404);
    exit("Post not found.");
}

$canEdit = isLoggedIn()
    && (int) $_SESSION['user_id'] === (int) $post['user_id'];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= htmlspecialchars($post['title']) ?> - <?= htmlspecialchars(APP_NAME) ?></title>

<style>
    * {
        box-sizing: border-box;
    }

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
        gap: 15px;
        flex-wrap: wrap;
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

    header nav {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    header nav a {
        color: white;
        text-decoration: none;
        font-weight: 500;
    }

    header nav a:hover {
        color: #ffeb3b;
    }

    .user-greet {
        background: rgba(255,255,255,0.2);
        padding: 8px 14px;
        border-radius: 20px;
        font-weight: 500;
        color: #fff;
    }

    .btn-newpost,
    .btn-logout {
        color: #fff;
        padding: 9px 15px;
        border-radius: 22px;
        text-decoration: none;
        display: inline-block;
        transition: 0.3s;
    }

    .btn-newpost {
        background: #0056d2;
    }

    .btn-newpost:hover {
        background: #003c8f;
    }

    .btn-logout {
        background: #d32f2f;
    }

    .btn-logout:hover {
        background: #b71c1c;
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
        overflow-wrap: anywhere;
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
        gap: 12px;
        align-items: center;
        flex-wrap: wrap;
        margin-bottom: 20px;
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

    .btn-blue {
        background: #0b63d1;
    }

    .btn-blue:hover {
        background: #094ea6;
    }

    .btn-red {
        background: #e53935;
    }

    .btn-red:hover {
        background: #b71c1c;
    }

    .delete-form {
        display: inline;
        margin: 0;
    }

    button.btn-red {
        border: none;
        cursor: pointer;
        font-family: inherit;
    }

    .btn-back {
        background: #607d8b;
        text-decoration: none;
        color: white;
        padding: 9px 14px;
        border-radius: 6px;
        font-size: 14px;
        display: inline-block;
        transition: background 0.3s;
    }

    .btn-back:hover {
        background: #455a64;
    }

    footer {
        background: #3d3d3e;
        color: #fff;
        text-align: center;
        padding: 18px 10px;
        font-size: 14px;
        margin-top: auto;
    }

    @media (max-width: 600px) {
        header {
            padding: 16px;
        }

        header nav {
            gap: 8px;
        }

        .user-greet {
            width: 100%;
        }

        main {
            padding: 30px 14px;
        }

        .post-box {
            padding: 24px 18px;
        }

        .post-box h2 {
            font-size: 25px;
        }

        .content {
            font-size: 16px;
        }
    }
</style>
</head>

<body>

<header>
    <h1>
        <a href="index.php"><?= htmlspecialchars(APP_NAME) ?></a>
    </h1>

    <nav>
        <?php if (isLoggedIn()): ?>
            <span class="user-greet">
                👋 Hi, <?= htmlspecialchars($_SESSION['username']) ?>
            </span>

            <a href="new_post.php" class="btn-newpost">✏️ New Post</a>
            <a href="logout.php" class="btn-logout">🚪 Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>

<main>
    <article class="post-box">
        <h2><?= htmlspecialchars($post['title']) ?></h2>

        <div class="meta">
            By <?= htmlspecialchars($post['username']) ?>
            &bull;
            <?= htmlspecialchars($post['created_at']) ?>
        </div>

        <div class="content"><?= nl2br(htmlspecialchars($post['content'])) ?></div>

        <?php if ($canEdit): ?>
            <div class="btn-group">
                <a href="edit.php?id=<?= (int) $post['id'] ?>"
                   class="btn btn-blue">✏️ Edit Post</a>

                <form method="post"
                      action="delete.php"
                      class="delete-form"
                      onsubmit="return confirm('Are you sure you want to delete this post? This action cannot be undone.');">

                    <input type="hidden"
                           name="id"
                           value="<?= (int) $post['id'] ?>">

                    <button type="submit" class="btn btn-red">
                        🗑️ Delete Post
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <a href="index.php" class="btn-back">← Back to Home</a>
    </article>
</main>

<footer>
    © <?= date('Y') ?> <?= htmlspecialchars(APP_NAME) ?>. All rights reserved.
</footer>

</body>
</html>
