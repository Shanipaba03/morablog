<?php

require_once 'config.php';
require_once 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo = getPDO();

    $login_input = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$login_input, $login_input]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

     if ($user) {
        if (password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];      // ✅ REQUIRED
        $_SESSION['username'] = $user['username'];

        header("Location: index.php");
        exit();
    } else {
        $error = "Incorrect password.";
    }
} else {
    $error = "No account found with that username.";
}

}

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - MoraBlog</title>
<style>
    body {
    background: 
      linear-gradient(rgba(11, 99, 209, 0.6), rgba(26, 35, 126, 0.6)),
      url('assets/Images/blog-background.png') no-repeat center center/cover;
    font-family: "Poppins", sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    }

    .login-box {
        background: #ffffff;
        padding: 40px 50px;
        border-radius: 20px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        width: 360px;
        text-align: center;
    }

    .login-box h1 {
        color: #0d6efd;
        margin-bottom: 25px;
        font-size: 28px;
    }

    .error {
        background-color: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 15px;
        text-align: left;
    }

    form {
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    label {
        font-weight: 600;
        margin-top: 12px;
        color: #333;
    }

    input {
        padding: 10px;
        margin-top: 6px;
        border-radius: 8px;
        border: 1px solid #ccc;
        font-size: 15px;
    }

    input:focus {
        border-color: #0d6efd;
        outline: none;
        box-shadow: 0 0 4px rgba(13, 110, 253, 0.3);
    }

    button {
        margin-top: 25px;
        padding: 10px;
        background-color: #0d6efd;
        color: white;
        font-weight: bold;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    button:hover {
        background-color: #0b5ed7;
    }

    .register-link {
        margin-top: 20px;
        color: #444;
    }

    .register-link a {
        color: #0d6efd;
        text-decoration: none;
        font-weight: 600;
    }

    .register-link a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
    <div class="login-box">
        <h1>Login</h1>

        <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="post">
            <label>Username or Email</label>
            <input type="text" name="username" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>

        <div class="register-link">
            No account? <a href="register.php">Register</a>
        </div>
    </div>
</body>
</html>
