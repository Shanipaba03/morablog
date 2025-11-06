<?php
require_once 'config.php';
require_once 'db.php';

$pdo = getPDO();

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if ($search !== '') {
    $stmt = $pdo->prepare("SELECT bp.id, bp.title, bp.content, bp.created_at, u.username 
                           FROM blog_posts bp 
                           JOIN users u ON bp.user_id = u.id 
                           WHERE bp.title LIKE :search OR bp.content LIKE :search
                           ORDER BY bp.created_at DESC");
    $stmt->execute(['search' => "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT bp.id, bp.title, bp.content, bp.created_at, u.username 
                         FROM blog_posts bp 
                         JOIN users u ON bp.user_id = u.id 
                         ORDER BY bp.created_at DESC");
}
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$colors = [
    "linear-gradient(135deg, #4e54c8, #8f94fb)",
    "linear-gradient(135deg, #0bc0b1ff, #4bb272ff)",
    "linear-gradient(135deg, #dd83f6ff, #855daaff)",
    "linear-gradient(135deg, #ff5f6d, #ffc371)",
    "linear-gradient(135deg, #00c6ff, #0072ff)",
    "linear-gradient(135deg, #6a11cb, #2575fc)"
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title><?= htmlspecialchars(APP_NAME) ?> - Home</title>
  <link rel="stylesheet" href="assets/css/style.css?v=7">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", "Segoe UI", sans-serif;
    }
    body {
      background: #eef1f7;
      color: #222;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    header {
      background: #0b63d1;
      color: #fff;
      padding: 15px 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 3px 8px rgba(0,0,0,0.15);
    }
    header h1 a { color: #fff; text-decoration: none; font-weight: 600; }
    header nav a {
      color: #fff;
      text-decoration: none;
      margin-left: 15px;
      font-weight: 500;
    }
    header nav a:hover { color: #ffeb3b; }

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

    .hero {
      background: radial-gradient(circle at 20% 30%, #0b63d1, #3949ab, #1a237e);
      color: white;
      text-align: center;
      padding: 60px 20px 50px;
      position: relative;
      overflow: hidden;
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

    .welcome-box {
      display: inline-block;
      background: rgba(255, 255, 255, 0.12);
      padding: 20px 35px;
      border-radius: 16px;
      box-shadow: 0 3px 12px rgba(0, 0, 0, 0.2);
      backdrop-filter: blur(4px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      animation: fadeIn 1s ease-in-out;
    }
    .welcome-box h2 {
      font-size: 34px;
      margin-bottom: 8px;
      font-weight: 700;
      text-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
    }
    .welcome-box h2 span {
      background: linear-gradient(45deg, #ffea00, #00e5ff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .welcome-box p {
      font-size: 16px;
      color: #e3f2fd;
      opacity: 0.95;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .hero::before, .hero::after {
      content: '';
      position: absolute;
      border-radius: 50%;
      opacity: 0.2;
      animation: float 10s infinite ease-in-out alternate;
    }
    .hero::before {
      width: 250px; height: 250px;
      top: -80px; left: -80px;
      background: #ffeb3b;
      animation-delay: 2s;
    }
    .hero::after {
      width: 300px; height: 300px;
      bottom: -100px; right: -100px;
      background: #00e5ff;
    }
    @keyframes float {
      0% { transform: translate(0, 0); }
      100% { transform: translate(30px, 30px) scale(1.1); }
    }

    main.container {
      flex: 1;
      max-width: 1200px;
      margin: 40px auto;
      padding: 20px;
    }

    .search-box {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      margin-bottom: 35px;
      flex-wrap: wrap;
    }
    .search-box input[type="text"] {
      width: 300px;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 14px;
    }
    .search-box button {
      padding: 10px 18px;
      border: none;
      border-radius: 8px;
      background: #0b63d1;
      color: white;
      font-size: 14px;
      cursor: pointer;
      transition: 0.3s;
    }
    .search-box button:hover {
      background: #094ea6;
    }

    h3.section-title {
      text-align: center;
      font-size: 28px;
      color: #1a237e;
      margin-bottom: 25px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(3,1fr);
      gap: 28px;
      justify-content: center;
      align-items: stretch;
    }
    @media (max-width: 1000px) {
      .grid {
        grid-template-columns: repeat(2, 1fr); /* 2 columns */
    }
  }
    @media (max-width: 640px) {
     .grid {
       grid-template-columns: 1fr; /* 1 column on phones */
    }
  }

    .post-card {
      color: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.15);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 220px;
      transition: 0.3s ease;
    }
    .post-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 18px rgba(0,0,0,0.2);
    }
    .post-card a.title {
      font-size: 20px;
      text-align:center;
      font-weight: 600;
      color: #010513ff;
      text-decoration: none;
      font-family:sans-serif;
      margin-bottom: 8px;
      display: inline-block;
    }
    .meta {
      font-size: 13px;
      opacity: 0.9;
      margin-bottom: 10px;
    }
    .post-card p {
      font-size: 14px;
      line-height: 1.6;
      opacity: 0.95;
    }
    .readmore {
      align-self: flex-start;
      background: rgba(255, 255, 255, 0.9);
      color: #0b63d1;
      padding: 8px 14px;
      border-radius: 6px;
      text-decoration: none;
      transition: 0.3s;
      font-size: 14px;
      font-weight: 500;
      margin-top: 10px;
    }
    .readmore:hover {
      background: white;
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
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
    <?php endif; ?>
  </nav>
</header>

<section class="hero">
  <div class="welcome-box">
    <h2>Welcome to <span><?= htmlspecialchars(APP_NAME) ?></span></h2>
    <p>Where every voice matters, and every story shines ✨</p>
  </div>
</section>

<main class="container">
  <form method="get" class="search-box">
    <input type="text" name="search" placeholder="Search posts..." value="<?= htmlspecialchars($search) ?>">
    <button type="submit">Search</button>
  </form>

  <h3 class="section-title">Recent Posts</h3>

  <?php if(!$posts): ?>
    <p style="text-align:center;">No posts found.</p>
  <?php else: ?>
    <div class="grid">
      <?php 
      $i = 0;
      foreach($posts as $p): 
          $bg = $colors[$i % count($colors)];
          $i++;
      ?>
      <div class="post-card" style="background: <?= $bg ?>;">
        <div>
          <a href="view.php?id=<?= $p['id'] ?>" class="title"><?= htmlspecialchars($p['title']) ?></a>
          <div class="meta">By <?= htmlspecialchars($p['username']) ?> • <?= htmlspecialchars(date('Y-m-d', strtotime($p['created_at']))) ?></div>
          <p><?= nl2br(htmlspecialchars(substr($p['content'], 0, 140))) ?>...</p>
        </div>
        <a href="view.php?id=<?= $p['id'] ?>" class="readmore">Read More</a>
      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<footer>
  © <?= date('Y') ?> <?= htmlspecialchars(APP_NAME) ?>. All rights reserved.
</footer>

</body>

</html>
