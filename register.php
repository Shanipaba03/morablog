<?php
require_once 'config.php';
require_once 'db.php'; 
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo = getPDO();

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = trim($_POST['confirm_password']);

     if (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        $existing = $stmt->fetch();

        if ($existing) {
            $error = "Username or email already exists.";
        } else {
           $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                $success = "Registration successful! Redirecting to login page...";
                header("refresh:2;url=login.php");
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }

}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Register - <?=htmlspecialchars(APP_NAME)?></title>
<link rel="stylesheet" href="assets/css/style.css">
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

    .register-box {
        background: #ffffff;
        padding: 40px 50px;
        border-radius: 20px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        width: 380px;
        text-align: center;
    }

    .register-box h1 {
        color: #0d6efd; 
        margin-bottom: 25px;
        font-size: 28px;
    }

    .error {
        background-color: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
    }

    .success {
        background-color: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
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

    .login-link {
        margin-top: 20px;
        color: #444;
    }

    .login-link a {
        color: #0d6efd;
        text-decoration: none;
        font-weight: 600;
    }

    .login-link a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
   <div class="register-box">
        <h1>Create Account</h1>

         <?php if (!empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="register.php" method="post">
            <label>Username</label>
            <input type="text" name="username" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Register</button>
        </form>
        <div class="login-link">
            Already have an account? <a href="login.php">Login</a>
        </div>
    </div>

    <script>
      function validateForm() {
         const password = document.getElementById('password').value;
         const confirm = document.getElementById('confirm_password').value;

         if (password.length < 6) {
             alert("Password must be at least 6 characters long.");
             return false;
        }

        if (password !== confirm) {
            alert("Passwords do not match.");
            return false;
        }

      return true;
     }
   </script>
</body>
</html>

