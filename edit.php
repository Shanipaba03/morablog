<?php
require_once 'config.php';
require_once 'db.php';

if(!isLoggedIn()) {
  header("Location: login.php");
  exit;
}

$pdo = getPDO();
$id = $_GET['id'] ?? 0;

// Fetch post
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post || $post['user_id'] != $_SESSION['user_id']) {
  die("You are not authorized to edit this post.");
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    if ($title === "") $errors[] = "Title is required.";
    if ($content === "") $errors[] = "Content is required.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE blog_posts SET title=?, content=? WHERE id=?");
        $stmt->execute([$title, $content, $id]);
        header("Location: view.php?id=" . $id);
        exit;
    } else {
        $error = "Please fill in all fields.";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Edit Post – <?= htmlspecialchars(APP_NAME) ?></title>
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

    /* ===== HEADER ===== */
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

    nav {
        display: flex;
        align-items: center;
        gap: 12px;
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

    .btn-newpost {
        background: linear-gradient(90deg, #007bff, #0056d2);
        color: #fff;
        padding: 8px 16px;
        border-radius: 25px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s;
    }

    .btn-newpost:hover {
        background: linear-gradient(90deg, #0056d2, #003c8f);
        transform: translateY(-2px);
    }

    .btn-logout {
        background: linear-gradient(90deg, #ff4d4d, #d32f2f);
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

    /* ===== MAIN CONTENT ===== */
    main {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 60px 20px;
    }

    .form-box {
        background: white;
        max-width: 800px;
        width: 100%;
        border-radius: 14px;
        padding: 40px;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
        border-top: 6px solid #0b63d1;
    }

    .form-box h2 {
        text-align: center;
        margin-top: 0;
        font-size: 28px;
        color: #1a237e;
        font-weight: 700;
        margin-bottom: 25px;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    label {
        font-weight: 500;
        margin-bottom: 5px;
        display: block;
        color: #333;
    }

    input[type="text"],
    textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #ccd1d9;
        border-radius: 8px;
        font-size: 15px;
        resize: vertical;
        font-family: inherit;
    }

    textarea {
        min-height: 180px;
    }

    button {
        align-self: flex-start;
        background-color: #0b63d1;
        color: white;
        font-size: 15px;
        font-weight: 500;
        padding: 10px 18px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
    }

    button:hover {
        background-color: #094ea6;
    }

    .error {
        color: #d32f2f;
        margin-bottom: 15px;
        text-align: center;
        font-weight: 500;
    }

    .back {
        display: inline-block;
        margin-top: 20px;
        text-decoration: none;
        color: #0b63d1;
        font-weight: 500;
        font-size: 14px;
        transition: color 0.2s;
    }

    .back:hover {
        color: #003c8f;
    }

    footer {
        background: #3d3d3eff;
        color: #fff;
        text-align: center;
        padding: 18px 0;
        font-size: 14px;
        margin-top: auto;
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
      <a href="login.php" class="basic-link">Login</a>
      <a href="register.php" class="basic-link">Register</a>
    <?php endif; ?>
  </nav>
</header>

<main>
  <div class="form-box">
    <h2>Edit Your Post</h2>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
      <div>
        <label for="title">Post Title</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
      </div>
      <div>
        <label for="content">Content</label>
        <textarea id="content" name="content" required><?= htmlspecialchars($post['content']) ?></textarea>
      </div>
      <button type="submit">Update Post</button>
    </form>

    <a href="view.php?id=<?= $post['id'] ?>" class="back">← Back to Post</a>
  </div>
</main>

<footer>
  © <?= date('Y') ?> <?= htmlspecialchars(APP_NAME) ?>. All rights reserved.
</footer>

</body>
</html>
