<?php
    session_start();
    include("header.php");
    include("config.php");
    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);
?>

<div class="dtr">
    <div class="display">
        <img src="avatar.png" width="50" height="50" alt="Placeholder">&nbsp;&nbsp;
        Welcome, <?= $_SESSION['user']['userFirstName'] ?>!
    </div>

    <div class="box2">
        <div class="options">
            <img src="avatar.png" width="50" height="50" alt="Placeholder">&nbsp;&nbsp;
            <?= $_SESSION['user']['userFirstName'] . ", " . $_SESSION['user']['userLastName'] . " <br>(" . ($_SESSION['user']['admin'] == false ? "Faculty)<br>" : "Admin)<br>") ?><br>
            <?= $_SESSION['user']['userEmail'] ?>

            <h4>
                <?php 
                    if($_SESSION['user']['admin'] == true) {
                        echo "<a href='manageusers.php'>Show Users</a><br>";
                        echo "<a href='home.php'>Show DTR</a><br>";
                    }
                ?>
                <a href="checkin.php?id=<?=$_SESSION['user']['userID']?>">Check In</a><br>
                <a href="checkout.php?id=<?=$_SESSION['user']['userID']?>">Check Out</a><br>
                <a href="delete.php?id=<?=$_SESSION['user']['userID']?>">Delete Account</a><br>
                <a href="logout.php">Log Out</a>
            </h4>

        </div>

        <div class="data">
            <table>
                <tr>
                    <td>ID</td>
                    <td>Checked In</td>
                    <td>Checked Out</td>
                </tr>

                <?php
                $sql = "SELECT * FROM dtr";
                $result = mysqli_query($conn, $sql);

                if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $row['userID'] ?></td>
                            <td><?= $row['checkedIn'] ?></td>
                            <td><?= $row['checkedOut'] ?></td>
                        </tr>
                <?php 
                    }
                } else { ?>
                    <tr>
                        <td colspan="3">0 results</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

