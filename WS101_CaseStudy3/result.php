<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            border-collapse: collapse;
        }
        td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
        .odd {
            background-color: yellow;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php
    $rows = (int)$_POST["rows"];
    $cols = (int)$_POST["cols"];


    echo "<table>";


    echo "<tr><td>X</td>";
    for ($j = 1; $j <= $cols; $j++) {
        if ($j % 2 == 1) {
            echo "<td class='odd'>$j</td>";
        } 
        else {
            echo "<td>$j</td>";
        }
    }
    echo "</tr>";

    
    for ($i = 1; $i <= $rows; $i++) {
        echo "<tr>";

     
        if ($i % 2 == 1) {
            echo "<td class='odd'>$i</td>";
        } else {
            echo "<td>$i</td>";
        }

    
        for ($j = 1; $j <= $cols; $j++) {
            $value = $i * $j;
            if ($value % 2 == 1) {
                echo "<td class='odd'>$value</td>";
            } else {
                echo "<td>$value</td>";
            }
        }
        echo "</tr>";
    }

    echo "</table>";
?>

</body>
</html>
