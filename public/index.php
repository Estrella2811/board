<?php
// index.php
// ---------------------------------------------------------
// Entry point — redirects based on login state.
// ---------------------------------------------------------


session_start();

// ✅ absolute paths, no folder prefix needed
if (!empty($_SESSION['access_token'])) {
    header("Location: PostMessage.php");
} else {
    header("Location: login.php");
}
exit;
