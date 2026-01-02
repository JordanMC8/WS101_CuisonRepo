<?php
    include('config.php');
    include('emailverification.php');

    $firstName = htmlspecialchars(trim($_POST['firstname']), ENT_QUOTES, 'UTF-8');
    $lastName  = htmlspecialchars(trim($_POST['lastname']), ENT_QUOTES, 'UTF-8');
    $email     = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password  = $_POST['password'];

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $sql ="INSERT INTO users (userFirstName, userLastName, userEmail, userPassword, dateRegistered) VALUES ('$firstName', '$lastName', '$email', '$hashed', NOW())";
    $result = mysqli_query($conn, $sql);

    header("Location: home.php");
?>