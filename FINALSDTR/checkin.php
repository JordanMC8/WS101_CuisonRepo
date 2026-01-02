<?php
    include("config.php");
    $id = $_GET['id'];
    $sql ="INSERT INTO dtr (userID, checkedIn) VALUES ('$id', NOW())";
    $result = mysqli_query($conn, $sql);
    header("Location: home.php");
?>
