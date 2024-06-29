<?php
session_start();
include_once 'db_connect.php';

$error_msg = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_msg[] = "Error: All fields are required.";
    } else {
        if ($password != $confirm_password) {
            $error_msg[] = "Error: Password and confirm password do not match.";
        } else {
            $username = htmlspecialchars(trim($username));
            $email = trim($email);

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error_msg[] = "Error: Invalid email format.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $sql = "SELECT * FROM users WHERE username=? OR email=?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "ss", $username, $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $count = mysqli_num_rows($result);

                if ($count > 0) {
                    $error_msg[] = "Error: Username or email already in use.";
                } else {
                    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashed_password);
                    if (mysqli_stmt_execute($stmt)) {
                        require_once('classes.php');
                        $result = Subscriber::register($username, $email, $hashed_password);
                        header("Location: login.php?registered=true");
                        exit;
                    } else {
                        $error_msg[] = "Error: " . mysqli_error($conn);
                    }
                }
            }
        }
    }
}

mysqli_close($conn);
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
            margin-top: 0px;
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
        <h1>Sign Up</h1>
        <div class="form-box">
            <form action="signup.php" method="post">
                <?php if (!empty($error_msg)) { ?>
                    <div class="error-msg"><?php foreach ($error_msg as $msg) { echo $msg . "<br>"; } ?></div>
                <?php } ?>
                <div class="field input">
                    <input type="text" name="username" id="username" placeholder="Username" autocomplete="off" required>
                </div>
                <div class="field input">
                    <input type="email" name="email" id="email" placeholder="Email" autocomplete="off" required>
                </div>
                <div class="field input">
                    <input type="password" name="password" id="password" placeholder="Password" autocomplete="off" required>
                </div>
                <div class="field input">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>
                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Register">
                </div>
            </form>
            <div class="login-link">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$_SESSION["error_msg"] = null;
?>
