<?php
    include("config.php");
    $id = $_GET['id'];
    $sql ="UPDATE dtr SET checkedOut = NOW() WHERE userID = '$id' ";
    $result = mysqli_query($conn, $sql);

    header("Location: home.php")
?>