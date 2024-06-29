<?php
session_start();
require_once('classes.php');

if (isset($_POST['tweet_id']) && isset($_SESSION['user'])) {
    $tweet_id = $_POST['tweet_id'];
    $user = unserialize($_SESSION['user']);
    if ($user) { 
        $user->removeLikeFromTweet($tweet_id);
        header("Location: home.php");
        exit;
    } else {
        echo "Error: User not logged in.";
        exit;
    }
}
?>