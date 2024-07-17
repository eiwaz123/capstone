<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/admin/payment_history.css">

    <?php require($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>
</head>

<body style="padding-top: 100px;">
    <h1>Payment History</h1>



    <div class="half">
        <div class="half-half">
            <h2>Approved Payment:</h2>
        </div>
        <div class="half-half">
            <input type="text" id="searchApproved" class="search-input" onkeyup="filterTable('searchApproved', 'approvedTable')" placeholder="Search for info...">
        </div>
    </div>
    <div class="scrollable">
        <table id="approvedTable" class="table">
            <tr>
                <th class="sort-btn" onclick="sortTable(0, 'approvedTable')">Name</th>
                <th class="sort-btn" onclick="sortTable(2, 'approvedTable')">Transaction Type</th>
                <th class="sort-btn" onclick="sortTable(3, 'approvedTable')">Date Paid</th>
                <th>Amount</th>
                <th>Manage</th>
                <?php
                 if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'USER') {

                 } else {
                     // If not an admin, redirect to the login page or an error page
                     header("Location: ../index.php");
                     exit();
                 }  
                    $data=member_payment_history();
                    $history = $data['member_history'];  
                   
                        
                    ?>
                <?php if ($history) : ?>
                
                    <?php foreach ($history as $histories) : ?>
            <tr>
                <td><?php echo htmlspecialchars($histories['last_name']) . ', ' . htmlspecialchars($histories['middle_name']) . ' ' . htmlspecialchars($histories['last_name']); ?></td>
                <td><?php echo htmlspecialchars($histories['transc_type']); ?></td>
                <td><?php echo htmlspecialchars($histories['date']); ?></td>
                <td><?php echo htmlspecialchars($histories['amount']); ?></td>
                <td>
                    <form action="view_history.php?id=<?php echo htmlspecialchars($histories['transac_id']); ?>" method="POST">
                        <input type="submit" name="view"  class="btn-view" value="View">
                        <a href="generatepdf.php?id=<?php echo htmlspecialchars($histories['transac_id']); ?>" name="generate" class="btn-generate" value="">Generate</a>
                    </form>
                        
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="5">No Result</td>
        </tr>
    <?php endif; ?>
    
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
</script>

</html>