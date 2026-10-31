<?php
require_once 'core/dbConfig.php';

$message = "";

if (isset($_POST['register'])) {

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (
        empty($first_name) ||
        empty($last_name) ||
        empty($username) ||
        empty($email) ||
        empty($password) ||
        empty($confirm_password)
    ) {
        $message = "Please fill in all fields.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email.";
    }
    elseif ($password != $confirm_password) {
        $message = "Passwords do not match!";
    }
    elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters!";
    }
    else {

        $check = $pdo->prepare(
            "SELECT id FROM users WHERE username = ? OR email = ?"
        );

        $check->execute([$username, $email]);

        if ($check->fetch()) {
            $message = "Username or email already exists!";
        }
        else {

            $password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users
                    (first_name, last_name, username, email, password)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $first_name,
                $last_name,
                $username,
                $email,
                $password
            ]);

            $message = "Registration successful! You can now login.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register | AAA Studio</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #321D27;
            margin: 0;
            padding: 40px 15px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background: #F1E6D8;
            width: 100%;
            max-width: 430px;
            padding: 35px;
            border-radius: 12px;
        }

        .logo {
            text-align: center;
            color: #572C3A;
            font-size: 25px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .subtitle {
            text-align: center;
            color: #8A625F;
            font-size: 11px;
            letter-spacing: 2px;
            margin-bottom: 30px;
        }

        h2 {
            color: #321D27;
            margin-bottom: 5px;
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
            margin-top: 15px;
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
            color: #572C3A;
            text-align: center;
            font-size: 13px;
            margin-top: 15px;
        }

        .login {
            text-align: center;
            margin-top: 22px;
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

    <h2>Create your account.</h2>

    <p class="description">
        Join the studio management workspace.
    </p>

    <?php if (!empty($message)) { ?>
        <div class="message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php } ?>

    <form method="POST">

        <label>First Name</label>
        <input type="text" name="first_name" required>

        <label>Last Name</label>
        <input type="text" name="last_name" required>

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Email Address</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password"
        minlength="8" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password"
        minlength="8" required>

        <button type="submit" name="register">
            CREATE ACCOUNT
        </button>

    </form>

    <div class="login">
        Already have an account?
        <a href="login.php">Login here</a>
    </div>

</div>

</body>
</html>