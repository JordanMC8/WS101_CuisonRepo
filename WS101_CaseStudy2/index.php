<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGSystem</title>
    <style>
        body{
            margin: 0;
            padding: 0;
            
            font-family: Arial;
            color: white;
            
        }
        .tab{
            margin: 0px;
            background-color: #17a4dbff;
            padding-left: 10px;
            display: flex;

        }
        .container{
            margin: 0px;
            padding: 0px;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #64caf3ff;
        }
        .outer_circle{
            background-color: #26b2e9ff;
            border-radius: 50%;
            height: 600px; 
            width: 600px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .content{
            background-color: #17a4dbff;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            font-size: 40px;
            height: 400px; 
            width: 400px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
<div class ="tab">
    <h1>
        Student Grading System
    </h1>
</div>
<div class = "container">
    <div class = "outer_circle">
        <div class = "content">
<?php 
$name = $_GET["name"];
$score = $_GET["score"];?>
<?php echo "Name: $name"?>
<br>
<?php echo "Score: $score"?>
<br>
<?php
if ( $score <= 100 && $score >= 95 ) {
    $grade = "Excellent";
    $remark = 'A → "Outstanding Performance!"';
} else if ( $score <= 94 && $score >= 90 ) {
    $grade = "Very Good";
    $remark = 'B → "Great Job!"';
} else if ( $score <= 89 && $score >= 85 ) {
    $grade = "Good";
    $remark = 'C → "Good effort, keep it up!"';
} else if ( $score <= 84 && $score >= 75 ) {
    $grade = "Needs Improvement";
    $remark = 'D → "Work harder next time."';
} else if ( $score <= 74 && $score >= 0 ) {
    $grade = "Failed";
    $remark = 'F → "You need to improve."';
} else {
    $grade = "Error";
}
?>
<?php echo "Grade: $grade "?>
<br>
<br>
<?php echo $remark; ?>
        </div>
    </div>
</div>
</body>
</html>