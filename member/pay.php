<!DOCTYPE html>
<html lang="en">

<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/member/pay.css">
    <?php require ($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>
</head>

    <?php
      if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'USER') {

      } else {
          // If not an admin, redirect to the login page or an error page
          header("Location: ../index.php");
          exit();
      }
   
    ?>
<body>
    <form method="POST" action="../functions.php" class="payment-form">
        <h1>Payment</h1>
        <div class="payment-choice">
            <p>How do you want to pay?</p>
            <label for="cash">
                <i class="fa-solid fa-money-bill"></i>Cash
                <input type="radio" id="cash" name="choice" value="cash" required>
            </label>
            <br>
            <label for="gcash">
                <i class="fa-brands fa-google"></i>G-Cash
                <input type="radio" id="gcash" name="choice" value="gcash" required>
            </label>
            <br>
            <input class="verify"  name="verify" type="submit" value="Proceed >">
        </div>
    </form>
</body>

</html>