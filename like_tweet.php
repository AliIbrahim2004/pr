<?php
session_start();
require_once('classes.php');

if (isset($_SESSION["user"])) {
    $user_data = $_SESSION["user"];
    $user = unserialize($user_data);
    if (is_object($user) && isset($_POST['tweet_id'])) {
        $tweet_id = $_POST['tweet_id'];
        $user->addLikeToTweet($tweet_id);
    }
}

header("Location: home.php");
exit();
?>
