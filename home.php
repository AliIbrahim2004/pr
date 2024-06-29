<?php
session_start();
require_once('classes.php');
if (isset($_SESSION["user"])) {
    $user_data = $_SESSION["user"];
    $user = unserialize($user_data);
    if (is_object($user)) {
        $username = $user->username;

        if ($user->role == 'admin') {
            header('Location: admin.php');
            exit;
        }
    } else {
        echo "Error: Unable to unserialize user data.";
        exit;
    }
} else {
    header("Location: login.php");
    echo "You are not logged in.";
    exit;}

if (isset($_SESSION["user"])) {
    $user_data = $_SESSION["user"];
    $user = unserialize($user_data);
    if (is_object($user)) {
        $username = $user->username;
        $myposts = $user->getMyTweets($user->id);
    } else {
        echo "Error: Unable to unserialize user data.";
        exit;
    }
} else {
    header("Location: login.php");

    echo "You are not logged in.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/homepage.css">
    <link rel="stylesheet" href="styles/comments.css">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha384-KyZXEAg3QhqLMpG8Ya3W2ggJXfXALMeZjQwdXnLgR2t6+I6auKt+D5r8V5wqK5Q==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
    /* Basic reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #fafafa;
}

.grid-container {
    display: grid;
    grid-template-columns: 1fr 3fr 1fr;
    grid-gap: 20px;
    max-width: 1200px;
    margin: 50px auto;
    padding: 20px;
}

.leftoo, .sidebar {
    background: linear-gradient(135deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
    color: white;
    border: 1px solid #dbdbdb;
    border-radius: 3px;
    padding: 20px;
    height: 500px;

}

.leftoo ul, .sidebar ul {
    list-style: none;
}

.leftoo ul li, .sidebar ul li {
    margin-bottom: 15px;
}

.leftoo ul li a, .sidebar ul li a {
    text-decoration: none;
    color: white;
}

.stories-section {
    margin-top: 20px;
}

.stories {
    display: flex;
    overflow-x: scroll;
}

.story {
    margin-right: 15px;
    text-align: center;
}

.story img {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 2px solid #e91e63;
}

.story span {
    display: block;
    margin-top: 5px;
    font-size: 12px;
}

.main {
    background-color: white;
    border: 1px solid #dbdbdb;
    border-radius: 3px;
    padding: 20px;
}

.tweet__box {
    margin-bottom: 20px;
}

.tweet-box {
    border: 1px solid #dbdbdb;
    border-radius: 3px;
    padding: 10px;
}

.tweet-box-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.tweet-box-body input,
.tweet-box-body textarea {
    width: 100%;
    border: 1px solid #dbdbdb;
    border-radius: 3px;
    padding: 10px;
    margin-bottom: 10px;
}

.tweet-box-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.tweet-icons .icon {
    margin-right: 10px;
    cursor: pointer;
}

.tweet-action button {
    background-color: #e91e63;
    color: white;
    border: none;
    border-radius: 3px;
    padding: 10px 20px;
    cursor: pointer;
}

.post-box {
    margin-bottom: 20px;
    padding: 20px;
    border: 1px solid #dbdbdb;
    border-radius: 3px;
}

.post-box img {
    max-width: 100%;
    height: auto;
    border-radius: 3px;
}

.likes-section {
    display: flex;
    align-items: center;
    margin-top: 10px;
}

.likes-section form {
    display: inline;
    margin-right: 10px;
}

.comments-section {
    margin-top: 20px;
    border-top: 1px solid #dbdbdb;
    padding-top: 10px;
}

.comments-section .tweet__left {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.comments-section .tweet__left img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 10px;
}

.comments-section .comment {
    margin-bottom: 10px;
}

.comments-section form input {
    width: calc(100% - 100px);
    padding: 10px;
    margin-right: 10px;
    border: 1px solid #dbdbdb;
    border-radius: 3px;
}

.comments-section form button {
    background-color: #e91e63;
    color: white;
    border: none;
    border-radius: 3px;
    padding: 10px 20px;
    cursor: pointer;
}

.toggle-button {
    display: none;
}

@media (max-width: 768px) {
    .grid-container {
        grid-template-columns: 1fr;
    }

    .leftoo, .sidebar {
        display: none;
    }

    .toggle-button {
        display: block;
        margin: 10px;
        padding: 10px;
        background-color: #e91e63;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    .leftoo.show, .sidebar.show {
        display: block;
    }
}

.navbar {
    width: 100%;
    background-color: white;
    border-bottom: 1px solid #dbdbdb;
    padding: 10px;
    box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1000;
}

.navbar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar .logo span {
    color: #e1306c;
    font-size: 24px;
    font-weight: bold;
}

.navbar ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 20px;
    align-items: center;

}

.navbar ul li {
    display: inline;
}

.navbar ul li a {
    text-decoration: none;
    color: #333;
    font-weight: bold;
}

.navbar ul li a:hover {
    color: #0095f6;
}

.unlike-btn {
    background-color: white;
    color: red;
    border: none;
    padding: 5px 10px;
    font-size: 14px;
    cursor: pointer;
}





   </style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/comments.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha384-KyZXEAg3QhqLMpG8Ya3W2ggJXfXALMeZjQwdXnLgR2t6+I6auKt+D5r8V5wqK5Q==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <style>
        .unlike-btn {
            background-color: white; 
            color: red; 
            border: none;
            padding: 5px 10px;
            font-size: 14px;
            cursor: pointer;
        }
        .navbar {
            width: 100%;
            background-color: white;
            border-bottom: 1px solid #dbdbdb;
            padding: 10px ;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }
        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
   
        .navbar ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            gap: 20px;
        }
        .navbar ul li {
            display: inline;
        }
        .navbar ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
        }
        .navbar ul li a:hover {
            color: #0095f6;
        }
    </style>
</head>
<body>
        <!-- Navigation Bar -->
        <div class="navbar">
    <div class="container">
        <div class="logo">
        <img src="images/insta.jpg" alt="Instagram Logo" style="height: 50px; margin-right: 10px;">

            <span>insta</span>gram
        </div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            
            <li class="nav-item">
                    <a class="btn btn-danger" id="logoutBtn" href="logout.php">Logout</a>
                </li>
        </ul>
    </div>
</div>

    <div class="grid-container">
        <div class="leftoo">
            <div class="sidebar-item">
                <h3>Menu</h3>
                <ul>
                    <li><a href="user.php">Profile</a></li>
                    <li><a href="#">Explore</a></li>
                    <li><a href="#">Notifications</a></li>
                </ul>
            </div>
            <div class="stories-section">
                <h3>Stories</h3>
                <div class="stories">
                    <div class="story">
                        <img src="images/Dr.jpg" alt="Story 1">
                        <span>dr.Ashraf</span>
                    </div>
                    <div class="story">
                        <img src="images/4.jpg" alt="Story 2">
                        <span>ALi Ibrahim</span>
                    </div>
                    <div class="story">
                        <img src="images/Dr.Ahmed.JPG" alt="Story 3">
                        <span>Dr.Ahmed</span>
                    </div>
                    
                    <!-- Add more stories as needed -->
                </div>
            </div>
        </div>
        <div class="main">
            <div class="tweet__box tweet__add">
                <div class="tweet-box">
                    <div class="tweet-box-header">
                    <img src="images/insta.jpg" alt="Instagram Logo" style="height: 50px; margin-right: 10px;">

                        <i class="fas fa-times close-icon"></i>
                    </div>
                    <div class="tweet-box-body">
                        <form action="store_posts.php" method="post" enctype="multipart/form-data">
                            <input type="text" name="title" placeholder="Title" class="tweet-title" required>
                            <textarea name="content" placeholder="What's happening?" required></textarea>
                            <div class="tweet-box-footer">
                                <div class="tweet-icons">
                                    <label for="upload_image" class="icon">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" id="upload_image" name="image" accept="image/*" style="display:none;">
                                    <i class="far fa-file-image icon"></i>
                                    <i class="fas fa-map-marker-alt icon"></i>
                                    <i class="far fa-grin icon"></i>
                                    <i class="far fa-user icon"></i>
                                </div>
                                <div class="tweet-action">
                                    <button class="button-tweet" type="submit" name="btn_add_post">Post</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php foreach($myposts as $tweet) { ?>
                <div class="post-box">
                    <h2><?php echo htmlspecialchars($tweet['title']); ?></h2>
                    <p><?php echo nl2br(htmlspecialchars($tweet['content'])); ?></p>
                    <?php if (!empty($tweet['image'])): ?>
                    <img src="<?php echo htmlspecialchars($tweet['image']); ?>" alt="Tweet Image">
                    <?php endif; ?>
                    <div class="likes-section">
                        <?php
                        $likes = $user->getLikesForTweet($tweet['id']);
                        ?>
                        <span><?php echo $likes; ?> Likes</span>
                        <form action="like_tweet.php" method="post">
                            <input type="hidden" name="tweet_id" value="<?php echo $tweet['id']; ?>">
                            <button type="submit" class="like-btn">
                                <i class="fa fa-heart"></i>
                            </button>
                        </form>
                        <form action="remove_like.php" method="post">
                            <input type="hidden" name="tweet_id" value="<?php echo $tweet['id']; ?>">
                            <button type="submit" class="unlike-btn">
                                <i class="fa fa-thumbs-down"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="comments-section">
                    <div class="tweet__left">
                        <img src="images/2.jpg" alt="Profile picture">
                        <span><?php echo htmlspecialchars($user->username); ?></span>
                    </div>
                    <?php
                    $comments = $user->getCommentsForTweet($tweet['id']);
                    foreach ($comments as $comment) {
                    ?>
                    <div class="comment">
                        <span><?php echo htmlspecialchars($comment['comment_text']); ?></span>
                    </div>
                    <?php
                    }
                    ?>
                    <form action="store_comment.php" method="post">
                        <input type="text" name="comment" placeholder="Add a comment..." required>
                        <input type="hidden" name="tweet_id" value="<?php echo $tweet['id']; ?>">
                        <button type="submit" style="background-color: #e91e63;">Comment</button>
                    </form>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="sidebar">
            <!-- <div class="sidebar-item">
                <h3>Trends for you</h3>
                <ul>
                    <li><a href="#">#art</a></li>
                    <li><a href="#">#beauty</a></li>
                    <li><a href="#">#sport</a></li>
                </ul>
            </div> -->
            <div class="sidebar-item">
                <h3>Who to follow</h3>
                <ul>
                    <li><a href="#">@dr.Ashraf Abd Abdelaziz</a></li>
                    <li><a href="#">@Ahmed sultan</a></li>
                    <li><a href="#">@ALi Ibrahim(me)</a></li>
                </ul>
            </div>
        </div>
        <button class="toggle-button" id="toggleLeftButton">Menu</button>
        <button class="toggle-button" id="toggleRightButton">Trends</button>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // JavaScript to toggle sidebars on small screens
        const leftSidebar = document.querySelector('.leftoo');
        const rightSidebar = document.querySelector('.sidebar');
        const toggleLeftButton = document.getElementById('toggleLeftButton');
        const toggleRightButton = document.getElementById('toggleRightButton');

        document.addEventListener('DOMContentLoaded', function () {
            toggleLeftButton.addEventListener('click', function () {
                leftSidebar.classList.toggle('show');
            });
            toggleRightButton.addEventListener('click', function () {
                rightSidebar.classList.toggle('show');
            });
        });
    </script>
</body>
</html>
