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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Message</title>
    <link rel="stylesheet" type="text/css" href="styles.css"/>
</head>
<body>

<main class="login-card">
    <div class="header-area">
        <div class="status-badge">
            <span class="status-dot"></span> Community Board
        </div>
        <h2>Post a Message</h2>
        <p>Share something with the community </p>
    </div>
    <div class="user-bar">
        <span> Logged in as: <strong>
            <?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>
        </strong> </span>
   |   <a href="logout.php">Logout</a>
    </div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success">
        <p><?= htmlspecialchars($success) ?></p>
    </div>
<?php endif; ?>

<!-- ✅ posts to itself -->
<form action="PostMessage.php" method="POST">
    <div class="form-group">
        <label>Subject:</label>
        <input type="text" name="subject"
               value="<?= htmlspecialchars($formData['subject'] ?? '') ?>"
               placeholder= "What it this about?"
               required>
    </div>

    <div class="form-group">
        <label>Name:</label>
        <input type="text" name="name"
               value="<?= htmlspecialchars($formData['name'] ?? '') ?>"
               placeholder= "Your name"
               required>
    </div>

    <div class="form-group">
        <label>Message:</label>
        <textarea name="message" rows="5"
            placeholder="Write your message..."
            required>
            <?= htmlspecialchars($formData['message'] ?? '') ?>
        </textarea>
    </div>

    <button class="btn-submit" type="submit" name="submit">Post Message</button>
</form>

</body>
</html>