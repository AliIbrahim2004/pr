<?php
$connection = mysqli_connect('localhost', 'root', '', 'twitter_clone');

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["tweet"])) {
        $tweet_text = $_POST["tweet"];
        
        $query = "INSERT INTO tweets (user_id, tweet_text, created_at) VALUES ('$user_id', '$tweet_text', NOW())";
        
        if (mysqli_query($connection, $query)) {
    
            header("Location: home.php"); 
            exit();
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($connection);
        }
    } else {
        echo "Tweet cannot be empty!";
    }
}

$query = "INSERT INTO tweets (user_id, tweet_text, created_at) VALUES ('$user_id', '$tweet_text', NOW())";

if (mysqli_query($connection, $query)) {
    header("Location:home.php");
    exit();
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($connection);
}


mysqli_close($connection);
?>
