<?php
    include('config.php');
    session_destroy();
    $id = $_GET['id'];

    $sql ="DELETE FROM users where userID = '$id'";
    $result = mysqli_query($conn, $sql);

    header("Location: login.php");
?>