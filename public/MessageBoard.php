<?php
// MessageBoard.php
// ---------------------------------------------------------
// Displays all messages belonging to the logged-in user.
// Replaces flat-file messages.txt reading with Supabase query.
// ---------------------------------------------------------

session_start();

// Redirect to login if not authenticated
if (empty($_SESSION['access_token'])) {
    header("Location: login.php");
    exit;
}

// ✅ __DIR__ goes up one level from public/ to project root
require_once '../src/Models/MessageModel.php';

// Fetch only the logged-in user's messages from Supabase
$messages = get_user_messages();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Board</title>
</head>
<body>

    <h1>Message Board</h1>

    <p>
        Logged in as: <strong><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></strong>
        | <a href="logout.php">Logout</a>
    </p>

    <p><a href="PostMessage.php">Post a New Message</a></p>

    <?php if (empty($messages)): ?>

        <p>There are no messages posted.</p>

    <?php else: ?>

        <table style="background-color:lightgray" border="1" width="100%">
            <?php foreach ($messages as $i => $msg): ?>
                <tr>
                    <td width="5%" align="center">
                        <strong><?= $i + 1 ?></strong>
                    </td>
                    <td width="95%">
                        <strong>Subject: </strong> <?= htmlspecialchars($msg['subject']) ?><br />
                        <strong>Name: </strong>    <?= htmlspecialchars($msg['name'])    ?><br />
                        <strong>Date: </strong>    <?= htmlspecialchars($msg['created_at']) ?><br />
                        <u><strong>Message:</strong></u><br />
                        <?= htmlspecialchars($msg['message']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

    <?php endif; ?>

</body>
</html>