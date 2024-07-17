<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/member/home.css">
    <?php require ($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>
</head>

<body style="padding-top: 100px;">

    <h1 class="center" style="padding-top: 20px;">Members Information</h1>
    <?php
    $data=home();
    $unverfied_payment=count($data['row']);
    $verfied_payment=count($data['history']);
    // $id=$_SESSION['user_id'];
    if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'USER') {

    } else {
        // If not an admin, redirect to the login page or an error page
        header("Location: ../index.php");
        exit();
    }

    // echo $id;
    
    ?>
    <div class="container">
        <div class="card">
            <h2>Pending Bills</h2>
            <p>Total Unpaid Bills:<?php echo $unverfied_payment    ?> </p>
            <a href="payment.php" class="button">Pay</a>
        </div>
        <div class="card">
            <h2>Verified Payments</h2>
            <p>Total transactions: <?php echo $unverfied_payment ?> </p>
            <a href="/members/payment_history" class="button">View History</a>
        </div>
    </div>

</body>

</html>