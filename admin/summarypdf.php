<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Pdf</title>
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/admin/summary.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/hofin/functions.php'); ?>
</head>

<body>
    <?php
    if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'ADMIN') {
    } else {
        // If not an admin, redirect to the login page or an error page
        header("Location: ../index.php");
        exit();
    }

    $data = payment_history();
    $history = $data['history'];
    ?>
    <?php if ($history) : ?>
        <!--  -->
        <div id="invoice">
            <div class="half">
                <div class="half-half">
                    <img src="../styles/images/logo-s.png" alt="" srcset="">
                    <h2 styles="text-align: center;">Approved Payments</h2>
                </div>
            </div>
            <div class="scrollable">
                <table id="approvedTable" class="table">
                    <tr>
                        <th class="sort-btn" onclick="sortTable(0, 'approvedTable')">Name</th>
                        <th class="sort-btn" onclick="sortTable(2, 'approvedTable')">Transaction Type</th>
                        <th class="sort-btn" onclick="sortTable(2, 'approvedTable')">Due Date</th>
                        <th class="sort-btn" onclick="sortTable(3, 'approvedTable')">Date Paid</th>
                        <th>Amount</th>
                        <th>Is Verified</th>
                        <?php
                        $data = payment_history();
                        $history = $data['history'];
                        if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'ADMIN') {
                        } else {
                            // If not an admin, redirect to the login page or an error page
                            header("Location: ../index.php");
                            exit();
                        }

                        ?>
                        <?php if ($history) : ?>

                            <?php foreach ($history as $histories) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($histories['last_name']) . ', ' . htmlspecialchars($histories['middle_name']) . ' ' . htmlspecialchars($histories['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($histories['transc_type']); ?></td>
                        <td><?php echo htmlspecialchars($histories['due_date']); ?></td>
                        <td><?php echo htmlspecialchars($histories['date']); ?></td>
                        <td><?php echo htmlspecialchars($histories['amount']); ?></td>
                        <td><?php echo htmlspecialchars($histories['is_verified']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">No Result</td>
                </tr>
            <?php endif; ?>

                </table>

            </div>

            <!--  -->

        <?php endif; ?>

        </div>


        <button class="btn-print" id="download-button">PRINT</button>

</body>
<script>
    const button = document.getElementById('download-button');

    function generatePDF() {
        // Choose the element that your content will be rendered to.
        const element = document.getElementById('invoice');
        // Choose the element and save the PDF for your user.
        html2pdf().from(element).save();
    }

    button.addEventListener('click', generatePDF);
</script>

</body>




</html>