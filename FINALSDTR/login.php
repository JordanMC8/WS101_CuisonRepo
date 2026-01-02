<?php

    include("header.php");

?>

<div class="spacer">
    <h3 style="text-align: center;">Welcome back user!</h3>

    <div class="forms">
        <form method="POST" action="emailverification.php" class="grid-form">

            <label for="email">Email:</label>
            <input type="email" required id="email" name="email">

            <label for="password">Password:</label>
            <div class="password-wrapper">
                <input type="password" required id="password" name="password">
                <i class="fa-solid fa-eye toggle-eye" onclick="togglePassword('password', this)"></i>
            </div>
        
            <div class="button">
                <input type="submit" name="submitLogin" value="Login">
            </div>
        </form>
            <h3>Don't have an account yet? <a href="index.php">Click here.</a></h3>
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

