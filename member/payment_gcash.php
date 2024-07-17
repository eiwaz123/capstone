<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/member/payment_gcash.css">
    <?php require ($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>
</head>

<body style="margin-top: 100px;">
<?php
  if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'USER') {

  } else {
      // If not an admin, redirect to the login page or an error page
      header("Location: ../index.php");
      exit();
  }

$data=gcash();
$gcash=$data['gcash']


?>
    <div class="payment-forms">
        <form action="../functions.php" method="POST" enctype="multipart/form-data">
            <h1 class="center">G-Cash Payment</h1>
            <?php if ($gcash) : ?>
                <?php foreach ($gcash as $g) : ?>
            <div class="pay-detail">
                <h3>Payment Details:</h3><br>
                <h3>Amount to pay: <span id="formattedAmount"><?php echo $g['balance_debt']; ?></span></h3>
                <h3>Due date: <?php echo $g['due_date']; ?></h3>
            </div>
            <br>
            <img src="../styles/images/gcash.jpg" alt="gcash-qr" class="qr" id="qrCode"
                draggable="false">
            <p style="text-align: center; font-weight: bold; padding: 5px;">Please scan using G-cash app</p>
            <div class="copy-container" id="copy">
                <span class="copy-text">09564629898</span>
                <button class="copy-button" onclick="copyText()"><i class="fa-regular fa-copy"></i> COPY</button>
            </div>
            <br>
            <div class="payment-details">
                <div class="rows-left">
                    <h3 class="det">Proof:</h3>
                </div>
                <div class="rows">
                    <div class="file-input-container">
                        <input class="file-input" type="file" name="proof" id="proof" accept="image/*" required>
                        <label class="file-input-label" for="proof">Choose Picture</label>
                    </div>
                </div>
                <div class="rows-left">
                    <h3 class="det">Amount:</h3>
                </div>
                <div class="rows">
                    <input type="text" name="amount" value="<?php echo $g['balance_debt']; ?>" title="Please put the exact amount"
                        placeholder="Amount of the payment" required>
                </div>
                <input class="pay" name="gcash_upload" type="submit" value="Pay">
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                var qrCode = document.getElementById("qrCode");
                var copyContainer = document.getElementById("copy");
                if (qrCode) qrCode.style.display = 'none';
                if (copyContainer) copyContainer.style.display = 'block'; // Adjust this based on your layout
            } else {
                var qrCode = document.getElementById("qrCode");
                var copyContainer = document.getElementById("copy");
                if (qrCode) qrCode.style.display = 'block';
                if (copyContainer) copyContainer.style.display = 'none'; // Adjust this based on your layout
            }

            function addCommas(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            var amountElement = document.getElementById("formattedAmount");
            amountElement.innerText = addCommas(amountElement.innerText);
        });

        function copyText() {
            // Get the text to copy
            const textToCopy = document.querySelector('.copy-text');

            // Create a textarea element to hold the text temporarily
            const textarea = document.createElement('textarea');
            textarea.value = textToCopy.innerText;
            document.body.appendChild(textarea);

            // Select the text in the textarea
            textarea.select();
            textarea.setSelectionRange(0, 99999); // For mobile devices

            // Copy the selected text to the clipboard
            document.execCommand('copy');

            // Remove the textarea from the DOM
            document.body.removeChild(textarea);

            // Optionally, provide feedback to the user
            alert('Text copied to clipboard!');
        }
    </script>
</body>

</html>