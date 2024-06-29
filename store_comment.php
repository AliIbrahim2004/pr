<?php
session_start();
require_once('classes.php');

if (isset($_SESSION["user"])) {
    $user_data = $_SESSION["user"];
    $user = unserialize($user_data);

    if (is_object($user)) {
        $username = $user->username;
        $user_id = $user->id;
    } else {
        echo "Error: Unable to unserialize user data.";
        exit;
    }
} else {
    echo "You are not logged in.";
    exit;
}

if (!empty($_REQUEST["comment"]) && !empty($_REQUEST["tweet_id"])) {
    $comment = $_REQUEST["comment"];
    $tweet_id = $_REQUEST["tweet_id"];

    if (!is_numeric($tweet_id)) {
        header("Location: home.php?error_msg=tweet_id_invalid");
        exit;
    }

    $success = $user->store_comment($tweet_id, $user_id, $comment);

    if ($success) {
        header("Location: home.php?success_msg=comment_saved");
        exit;
    } else {
        header("Location: home.php?error_msg=comment_failed");
        exit;
    }
} else {
    header("Location: home.php?error_msg=missing_data");
    exit;
}
?>
