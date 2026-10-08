<?php
// src/Handlers/LoginHandler.php
// ---------------------------------------------------------
// Handles login form submission.
// Signs in via Supabase Auth and stores the token in session.
// ---------------------------------------------------------

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Supabase/SupabaseAuth.php';


//session_start();

if (isset($_POST['submit'])) {

    $email    = trim($_POST['email']    ?? '');
    $password = trim($_POST['password'] ?? '');

    $errors = [];
    if (empty($email))    $errors[] = "Email is required.";
    if (empty($password)) $errors[] = "Password is required.";

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        header("Location: /login.php");
        exit;
    }
    $auth   = new SupabaseAuth(SUPABASE_URL, SUPABASE_ANON_KEY);
    $result = $auth->signIn($email, $password);

    if ($result['status'] === 200) {
        // Store token and user_id in session for use across pages
        $_SESSION['access_token']  = $result['data']['access_token'];
        $_SESSION['refresh_token'] = $result['data']['refresh_token'];
        $_SESSION['user_id']       = $result['data']['user']['id'];
        $_SESSION['user_email']    = $result['data']['user']['email'];

        header("Location: /PostMessage.php");
        exit;
    } else {
        $errorMsg = $result['data']['error_description']
                 ?? $result['data']['msg']
                 ?? "Invalid email or password.";
        echo $result['status'] . " is the status code";
        $_SESSION['form_errors'] = [$errorMsg];
       header("Location: ../../login.php");
        exit;
    }
}
