<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/member/payment_cash.css">
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>
</head>


<body>
    <?php
      if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'USER') {

      } else {
          // If not an admin, redirect to the login page or an error page
          header("Location: ../index.php");
          exit();
      }
  
    
    $data=member_payment_cash();
    $cash=$data['cash'];
    $message=$data['message'];
    $code=$data['code'];
    
    
    ?>
    <?php 
    if (isset($_SESSION['error'])) {
        echo $_SESSION['error'];
        unset($_SESSION['error']);
    }
        
        ?>
 
    <form method="POST" action="../functions.php" class="payment-form">
        <h1 class="center">Cash Payment</h1>
        <?php if ($cash) : ?>
            <?php foreach ($cash as $c) : ?>
                <div class="pay-detail">
                    <h3 class="center">Payment Details: </h3>
                    <h3>Amount to pay: <span id="formattedAmount"><?= htmlspecialchars($c[2]) ?></span></h3>
                    <h3>Due date: <?= date('F d, Y', strtotime($c[6])) ?></h3>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    <h3 class="instruct">Instructions:</h3>
    <p class="instruction">Go to admin and present this code upon giving your payment to confirm your
        transaction:</p>
    <h2 class="code"><?php echo $code   ?><strong></strong></h2>
    <p class="instruction">This can be found in transaction history page.</p>

    <p class="code-instruction"><strong>Enter the code displayed above to proceed:</strong></p>

    <input type="text" name="code-confirm" class="code-confirm" placeholder="Enter the code" maxlength="5" pattern="[A-Za-z0-9]*" title="Enter the code" style="text-transform:uppercase;" autofocus required autocomplete="false" style="text-transform:uppercase;">
    <input class="pay" type="submit" value="Proceed" name="proceed">
    </form>
</body>
<script>
    function addCommas(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }

    var amountElement = document.getElementById("formattedAmount");
    amountElement.innerText = addCommas(amountElement.innerText);
</script>

</html>