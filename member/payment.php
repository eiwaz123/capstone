<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/member/payment.css">
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>
</head>

<body>
    <div class="table">
        <h1>Members Payment</h1>
        <?php
        if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'USER') {
        } else {
            // If not an admin, redirect to the login page or an error page
            header("Location: ../index.php");
            exit();
        }


        $data = payment();
        $arranger = $data['pending'];


        ?>

        <table>
            <thead>
                <tr>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <?php

            ?>
            <?php if ($arranger) : ?>
                <?php foreach ($arranger as $arrangers) : ?>
                    <tr>
                        <td><span id="formattedAmount"><?php echo htmlspecialchars($arrangers['balance_debt'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                        <td><?php echo htmlspecialchars(date('F d, Y', strtotime($arrangers['due_date'])), ENT_QUOTES, 'UTF-8'); ?></td>
                        <td>
                            <form action="pay.php?id=<?php echo htmlspecialchars($arrangers['transac_id'], ENT_QUOTES, 'UTF-8'); ?>" method="post">
                                <input type="submit" class="fa fas pay" value="&#xf0d6; Pay">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">No Bills Yet</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
<script>
    function addCommas(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    var amountElement = document.getElementById("formattedAmount");
    amountElement.innerText = addCommas(amountElement.innerText);
</script>
</body>

</html>