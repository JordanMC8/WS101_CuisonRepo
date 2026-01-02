<?php

    include("config.php");
    if(isset($_POST['submitRegister'])){
        $firstName = htmlspecialchars(trim($_POST['firstname']), ENT_QUOTES, 'UTF-8');
        $lastName  = htmlspecialchars(trim($_POST['lastname']), ENT_QUOTES, 'UTF-8');
        $email     = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password  = $_POST['password'];

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $emailverification = $conn->prepare("SELECT * FROM users WHERE userEmail = ?");
        $emailverification->bind_param("s", $email);
        $emailverification->execute();
        $emailverification->store_result();

        if ($emailverification->num_rows > 0) {
            header("Refresh: 3; url=index.php");
            echo "<h1>Email already registered. Please use another email.</h1><br>";
            echo "<h1>You will be redirected back to the register page shortly.</h1>";
        } 
        else {
            header("Refresh: 3; url=home.php");
            echo "<h1>User successfully registered.</h1>";
            echo "<h1>You will be redirected to the home page shortly.</h1>";
            if(isset($_POST['admin'])){
                if($_POST['admin']){
                    $sql ="INSERT INTO users (userFirstName, userLastName, userEmail, userPassword, admin, dateRegistered) VALUES ('$firstName', '$lastName', '$email', '$hashed', 1, NOW())";
                }
            }
            else{
                $sql ="INSERT INTO users (userFirstName, userLastName, userEmail, userPassword, dateRegistered) VALUES ('$firstName', '$lastName', '$email', '$hashed', NOW())";
            }
            $result = mysqli_query($conn, $sql);
            $sql = "SELECT * FROM users WHERE userEmail='$email'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            session_start();
            $_SESSION['user'] = $row;

        }
    }
    else{
        $email     = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password  = $_POST['password'];

        $sql = "SELECT * FROM users WHERE userEmail='$email'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
         
            if (password_verify($password, $row['userPassword'])) {
                echo "<h1>Login successful!</h1><br>";
                echo "<h1>You will be redirected to the home page shortly.</h1>";
                session_start();
                $_SESSION['user'] = $row;
                header("Refresh: 3; url=home.php");
            } else {
                echo "<h1>Wrong password.</h1><br>";
                echo "<h1>You will be redirected back to the login page shortly.</h1>";
                header("Refresh: 3; url=login.php");
            }
        }
        else{
            echo "<h1>Email isn't registered.</h1><br>";
            echo "<h1>You will be redirected back to the login page shortly.</h1>";
            header("Refresh: 3; url=login.php");
        }

    }

?>
