<?php
session_start();

require_once 'core/dbConfig.php';

$message = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['first_name'] = $user['first_name'];

        header("Location: index.php");
        exit();

    } else {
        $message = "Incorrect username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | AAA Studio</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #321D27;
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: #F1E6D8;
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 12px;
        }

        .logo {
            color: #572C3A;
            text-align: center;
            font-size: 25px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .subtitle {
            color: #8A625F;
            text-align: center;
            font-size: 11px;
            letter-spacing: 2px;
            margin-bottom: 35px;
        }

        h2 {
            color: #321D27;
            margin-bottom: 8px;
        }

        .description {
            color: #795D5A;
            font-size: 14px;
            margin-bottom: 25px;
        }

        label {
            display: block;
            color: #572C3A;
            font-weight: bold;
            font-size: 13px;
            margin-top: 18px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 7px;
            border: 1px solid #D0B9AD;
            border-radius: 6px;
            background: white;
            outline: none;
        }

        input:focus {
            border-color: #C47B67;
        }

        button {
            width: 100%;
            padding: 13px;
            margin-top: 25px;
            background: #572C3A;
            color: #F1E6D8;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #321D27;
        }

        .message {
            color: #8A454F;
            text-align: center;
            font-size: 13px;
            margin-top: 15px;
        }

        .register {
            text-align: center;
            margin-top: 25px;
            font-size: 13px;
            color: #795D5A;
        }

        a {
            color: #8A454F;
            font-weight: bold;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="logo">AAA STUDIO</div>

    <div class="subtitle">
        GAME DEVELOPMENT MANAGEMENT
    </div>

    <h2>Welcome back.</h2>

    <p class="description">
        Login to access your studio workspace.
    </p>

    <?php if (!empty($message)) { ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php } ?>

    <form method="POST">

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="login">
            LOGIN TO YOUR ACCOUNT
        </button>

    </form>

    <div class="register">
        Don't have an account?
        <a href="register.php">Register here</a>
    </div>

</div>

</body>
</html>