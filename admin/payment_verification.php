<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Verification</title>
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/admin/payment_verification.css">
  
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>
</head>

<body style="padding-top: 100px;">
    <h1>Payment Verification</h1>

    <div class="half">
        <div class="half-half">
            <h2>For Payment Approval:</h2>
        </div>
        <div class="half-half">
            <input type="text" id="searchverification" class="search-input" onkeyup="filterTable('searchverification', 'verificationtable')" placeholder="Search for info...">
        </div>
    </div>

    <?php
    $data = payment_verification();
    $unverified = $data['unverified'];
    if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'ADMIN') {

    } else {
        // If not an admin, redirect to the login page or an error page
        header("Location:../index.php");
        exit();
    }
    ?>
    <div class="scrollable">
        <table id="verificationtable" class="table">
            <tr>
                <th class="sort-btn" onclick="sortTable(0, 'verificationtable')">Transaction Type</th>
                <th>Amount to pay</th>
                <th>Manage</th>
            </tr>
            <?php if (!empty($unverified)) : ?>
                <?php foreach ($unverified as $unverifieds) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($unverifieds['transc_type']); ?></td>
                        <td><span id="formattedAmount"><?php echo htmlspecialchars($unverifieds['balance_debt']); ?></span></td>
                        <td>
                            <form action="payment_verify.php?id=<?php echo htmlspecialchars($unverifieds['transac_id']); ?>" method="POST" class="payment">
                                <input type="submit" name="verification"  class="btn-view" value="Verify" >
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">No Result</td>
                </tr>
            <?php endif; ?>

        </table>
    </div>

    <script>
        function filterTable(inputId, tableId) {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById(inputId);
            filter = input.value.toUpperCase();
            table = document.getElementById(tableId);
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, including the first row (header row)
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td");
                // Start from index 0 since the first row is included
                for (j = 0; j < td.length; j++) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        break;
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function sortTable(columnIndex, tableId) {
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById(tableId);
            switching = true;
            dir = "asc"; // Set the sorting direction to ascending by default

            while (switching) {
                switching = false;
                rows = table.rows;

                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[columnIndex];
                    y = rows[i + 1].getElementsByTagName("td")[columnIndex];

                    if (dir == "asc") {
                        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    } else if (dir == "desc") {
                        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                            shouldSwitch = true;
                            break;
                        }
                    }
                }
                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                } else {
                    if (switchcount == 0 && dir == "asc") {
                        dir = "desc";
                        switching = true;
                    }
                }
            }
        }
    </script>
</body>

</html>