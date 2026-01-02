<?php
    include('config.php');
    $id = $_GET['id'];

    $sql ="DELETE FROM users where userID = '$id'";
    $result = mysqli_query($conn, $sql);
    header("Location: manageusers.php");
?>