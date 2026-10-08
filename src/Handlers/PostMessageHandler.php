<?php
// src/Handlers/PostMessageHandler.php
// ---------------------------------------------------------
// Handles the contact/message form submission.
// Validates input and saves the message to Supabase.
// Replaces the old flat-file write logic in the original handler.
// ---------------------------------------------------------

//require_once __DIR__ . '/../../config/config.php'; //
require_once __DIR__ . '/../Models/MessageModel.php';

//session_start();

if (isset($_POST['submit'])) {

    $subject = trim(stripslashes($_POST['subject'] ?? ''));
    $name    = trim(stripslashes($_POST['name']    ?? ''));
    $message = trim(stripslashes($_POST['message'] ?? ''));

    // Validate required fields
    $errors = [];
    if (empty($subject)) $errors[] = "Subject is required.";
    if (empty($name))    $errors[] = "Name is required.";
    if (empty($message)) $errors[] = "Message is required.";

    if (!empty($errors)) {
        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data']   = [
            'subject' => $subject,
            'name'    => $name,
            'message' => $message,
        ];
        header("Location: /PostMessage.php");
        exit;
    }

    // Check the user is logged in before saving
    if (empty($_SESSION['access_token'])) {
        $_SESSION['form_errors'] = ["You must be logged in to post a message."];
        header("Location: /login.php");
        exit;
    }

    // Save to Supabase via MessageModel
    if (!save_message($subject, $name, $message)) {
        $_SESSION['form_errors'] = ["Could not save your message. Please try again."];
        header("Location: /PostMessage.php");
        exit;
    }

    $_SESSION['form_success'] = "Your message has been posted!";
    header("Location: /MessageBoard.php");
    exit;
}
