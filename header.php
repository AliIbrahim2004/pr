
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home/ lnstagram</title>
    <link rel="stylesheet"href="css/style.css">
    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .navbar {
    margin-bottom: 30px;
    background-color: cyan;
    height: 70px;
    
    
    
}

.navbar-brand, .nav-link {
    color: black !important; 
}

.navbar-brand:hover, .nav-link:hover {
    color: #ffffff !important; 
}

#logoutBtn {
    background-color: red; 
    border: none;
}

#logoutBtn:hover {
    background-color: white; 
}
    </style>
</head>
<body>
     <!-- Navigation Bar -->
     <nav class="navbar navbar-expand-lg fixed-top">
        <a class="navbar-brand" href="#">
        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" alt="Instagram Logo" width="40" height="40">
            lnstagram
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"style="background-color: white ;"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="home.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-danger" id="logoutBtn" href="logout.php">sign up</a>
                </li>
            </ul>
        </div>
    </nav>

    
</body>
</html>