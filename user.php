<?php
require_once ('header.php');?>
<?php
session_start();
require_once('classes.php');
if (isset($_SESSION["user"])) {
    $user_data = $_SESSION["user"];
    $user = unserialize($user_data);
    if (is_object($user)) {
        // $user is an object, you can access its properties
        $username = $user->username;
        // rest of your code
    } else {
        // $user is not an object, handle the error
        echo "Error: Unable to unserialize user data.";
        exit;
    }
} else {
    
    
    // handle the case where the user is not logged in
    echo "You are not logged in.";
    exit;
}

// ...
if (is_object($user)) {
    $username = $user->username;
    $role = $user->role; // assuming the user object has a role property

    if ($role == 'admin') {
        // redirect to admin page
        header('Location: admin.php');
        exit;
    }
} else {
    // handle the error
    echo "Error: Unable to unserialize user data.";
    exit;
}
// ...
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
    background: url('') no-repeat center center fixed;
    background-size: cover;
    font-family: Arial, sans-serif;
}



.profile-container {
    margin-top: 50px;
    background: rgba(255, 255, 255, 0.9);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.profile-img img {
    width: 100%;
    border: 5px solid #ffc0cb; /* Light pink border */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.profile-info {
    text-align: left;
}

.profile-info h1 {
    font-size: 2.5em;
    margin-bottom: 20px;
    color: #e91e63; /* Pink */
}

.profile-info p {
    font-size: 1.2em;
    margin-bottom: 10px;
    color: #e91e63; /* Pink */
}

.profile-info p.email,
.profile-info p.location {
    display: flex;
    align-items: center;
}

.profile-info p.email::before,
.profile-info p.location::before {
    content: '';
    display: inline-block;
    width: 20px;
    height: 20px;
    margin-right: 10px;
    background-size: contain;
    background-repeat: no-repeat;
}

.profile-info p.email::before {
    background-image: url('email-icon.png');
}

.profile-info p.location::before {
    background-image: url('location-icon.png');
}

.social-icons {
    margin-top: 20px;
}

.social-icons a {
    text-decoration: none;
    color: #e91e63; /* Pink */
    font-size: 1.5em;
    margin-right: 15px;
    transition: color 0.3s;
}

.social-icons a:hover {
    color: #ff4081; /* Lighter pink */
}

#showPostsBtn {
    background-color: #e91e63; /* Pink */
    border: none;
}

#showPostsBtn:hover {
    background-color: #ff4081; 
}

</style>    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
   

    <div class="container profile-container">
        <div class="row">
            <div class="col-md-3">
                <div class="profile-img">
                    <img src="images/4.JPG" alt="User Photo" class="img-fluid rounded-circle">
                </div>
            </div>
            <div class="col-md-8">
                <div class="profile-info">
                    <h1><?php
// ...
if (is_object($user)) {
    $GLOBALS['username'] = $user->username;
    ?>
<h1>welcome <?= $GLOBALS['username'] ?></h1>
    <?php
} else {
    // ...
}
?></h1>
                    <p class="email">Email: <?= $GLOBALS['username'] ?>@gmail.com</p>
                    <p class="location">role: <?= $GLOBALS['role'] ?></p>
                    <div class="social-icons">
                        <a href="https://www.facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
                    </div>
                    <a class="btn btn-primary mt-3" id="showPostsBtn" href="home.php">homepage</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
