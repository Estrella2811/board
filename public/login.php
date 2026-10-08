<?php
session_start();

// If POST, load the handler internally (not via browser URL)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
     $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');
    require_once __DIR__ . '/../src/Handlers/LoginHandler.php';
    exit;
}

// Redirect if already logged in
if (!empty($_SESSION['access_token'])) {
    header("Location: /PostMessage.php");
    exit;
}

$errors  = $_SESSION['form_errors']  ?? [];
$success = $_SESSION['form_success'] ?? '';
unset($_SESSION['form_errors'], $_SESSION['form_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="my_styles.css"/>
</head>
<body>
    
<div class="centralizing_container">
<div class="login_container">
<h2>Login</h2>

<?php if (!empty($errors)): ?>
    <ul style="color:red;">
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if ($success): ?>
    <p style="color:green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<!-- ✅ posts to itself -->
<form action="login.php" method="POST">
    <label>Email:
        <input type="email" name="email" required>
    </label><br><br>

    <label>Password:
        <input type="password" name="password" required>
    </label><br><br>

    <button type="submit" name="submit">Login</button>
</form>
</div>
</div>

</body>
</html>