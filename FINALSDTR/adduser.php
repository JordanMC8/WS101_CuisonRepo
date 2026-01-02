<?php
    include("header.php");
?>

<div class="spacer">
    <h3 style="text-align: center;">Add a user</h3>

    <div class="forms">
        <form method="POST" action="emailverification.php" class="grid-form">

            <label for="firstName">First Name:</label>
            <input type="text" required id="firstName" name="firstname">

            <label for="lastname">Last Name:</label>
            <input type="text" required id="lastname" name="lastname">

            <label for="email">Email:</label>
            <input type="email" required id="email" name="email">

            <label for="password">Password:</label>
            <div class="password-wrapper">
                <input type="password" required id="password" name="password"
                pattern="^(?=.*[A-Z])(?=.*\d)(?=.*_).{8,}$"
                title="Password must be at least 8 characters and include a capital letter, a number, and an underscore.">
                <i class="fa-solid fa-eye toggle-eye" onclick="togglePassword('password', this)"></i>
            </div>

            <label for="admin">Grant Admin Privileges?</label>
            <label class="custom-checkbox">
                <input type="checkbox" id="admin" name="admin" value="1">
                <span class="checkmark"></span>
            </label>

        
            <div class="button">
                <input type="submit" name="submitRegister" value="Add User">
            </div>
        </form>
            <h3><a href="manageusers.php">Go back.</a></h3>
    </div>
</div>


<script>
    function togglePassword(id, icon) {
        const field = document.getElementById(id);

        if (field.type === "password") {
            field.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            field.type = "password";
            icon.classList.add("fa-eye");
            icon.classList.remove("fa-eye-slash");
        }
    }
</script>

