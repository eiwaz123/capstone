<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAYMENT ARRANGEMENT</title>
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/admin/payment_arrangement.css">
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>
</head>

<body>
    <div class="width">
        <h1>PAYMENT ARRANGEMENT</h1>
        <br>

        <div class="half">
            <div class="half-half">
                <h2>New Accounts:</h2>
            </div>
            <div class="half-half">
                <input type="text" id="searchArrangement" class="search-input" onkeyup="filterTable('searchArrangement', 'arrangementTable')" placeholder="Search for names...">
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th class="sort-btn" onclick="sortTable(0, 'arrangementTable')">Name</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'ADMIN') {

                } else {
                    // If not an admin, redirect to the login page or an error page
                    header("Location: ../index.php");
                    exit();
                }

                $data = paymentarrangement();
                $old = $data['old'];
                $new = $data['new'];


                ?>
                <?php if ($new) : ?>
                    <?php foreach ($new as $newacc) : ?>
                        <tr>
                            <td><?php echo $newacc['last_name']; ?>, <?php echo $newacc['given_name']; ?> <?php echo $newacc['middle_name']; ?></td>
                            <td><span id="formattedAmount"><?php echo $newacc['total']; ?></span></td>
                            <td>
                                <a class="button remind" href="payment_arrange.php?id=<?php echo $newacc['user_id']; ?>">Arrange Payment</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3">No account to manage</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>



        <div class="half">
            <div class="half-half">
                <h2>Old Accounts:</h2>
            </div>
            <div class="half-half">
                <input type="text" id="searchArrangement" class="search-input" onkeyup="filterTable('searchArrangement', 'arrangementTable')" placeholder="Search for names...">
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th class="sort-btn" onclick="sortTable(0, 'arrangementTable')">Name</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($old): ?>
                    <?php foreach ($old as $oldacc) : ?>
                        <tr>
                            <td><?php echo $oldacc['last_name']; ?>, <?php echo $oldacc['given_name']; ?> <?php echo $oldacc['middle_name']; ?></td>
                            <td><span id="formattedAmount"><?php echo $oldacc['total']; ?></span></td>
                            <td>
                                <a class="button remind" href="payment_arrange.php?id=<?php echo $oldacc['user_id']; ?>">Arrange Payment</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3">No account to manage</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>
</body>
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

    function addCommas(number) {
        return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    }
    var amountElement = document.getElementById("formattedAmount");
    amountElement.innerText = addCommas(amountElement.innerText);
</script>

</html>