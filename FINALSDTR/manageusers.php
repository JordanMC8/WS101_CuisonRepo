<?php
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
    include("header.php");
    include("config.php");
    $sql = "SELECT * FROM users";
    $result = mysqli_query($conn, $sql);

    $search = "";
    if (isset($_GET['search'])) {
        $search = mysqli_real_escape_string($conn, $_GET['search']);
    }
    if ($search != "") {
        $sql = "
            SELECT * FROM users
            WHERE userFirstName LIKE '%$search%'
            OR userLastName LIKE '%$search%'
            OR userEmail LIKE '%$search%'
            OR userID LIKE '%$search%'
            OR admin LIKE '%$search%'
        ";
    } else {
        $sql = "SELECT * FROM users";
    }

    $sort = $_GET['sort'] ?? '';
    $order = $_GET['order'] ?? 'ASC';

    $allowedSortColumns = [
        'userID',
        'userFirstName',
        'userLastName',
        'userEmail',
        'userPassword',
        'dateRegistered'
    ];

    if (in_array($sort, $allowedSortColumns)) {
        $sql .= " ORDER BY $sort $order";
    }


    $result = mysqli_query($conn, $sql);

?>

<div class="dtr">
    <div class="display search-bar">
        <form method="GET" style="display:flex; gap:10px;">
            <input type="text" name="search" class="search-input" placeholder="Search user..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="search-btn">Search</button>
            <button type="button" class="add-btn" onclick="window.location='adduser.php'">Add User</button>
        </form>
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
            <?php
                function sortIcon($column) {
                    if (!isset($_GET['sort']) || $_GET['sort'] !== $column) {
                        return 'fa-sort';
                    }
                    return ($_GET['order'] ?? 'ASC') === 'ASC' ? 'fa-sort-up' : 'fa-sort-down';
                }
            ?>

            <table>
                <tr>
                    <td>
                        <div class="header-with-icon">
                            User ID
                            <button onclick="sortOperation('userID')" class="sort-btn">
                                <i class="fa-solid <?= sortIcon('userID') ?>"></i>
                            </button>
                        </div>
                    </td>

                    <td>
                        <div class="header-with-icon">
                            User FirstName
                            <button onclick="sortOperation('userFirstName')" class="sort-btn">
                                <i class="fa-solid <?= sortIcon('userFirstName') ?>"></i>
                            </button>
                        </div>
                    </td>

                    <td>
                        <div class="header-with-icon">
                            User LastName
                            <button onclick="sortOperation('userLastName')" class="sort-btn">
                                <i class="fa-solid <?= sortIcon('userLastName') ?>"></i>
                            </button>
                        </div>
                    </td>

                    <td>
                        <div class="header-with-icon">
                            User Email
                            <button onclick="sortOperation('userEmail')" class="sort-btn">
                                <i class="fa-solid <?= sortIcon('userEmail') ?>"></i>
                            </button>
                        </div>
                    </td>

                    <td>Password</td>

                    <td>Admin?</td>

                    <td>
                        <div class="header-with-icon">
                            Date Registered
                            <button onclick="sortOperation('dateRegistered')" class="sort-btn">
                                <i class="fa-solid <?= sortIcon('dateRegistered') ?>"></i>
                            </button>
                        </div>
                    </td>

                    <td>Delete</td>
                </tr>


                <?php
                    if(mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $row['userID'] ?></td>
                            <td><?= $row['userFirstName'] ?></td>
                            <td><?= $row['userLastName'] ?></td>
                            <td><?= $row['userEmail'] ?></td>
                            <td><?= $row['userPassword'] ?></td>
                            <td><?= $row['admin'] ?></td>
                            <td><?= $row['dateRegistered'] ?></td>
                            <td>
                                <button onclick="deleteRecord(<?= $row['userID'] ?>)">
                                    <i style="color: red;" class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                <?php 
                    }
                } else { ?>
                    <tr>
                        <td colspan="7">0 results</td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

<script>
    function deleteRecord(id){
        if (confirm("Are you sure you want to delete this record?")) {
            window.location = "delete2.php?id=" + id;
        }
    }
    function sortOperation(column) {
        const url = new URL(window.location.href);
        const currentSort = url.searchParams.get("sort");
        const currentOrder = url.searchParams.get("order") || "ASC";

        let newOrder = "ASC";

        if (currentSort === column) {
            newOrder = currentOrder === "ASC" ? "DESC" : "ASC";
        }

        url.searchParams.set("sort", column);
        url.searchParams.set("order", newOrder);

        window.location.href = url.toString();
    }
</script>

