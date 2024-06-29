<?php
session_start();
require_once('classes.php');

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

if (!empty($_REQUEST["title"]) && !empty($_REQUEST["content"])) {
    if (!empty($_FILES["image"]["name"])) {
        $uploadDir = "images/";
        $imageName = $uploadDir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $imageName)) {
        } else {
            $imageName = null;
        }
    } else {
        $imageName = null;
    }

    $createdAt = date('Y-m-d H:i:s'); 
    $user->store_posts($_REQUEST["title"], $_REQUEST["content"], $imageName, $createdAt);
    header("Location: home.php?error_msg=done");
} else {
    header("Location: home.php?error_msg=require_fields");
}
?>
