<?php
if (isset($_POST['submit'])) {
    $target_dir = "uploads/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = basename($_FILES["myfile"]["name"]);
    $fileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $newFileName = uniqid("file", true) . "." . $fileType;
    $target_file = $target_dir . $newFileName;

    $uploadOk = 1;
    if ($_FILES["myfile"]["size"] > 2 * 1024 * 1024) {
        $uploadOk = 0;
        $uploadError = "File too large (max 2MB).";
    }
    $allowed_types = ["jpg", "jpeg", "png", "gif", "pdf"];
    if (!in_array($fileType, $allowed_types)) {
        $uploadOk = 0;
        $uploadError = "Invalid file type.";
    }

    if ($uploadOk == 1) {
        move_uploaded_file($_FILES["myfile"]["tmp_name"], $target_file);
    }
} else {
    echo "No form submitted.";
    exit;
}

// Collect data
$desiredPos = htmlspecialchars($_POST['desiredPos']);
$fullName = htmlspecialchars($_POST['fullName']);
$gender = htmlspecialchars($_POST['gender']);
$cityAddress = htmlspecialchars($_POST['cityAddress']);
$provAddress = htmlspecialchars($_POST['provAddress']);
$phoneNum = htmlspecialchars($_POST['phoneNum']);
$email = htmlspecialchars($_POST['email']);
$birthdate = htmlspecialchars($_POST['birthdate']);
$birthplace = htmlspecialchars($_POST['birthplace']);
$civilStatus = htmlspecialchars($_POST['civilStatus']);
$citizenship = htmlspecialchars($_POST['citizenship']);
$height = htmlspecialchars($_POST['height']);
$weight = htmlspecialchars($_POST['weight']);
$religion = htmlspecialchars($_POST['religion']);
$spouse = htmlspecialchars($_POST['spouse']);
$spouse_Occup = htmlspecialchars($_POST['spouse_Occup']);
$fatherName = htmlspecialchars($_POST['fatherName']);
$father_Occup = htmlspecialchars($_POST['father_Occup']);
$motherName = htmlspecialchars($_POST['motherName']);
$mother_Occup = htmlspecialchars($_POST['mother_Occup']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bio-Data Result</title>
  <style>
    body {
      background: url('your-background.jpg') no-repeat center center fixed;
      font-family: Arial;
      background: #f4f4f4;
      display: flex;
      justify-content: center;
      padding: 30px;
    }
    .result-box {
      background: rgba(255, 255, 255, 0.0);;
      padding: 20px 40px;
      border-radius: 8px;
      box-shadow: none;
      width: 800px;
      margin: 20px auto;
    }
    .header-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .header-row h1 {
      margin: 0;
      text-transform: uppercase;
      white-space: nowrap;
    }
    .header-row img {
      max-width: 120px;
      max-height: 120px;
      border: 1px solid #ccc;
      border-radius: 4px;
      object-fit: cover;
    }
    .section {
      margin-top: 20px;
      border: 1px solid #000;
      border-radius: 4px;
      padding: 15px;
    }
    .section h2 {
      margin: -15px -15px 15px -15px;
      background: #333;
      color: #fff;
      font-size: 16px;
      padding: 6px 10px;
      border-top-left-radius: 4px;
      border-top-right-radius: 4px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    td {
      padding: 8px;
      vertical-align: top;
      width: 50%;
    }
    label {
      font-weight: bold;
      display: block;
      margin-bottom: 4px;
    }
    .value {
      padding: 6px;
      border: none;
      border-radius: 4px;
      background: transparent;
    }
  </style>
</head>
<body>
  <div class="result-box">
    <div class="header-row">
      <h1>Bio-Data</h1>
      <?php if ($uploadOk == 1): ?>
        <img src="<?= $target_file ?>" alt="Uploaded Photo">
      <?php else: ?>
        <span style="color:red;"><?= $uploadError ?></span>
      <?php endif; ?>
    </div>

    <div class="section">
      <h2>Personal Data</h2>
      <table>
        <tr>
          <td><label>Desired Position:</label><div class="value"><?= $desiredPos ?></div></td>
          <td><label>Full Name:</label><div class="value"><?= $fullName ?></div></td>
        </tr>
        <tr>
          <td><label>Gender:</label><div class="value"><?= $gender ?></div></td>
          <td><label>City Address:</label><div class="value"><?= $cityAddress ?></div></td>
        </tr>
        <tr>
          <td><label>Provincial Address:</label><div class="value"><?= $provAddress ?></div></td>
          <td><label>Phone Number:</label><div class="value"><?= $phoneNum ?></div></td>
        </tr>
        <tr>
          <td><label>Email:</label><div class="value"><?= $email ?></div></td>
          <td><label>Birthdate:</label><div class="value"><?= $birthdate ?></div></td>
        </tr>
        <tr>
          <td><label>Place of Birth:</label><div class="value"><?= $birthplace ?></div></td>
          <td><label>Civil Status:</label><div class="value"><?= $civilStatus ?></div></td>
        </tr>
        <tr>
          <td><label>Citizenship:</label><div class="value"><?= $citizenship ?></div></td>
          <td><label>Height:</label><div class="value"><?= $height ?> cm</div></td>
        </tr>
        <tr>
          <td><label>Weight:</label><div class="value"><?= $weight ?> kg</div></td>
          <td><label>Religion:</label><div class="value"><?= $religion ?></div></td>
        </tr>
        <tr>
          <td><label>Spouse:</label><div class="value"><?= $spouse ?></div></td>
          <td><label>Spouse's Occupation:</label><div class="value"><?= $spouse_Occup ?></div></td>
        </tr>
        <tr>
          <td><label>Father's Name:</label><div class="value"><?= $fatherName ?></div></td>
          <td><label>Father's Occupation:</label><div class="value"><?= $father_Occup ?></div></td>
        </tr>
        <tr>
          <td><label>Mother's Name:</label><div class="value"><?= $motherName ?></div></td>
          <td><label>Mother's Occupation:</label><div class="value"><?= $mother_Occup ?></div></td>
        </tr>
      </table>
    </div>
  </div>
</body>
</html>
