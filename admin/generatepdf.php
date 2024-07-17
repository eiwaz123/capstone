<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Pdf</title>
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/admin/generatepdf.css">
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
    $id = $_GET['id'];
    $data = generate_pdf($id);
    $info = $data['generate']
    ?>
    <div id="invoice">
        <div id="pspdfkit-header">
            <div class="header-columns">
                <div class="logotype">
                    <img class="logo" src="../styles/images/logo-s.png" />
                    <p>Flovi Homes</p>
                </div>

                <div>

                </div>
            </div>
        </div>

        <div class="page" style="page-break-after: always">
            <div>

                <h2>Invoice <?php $date=date("Y/m/d");  echo   str_replace('/', '', $date);  ?></h2>
                <h1>
                    <center>Amortization Payment</center>
                </h1>
            </div>
            <?php if ($info) : ?>
                <?php foreach ($info as $generate) : ?>
                    <div class="intro-table">
                        <div class="intro-form intro-form-item">
                            <p class="intro-table-title">Billed To:</p>
                            <p>
                                <?php echo htmlspecialchars($generate['last_name']) . ', ' . htmlspecialchars($generate['given_name']) . ' ' . htmlspecialchars($generate['last_name']); ?>
                            </p>
                        </div>

                        <div class="intro-form">
                            <div class="intro-form-item-border">
                                <p class="intro-table-title">Payment Date:</p>
                                <p>
                                    <?php echo htmlspecialchars($generate['date']); ?>
                                </p>
                            </div>

                            <div class="intro-form-item-border">
                                <p class="intro-table-title">Payment Method:</p>
                                <p>
                                    <?php echo htmlspecialchars($generate['transc_type']); ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="table-box">
                        <table cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr class="heading">
                                    <td>Description</td>
                                    <td>Amount</td>
                                    <td></td>
                                    <td></td>
                                </tr>


                                <tr class="item">
                                    <td>PRINCIPAL INTEREST</td>
                                    <td> <?php echo htmlspecialchars($generate['principal_interest']); ?></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr class="item">
                                    <td>MRI</td>
                                    <td> <?php echo htmlspecialchars($generate['MRI']); ?></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="summary-box">
                        <table cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr class="item">
                                    <td></td>
                                    <td>Total:<?php echo htmlspecialchars($generate['total']); ?> </td>
                                    <td></td>
                                </tr>
                                <tr class="total">
                                    <td></td>
                                    <td>Amount Paid:<?php echo htmlspecialchars($generate['amount']); ?></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
       
        <div class="page" style="page-break-after: always">
            <div class="form">
                <label for="notes" class="label"> Notes: </label>
                <input type="text" id="notes" class="border-bottom" value="" />
            </div>
            <div class="signer">
                <div class="form signer-item">
                    <label for="date" class="label">Date:</label>
                    <input type="text" id="date" class="border-bottom" value="<?php echo  date("Y/m/d") ?>" />
                </div>
                <div class="form signer-item">
                    <label for="signature" class="label">Issued by:</label>
                    <input type="text" id="signature" class="border" value="Sign Here" />
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

    </div>
    </div>

</div>
<button class="btn-view" id="download-button">PRINT</button>

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

</html>

</html>