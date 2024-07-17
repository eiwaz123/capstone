<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/index.css">
    <?php require ($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>
</head>

<body>


    <div class="left"><img draggable="false" class="image" src="./styles/images/logo-b.png" alt="logo">
    </div>
    <div class="right">
        <div class="centered">

            <?php
       
            if (isset($_POST['login'])) {

                $username = $_POST['username'];
                $password = $_POST['password'];
                $response = login($username, $password);
                if ($response != "success") {
                    echo "<label>
                    <input type='checkbox' class='alertCheckbox' autocomplete='off' />
                    <div class='alert warna'>
                        <span class='alertClose'>X</span>
                        <span class='alertText'>
                        <i class='fa-solid fa-x'></i> " . $response . "
                        <br class='clear' /></span>
                    </div>
                    </label>";


                } else {
                    $_POST['username'] = '';
                    $_POST['password'] = '';


                    echo "<label>
                    <input type='checkbox' class='alertCheckbox' autocomplete='off' />
                    <div class='alert successa'>
                        <span class='alertClose'>X</span>
                        <span class='alertText'>
                        <i class='fa-solid fa-check'></i> Login Success
                        <br class='clear' /></span>
                    </div>
                    </label>";
                }
            }
            ?>

            <form action="" method="POST" autocomplete="off">
                <div class="imgcontainer">
                    <img draggable="false" src="./styles/images/logo-s.png" alt="Avatar" class="avatar">
                    <h1 style="color: #545454;">LOGIN</h1>
                </div>
                <br><br>
                <label for="uname">Username:</label>

                <input type="text" name="username" id="uname" pattern="^\S{3,}$"
                    title="Username must have 3 characters and cannot have spaces" placeholder="Enter Username" required
                    autocomplete="false" value="<?php echo @$_POST['username']; ?>" class="<?php
                       if (isset($response) && $response != 'success') {
                           echo 'wrong';
                       } else {
                           echo '';
                       }
                       ?>">
                <br><br>
                <label for="psw">Password:</label>
                <input type="password" name="password" id="psw" pattern="^\S{3,}$"
                    title="Password must have 3 characters and cannot have spaces" placeholder="Enter Password" required
                    value="<?php echo @$_POST['password']; ?>" autocomplete="false" class="<?php
                       if (isset($response) && $response != 'success') {
                           echo 'wrong';
                       } else {
                           echo '';
                       }
                       ?>">
                <input type="submit" class="button" value="Login" name="login">
                <a href="./face_login.php" class="face-password-link">Face Login</a>
                <a href="./register.php" class="face-password-link">Register</a>
            </form>

        </div>
    </div>
</body>
<script>
    function updateRemainingTime() {
        var remainingTimeElement = document.getElementById('remainingTime');
        var remainingTime = parseInt(remainingTimeElement.innerHTML);
        remainingTime--;

        if (remainingTime <=5) {
            window.location.reload(); // For example, reload the page
        } else {
            remainingTimeElement.innerHTML = remainingTime;
            setTimeout(updateRemainingTime, 0); // Update every second
        }
    }
    // Call the function initially
    updateRemainingTime();



</script>

</html>