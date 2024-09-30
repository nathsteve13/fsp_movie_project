<?php
session_start();
require_once("class/user.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = new User();
    $userid = $_POST['userid'];
    $plain_password = $_POST['password'];

    if ($user->authenticate($userid, $plain_password)) {
        $_SESSION['userid'] = $userid;
        header("Location: index.php");
        exit();
    } else {
        header("Location: login.php?error=" . urlencode("Incorrect User ID or Password"));
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
