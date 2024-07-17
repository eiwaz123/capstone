<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/admin/view-history.css">

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>
</head>

<body style="padding-top: 100px;">
    <div class="modal-container">
        <div class="modal-content">
            <h2 style="padding-bottom: 20px; text-align: center;">Transaction History</h2>
            <div class="modal-line">
                <div class="modal-label">Name:</div>
                <div class="modal-value">
                    <?php $id = $_GET['id'];
                    if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'USER') {

                    } else {
                        // If not an admin, redirect to the login page or an error page
                        header("Location: ../index.php");
                        exit();
                    }
                    $view = view_history($id);
                    $transaction = $view['viewHistory'];

                    ?>
                    <?php if ($transaction) :  ?>
                        <?php foreach ($transaction as $transactions) : ?>
                            <?php echo htmlspecialchars($transactions['last_name']) . ', ' . htmlspecialchars($transactions['given_name']) . ' ' . htmlspecialchars($transactions['last_name']); ?>
                </div>
            </div>
            <div class="modal-line">
                <div class="modal-label">Transaction Type:</div>
                <div class="modal-value"><?php echo htmlspecialchars($transactions['transc_type']); ?></div>
            </div>
            <div class="modal-line">
                <div class="modal-label">Date:</div>
                <div class="modal-value"><?php echo htmlspecialchars($transactions['date']); ?></div>
            </div>
            <div class="modal-line">
                <div class="modal-label">Amount:</div>
                <div class="modal-value"><?php echo htmlspecialchars($transactions['amount']); ?></div>
            </div>
            <div class="modal-line">
                <div class="modal-label">Date Paid:</div>
                <div class="modal-value"><?php echo htmlspecialchars($transactions['date']); ?></div>
            </div>
            <?php if ($transactions['transc_type'] == 'Cash') : ?>
                <div class="modal-line">
                    <div class="modal-label">Code:</div>
                    <div class="modal-value"><?php echo htmlspecialchars($transactions['code']); ?></div>
                </div>
            <?php else : ?>
                <div class="modal-line">
                    <div class="modal-label">Proof:</div>
                    <div class="modal-value">
                        <img src="../<?php echo $transactions['proof']; ?>" width="300px" height="300px" alt="Proof" draggable="false">
                        
                    </div>
                </div>
            <?php endif; ?>

        <?php endforeach; ?>
    <?php else : ?>



    <?php endif; ?>
    <a href="./payment_history.php" class="go-back">
        < Back</a>
        </div>
    </div>

</body>

</html>