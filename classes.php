<?php

class User {
    public $id;
    public $username;
    public $email;
    protected $hashed_password;
    public $created_at;
    public $updated_at;
    public $role;

    public function __construct($id, $username, $email, $hashed_password, $created_at, $updated_at, $role) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->hashed_password = $hashed_password;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->role = $role;
    }

    public static function register($username, $email, $hashed_password) {
        $user = new Subscriber(null, $username, $email, $hashed_password, date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), "subscriber");
        return $user;
    }

    public static function login($email, $hashed_password) {
        $conn = mysqli_connect("localhost", "root", "", "twitter_clone");
            if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    
        $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$hashed_password'";
        $result = mysqli_query($conn, $sql);
        $user_data = mysqli_fetch_assoc($result);
    
        mysqli_close($conn);
    
        if ($user_data) {
            if ($user_data['role'] == "subscriber") {
                $user = new Subscriber($user_data['id'], $user_data['username'], $user_data['email'], $user_data['password'], $user_data['created_at'], $user_data['updated_at'], $user_data['role']);
            } elseif ($user_data['role'] == "admin") {
                $user = new Admin($user_data['id'], $user_data['username'], $user_data['email'], $user_data['password'], $user_data['created_at'], $user_data['updated_at'], $user_data['role']);
            }
            return $user;
        } else {
            return null;
        }
    }
}

class Subscriber extends User {
    public $role = "subscriber";

    public function __construct($id, $username, $email, $hashed_password, $created_at, $updated_at, $role) {
        parent::__construct($id, $username, $email, $hashed_password, $created_at, $updated_at, $role);
    }

    public function store_posts($title, $content, $imageName, $created_at) {
        $sql = "INSERT INTO tweets (user_id, title, content, image, created_at) VALUES ('$this->id','$title','$content','$imageName','$created_at')";
        $conn = mysqli_connect("localhost", "root", "", "twitter_clone");
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
        return $result;
    }

    public function getMyTweets() {
        $sql = "SELECT * FROM tweets WHERE user_id = '$this->id' ORDER BY created_at DESC";
        $conn = mysqli_connect("localhost", "root", "", "twitter_clone");
        $result = mysqli_query($conn, $sql);
        $tweets = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $tweets[] = $row;
        }
        mysqli_close($conn);
        return $tweets;
    }
      
    
    public function store_comment($tweet_id, $user_id, $comment_text) {
        $sql = "SELECT id FROM tweets WHERE id = '$tweet_id'";
        $conn = mysqli_connect("localhost", "root", "", "twitter_clone");
        $result = mysqli_query($conn, $sql);
            $sql = "INSERT INTO comments(tweet_id, user_id, comment_text) VALUES ('$tweet_id','$user_id','$comment_text')";
            $result = mysqli_query($conn, $sql);
            mysqli_close($conn);
            return $result;
  
        
    }
    public function getCommentsForTweet($tweet_id) {
        $sql = "SELECT * FROM comments WHERE tweet_id = '$tweet_id'";
        $conn = mysqli_connect("localhost", "root", "", "twitter_clone");
        $result = mysqli_query($conn, $sql);
        $comments = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $user_id = $row['user_id'];
            $comments[] = array( 'comment_text' => $row['comment_text']);
        }
        mysqli_close($conn);
        return $comments;
    }
    public function getLikesForTweet($tweet_id) {
        $sql = "SELECT COUNT(*) as like_count FROM likes WHERE tweet_id = '$tweet_id'";
        $conn = mysqli_connect("localhost", "root", "", "twitter_clone");
        $result = mysqli_query($conn, $sql);
        $like_count = mysqli_fetch_assoc($result)['like_count'];
        mysqli_close($conn);
        return $like_count;
    }

    public function addLikeToTweet($tweet_id) {
        $sql = "INSERT INTO likes (tweet_id, user_id) VALUES ('$tweet_id', '$this->id')";
        $conn = mysqli_connect("localhost", "root", "", "twitter_clone");
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
        return $result;
    }
    public function removeLikeFromTweet($tweet_id) {
        $sql = "DELETE FROM likes WHERE tweet_id = '$tweet_id' AND user_id = '$this->id'";
        $conn = mysqli_connect("localhost", "root", "", "twitter_clone");
        $result = mysqli_query($conn, $sql);
        mysqli_close($conn);
        return $result;
    }
  
}


class Admin extends User {
    public $role = "admin";

    public function __construct($id, $username, $email, $hashed_password, $created_at, $updated_at, $role) {
        parent::__construct($id, $username, $email, $hashed_password, $created_at, $updated_at, $role);
    }
    public function get_all_users(){
        $sql = "SELECT * FROM users";
        $conn = mysqli_connect("localhost", "root", "", "twitter_clone");
        if (!$conn) {
            die("Connection failed: ". mysqli_connect_error());
        }
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Query failed: ". mysqli_error($conn));
        }
        $user_data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $user_data[] = $row;
        }
        mysqli_close($conn);
        return $user_data;
    }
    public function get_all_tweets(){
        $sql = "SELECT * FROM tweets";
        $conn = mysqli_connect("localhost", "root", "", "twitter_clone");
        if (!$conn) {
            die("Connection failed: ". mysqli_connect_error());
        }
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Query failed: ". mysqli_error($conn));
        }
        $user_data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $user_data[] = $row;
        }
        mysqli_close($conn);
        return $user_data;
    }
    public function delete_user($user_id) {
        $sql = "DELETE FROM likes WHERE user_id = '$user_id'";
        $conn = mysqli_connect("localhost", "root", "", "twitter_clone");
        if (!$conn) {
            die("Connection failed: ". mysqli_connect_error());
        }
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Query failed: ". mysqli_error($conn));
        }
    
        $sql = "DELETE FROM comments WHERE user_id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Query failed: ". mysqli_error($conn));
        }
    
        $sql = "DELETE FROM tweets WHERE user_id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Query failed: ". mysqli_error($conn));
        }
    
        $sql = "DELETE FROM users WHERE id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            die("Query failed: ". mysqli_error($conn));
        }
        mysqli_close($conn);
    }

}

?>