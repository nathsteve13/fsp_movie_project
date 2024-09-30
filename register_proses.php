<?php
require_once("class/user.php");

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($username)) {
        $errors[] = 'Username is required.';
    }

    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (!empty($errors)) {
        $error_message = implode(" ", $errors);
        header("Location: register.php?error=" . urlencode($error_message));
        exit;
    }

    $user = new User();
    $user_id = $user->addUser($username, $password);

    if ($user_id) {
        header("Location: register.php?success=1");
        exit;
    } else {
        header("Location: register.php?error=" . urlencode("Failed to register the user. Please try again."));
        exit;
    }
} else {
    header("Location: register.php");
    exit;
}
