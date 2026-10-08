<?php
session_start();

// If POST, load the handler internally
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    require_once __DIR__ . '/../src/Handlers/PostMessageHandler.php';
    exit;
}

// Redirect to login if not authenticated
if (empty($_SESSION['access_token'])) {
    header("Location: /login.php");
    exit;
}

$errors   = $_SESSION['form_errors']  ?? [];
$success  = $_SESSION['form_success'] ?? '';
$formData = $_SESSION['form_data']    ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_success'], $_SESSION['form_data']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post a Message</title>
</head>
<body>

<h2>Post a Message</h2>
<p>Logged in as: <strong><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></strong>
   | <a href="logout.php">Logout</a>
</p>

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
<form action="PostMessage.php" method="POST">
    <label>Subject:
        <input type="text" name="subject"
               value="<?= htmlspecialchars($formData['subject'] ?? '') ?>" required>
    </label><br><br>

    <label>Name:
        <input type="text" name="name"
               value="<?= htmlspecialchars($formData['name'] ?? '') ?>" required>
    </label><br><br>

    <label>Message:<br>
        <textarea name="message" rows="5" cols="40" required><?= htmlspecialchars($formData['message'] ?? '') ?></textarea>
    </label><br><br>

    <button type="submit" name="submit">Post Message</button>
</form>

</body>
</html>