<?php
session_start();

// ✅ __DIR__ goes up one level from public/ to project root
//require_once  '../src/Supabase/supabase_auth.php';
require_once '../src/Supabase/SupabaseAuth.php';
require_once '../config/config.php';

$token = $_SESSION['access_token'] ?? null;

if ($token) {
    $auth   = new SupabaseAuth(SUPABASE_URL, SUPABASE_ANON_KEY);
    $result = $auth->signOut();
    //signIn($email, $password);
    //auth_signout($token);
}

session_unset();
session_destroy();

// ✅ absolute path from web root
header("Location: /login.php");
exit;