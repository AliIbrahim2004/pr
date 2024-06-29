<?php
session_start();
include_once 'db_connect.php';

// Check for error message in URL query string
if (isset($_GET['error_msg'])) {
    $display_error = htmlspecialchars($_GET['error_msg']);
    unset($_GET['error_msg']); // Clear error message from URL query string
} elseif (isset($_SESSION['error_msg'])) { // If not found in URL, check session
    $display_error = $_SESSION['error_msg'];
    unset($_SESSION['error_msg']); // Clear error message from session
} else {
    $display_error = ""; // If no error message, set it to an empty string
}

// Process login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize email address
    $email = mysqli_real_escape_string($conn, $email);

    // Query to retrieve user from database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $error_msg = "Error: ". mysqli_error($conn);
        $_SESSION['error_msg'] = $error_msg; // Store error message in session
        header("Location: login.php?error_msg=". urlencode($error_msg)); // Add error message to URL
        exit;
    }

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $row['password'])) {
            require_once('classes.php');
            $user = User::login($email, $row['password']); // Pass the hashed password from the database
            if (!empty($user)){
                $_SESSION['user'] = serialize($user);

                if($user->role == "admin"){
                    header("Location: home.php");
                    exit;
                } else if($user->role == "subscriber"){
                    header("Location: home.php"); // Redirect to homepage.php for users
                    exit;
                }
            } else {
                $error_msg = "Invalid user role or other login issue";
                $_SESSION['error_msg'] = $error_msg; // Store error message in session
                header("Location: login.php");
                exit;
            }
        } else {
            $error_msg = "Invalid password";
            $_SESSION['error_msg'] = $error_msg; 
            // Store error message in session
            header("Location: login.php");
            exit;
        }
    } else {
        $error_msg = "User not found";
        $_SESSION['error_msg'] = $error_msg; // Store error message in session
        header("Location: login.php");
        exit;
    }

    // Close connection
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fafafa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Roboto', sans-serif;
        }
        .main-container {
            display: flex;
            background: white;
            border: 1px solid #dbdbdb;
            border-radius: 1px;
            width: 900px;
            max-width: 90%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .left-section, .right-section {
            padding: 40px;
        }
        .left-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-right: 1px solid #dbdbdb;
        }
        .right-section {
            flex: 1;
        }
        .logo img {
            width: 175px;
            margin-bottom: 20px;
        }
        .intro-text {
            font-size: 20px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-box {
            margin-top: 20px;
        }
        .field input {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #dbdbdb;
            border-radius: 3px;
            background: #fafafa;
        }
        .btn {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 3px;
            background-color:#007bb5 ;
            color: black;
            font-weight: bold;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #007bb5;
        }
        .login-link {
            margin-top: 20px;
            text-align: center;
        }
        .login-link a {
            color: #0095f6;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .error-msg {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
            text-align: center;
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
    font-weight: bold;
}

.navbar ul li a:hover {
    color: #0095f6;
}
 
 
    </style>
</head>
<body>


<div class="main-container">
      <!-- Navigation Bar -->
      <div class="navbar">
    <div class="container">
        <div class="logo">

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
    <div class="left-section">
        <div class="logo">
            <img src="images/insta.jpg" alt="Instagram Logo">
        </div>
        <div class="intro-text">
            Welcome to Instagram! Connect with friends and the world around you on Instagram.
        </div>
    </div>
    <div class="right-section">
    <h1>log in </h1>

        <div class="form-box">
            <?php
                if (!empty($display_error)) { // Check if there's an error message to display
                    echo "<p class='error-msg'>{$display_error}</p>";
                }
            ?>
            <form action="login.php" method="post">
                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email"placeholder="Email" autocomplete="off" required>
                </div>
                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password"placeholder="Password" autocomplete="off" required>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login">
                </div>
                <div class="login-link">
                    Don't have an account? <a href="signup.php">Sign Up Now</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
