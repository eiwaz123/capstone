<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>

    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/member/account.css">
    <?php require ($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>
</head>

<body style="margin-top: 100px;">
    <h1 class="reg">Register Account</h1>
    <div class="centered">
        <form action="" method="POST">
            <div class="account-information">
                <br>
                <h3 class="info">Account Information</h3>

                <label for="username">Username <p></p></label>
                <input type="text" name="username" placeholder="Username" required>

                <div class="account-info">
                    <div class="account-info-row">
                        <div class="account-info-col"><label for="password">Password</label></div>
                        <div class="account-info-col"><input type="password" name="password" required
                                placeholder="Password">
                        </div>
                    </div>
                    <div class="account-info-row">
                        <div class="account-info-col"><label for="confirm-password">Confirm Password</label></div>
                        <div class="account-info-col-last"><input type="password" name="confirm" required
                                placeholder="Confirm Password"></div>
                    </div>
                </div>


                <label for="email">Email
                    <input type="email" name="email" placeholder="Email" required>
                </label>

            </div>
            <button class="button" id="hoverinbtn" type="submit">Update Account</button>
            <a href="/login" class="login">Back</a>
        </form>
    </div>


</html>