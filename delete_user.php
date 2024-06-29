<?php
require_once('classes.php');
session_start();

if (isset($_SESSION["user"])) {
    $user_data = $_SESSION["user"];
    $user = unserialize($user_data);
    if (is_object($user)) {
        $username = $user->username;
    } else {
        echo "Error: Unable to unserialize user data.";
        exit;
    }
} else {
    echo "You are not logged in.";
    exit;
}

$user_id = $_REQUEST['user_id']; 
$user->delete_user($user_id); 
header('Location:admin.php?msg=done');
exit;