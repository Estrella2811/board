<?php
// src/Models/MessageModel.php
// ---------------------------------------------------------
// Handles saving and retrieving messages from Supabase.
// ---------------------------------------------------------

require_once __DIR__ . '/../Supabase/SupabaseDB.php';
require_once __DIR__ . '/../../config/config.php';

// Save a new message linked to the authenticated user
function save_message(string $subject, string $name, string $message): bool {
    //session_start();

    $token  = $_SESSION['access_token'] ?? null;
    $userId = $_SESSION['user_id']      ?? null;

    $db = new SupabaseDB(SUPABASE_URL, SUPABASE_ANON_KEY, 'messages', $token);

    $result = $db->insert([
        'user_id' => $userId,
        'subject' => $subject,
        'name'    => $name,
        'message' => $message,
    ]);

    // 201 Created = success
    return $result['status'] === 201;
}

// Get all messages for the currently logged-in user
function get_user_messages(): array {
    $token  = $_SESSION['access_token'] ?? null;
    $userId = $_SESSION['user_id']      ?? null;

    if (!$token || !$userId) return [];

    $db     = new SupabaseDB(SUPABASE_URL, SUPABASE_ANON_KEY, 'messages', $token);
    $result = $db->select('*', ['user_id' => 'eq.' . $userId]);

    return $result['data'] ?? [];
}

// Get all messages (admin use — requires service key)
function get_all_messages(): array {
    $db     = new SupabaseDB(SUPABASE_URL, SUPABASE_SERVICE_KEY, 'messages');
    $result = $db->select('*');

    return $result['data'] ?? [];
}
