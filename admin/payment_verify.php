<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/admin/payment_verify.css">
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>
</head>

<body style="padding-top: 100px; background-color:#6FB9B9;">
    <div class="container">
        <h2>Payment Verification</h2>
        <?php

        $transac_id = $_GET['id'];
        $datas = payment_verify($transac_id);
        $to_verify = $datas['data'];

        $_SESSION['member'] = [
            'user_id' => $to_verify['user_id'],
            'transac_id' => $to_verify['transac_id']

        ];


        if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'ADMIN') {

        } else {
            // If not an admin, redirect to the login page or an error page
            header("Location: ../index.php");
            exit();
        }



        ?>
        <?php if ($to_verify) : ?>
                
            <?php
              if(isset($_SESSION['error'])){
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            
            }
                ?>
            <form action="../functions.php?id=<?php echo $to_verify['transac_id']; ?>" method="POST">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <span id="name"><?php echo htmlspecialchars($to_verify['given_name']); ?> <?php echo htmlspecialchars($to_verify['middle_name']); ?> <?php echo htmlspecialchars($to_verify['last_name']); ?></span>
                </div>
                <div class="form-group">
                    <label for="due">Due Date:</label>
                    <span id="due"><?php echo htmlspecialchars($to_verify['due_date']); ?></span>
                </div>
                <div class="form-group">
                    <label for="transac_type">Transaction Type:</label>
                    <span id="transac_type"><?php echo htmlspecialchars($to_verify['transc_type']); ?></span>
                </div>
                <div class="form-group">
                    <label for="amount">Amount: ₱</label>
                    <input type="number" id="amount" name="amount" value="<?php echo htmlspecialchars($to_verify['balance_debt']); ?>" required>
                </div>
                <?php if ($to_verify['transc_type'] == 'Cash') : ?>
                    <div class="form-group cash-verify">
                        <label for="code">Enter the code to verify the payment:</label>
                        <input type="text" id="code" name="code" required>
                    </div>
                <?php else : ?>
                    <div class="form-group">
                        <label for="proof">Proof of Payment:</label>
                        
                        <img src="../<?php echo$to_verify['proof'];?>" width="300px" height="300px"  alt="Proof"  draggable="false">
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <input type="submit" name="verified"  class="button" value="Verify Payment">
                </div>
            </form>
            <!-- <img src="../styles/images/face-scan.png" width="250px" height="200px"   alt=""> -->
        <?php else : ?>
            <p>No transactions to verify.</p>
        <?php endif; ?>

    </div>
</body>

</html>