<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bio-Data</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f4f4;
      display: flex;
      justify-content: center;
      padding: 30px;
    }

    form {
      background: #fff;
      padding: 20px 40px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      width: 900px;
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

    .header-row input[type="file"] {
      border: none;
      padding: 6px;
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

    input, select {
      width: 95%;
      padding: 6px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    input[type="file"] {
      width: auto;
    }

    input[type="submit"] {
      margin-top: 20px;
      background: #333;
      color: #fff;
      border: none;
      padding: 10px 20px;
      width: auto;
      border-radius: 4px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background: #555;
    }
    .submit-container {
    text-align: center; 
    margin-top: 20px;
    }
  </style>
</head>
<body>
  <form action="result.php" method="post" enctype="multipart/form-data" onsubmit="return validationField();">

    <div class="header-row">
      <h1>Bio-Data</h1>
      <input type="file" name="myfile" id="myfile">
    </div>

    <div class="section">
      <h2>Personal Data</h2>
      <table>
        <tr>
          <td>
            <label for="desiredPos">Desired Position:</label>
            <input type="text" id="desiredPos" name="desiredPos">
          </td>
          <td>
            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" name="fullName">
          </td>
        </tr>
        <tr>
          <td>
            <label for="gender">Gender:</label>
            <input type="text" id="gender" name="gender">
          </td>
          <td>
            <label for="cityAddress">City Address:</label>
            <input type="text" id="cityAddress" name="cityAddress">
          </td>
        </tr>
        <tr>
          <td>
            <label for="provAddress">Provincial Address:</label>
            <input type="text" id="provAddress" name="provAddress">
          </td>
          <td>
            <label for="phoneNum">Phone No.:</label>
            <input type="number" id="phoneNum" name="phoneNum">
          </td>
        </tr>
        <tr>
          <td>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email">
          </td>
          <td>
            <label for="birthdate">Date of Birth:</label>
            <input type="date" id="birthdate" name="birthdate">
          </td>
        </tr>
        <tr>
          <td>
            <label for="birthplace">Place of Birth:</label>
            <input type="text" id="birthplace" name="birthplace">
          </td>
          <td>
            <label for="civilStatus">Civil Status:</label>
            <select id="civilStatus" name="civilStatus">
              <option value="" disabled selected>Select option</option>
              <option value="Single">Single</option>
              <option value="Married">Married</option>
              <option value="Widowed">Widowed</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <label for="citizenship">Citizenship:</label>
            <input type="text" id="citizenship" name="citizenship">
          </td>
          <td>
            <label for="height">Height:</label>
            <input type="number" id="height" name="height" placeholder="cm">
          </td>
        </tr>
        <tr>
          <td>
            <label for="weight">Weight:</label>
            <input type="number" id="weight" name="weight" placeholder="kg">
          </td>
          <td>
            <label for="religion">Religion:</label>
            <input type="text" id="religion" name="religion">
          </td>
        </tr>
        <tr>
          <td>
            <label for="spouse">Spouse:</label>
            <input type="text" id="spouse" name="spouse">
          </td>
          <td>
            <label for="spouse_Occup">Occupation of Spouse:</label>
            <input type="text" id="spouse_Occup" name="spouse_Occup">
          </td>
        </tr>
        <tr>
          <td>
            <label for="fatherName">Father's Name:</label>
            <input type="text" id="fatherName" name="fatherName">
          </td>
          <td>
            <label for="father_Occup">Occupation of Father:</label>
            <input type="text" id="father_Occup" name="father_Occup">
          </td>
        </tr>
        <tr>
          <td>
            <label for="motherName">Mother's Name:</label>
            <input type="text" id="motherName" name="motherName">
          </td>
          <td>
            <label for="mother_Occup">Occupation of Mother:</label>
            <input type="text" id="mother_Occup" name="mother_Occup">
          </td>
        </tr>
      </table>
    </div>

    <div class="submit-container">
        <input type="submit" name="submit" value="Submit">
    </div>
  </form>

  <script>
    function validationField(){
      var requiredFields = [
        "desiredPos","fullName","gender","cityAddress","provAddress",
        "phoneNum","email","birthdate","birthplace","civilStatus",
        "citizenship","height","weight","religion","spouse",
        "spouse_Occup","father_Occup","mother_Occup"
      ];
      var filter = /^[a-zA-Z0-9\s.,'-]+$/;

      for (let i = 0; i < requiredFields.length; i++) {
        let field = document.getElementById(requiredFields[i]);
        if (field.value.trim() === "" || field.value === null) {
          alert("All fields are required.");
          field.focus();
          return false; 
        }
      }
      return true; 
    }
  </script>
</body>
</html>
