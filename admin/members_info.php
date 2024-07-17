<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Information</title>

    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/admin/members_info.css">
    <?php
    require($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php');
    $data = getinfo();
    if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'ADMIN') {

    } else {
        // If not an admin, redirect to the login page or an error page
        header("Location: ../index.php");
        exit();
    }
  

    ?>

</head>

<body>
    <h1>USER INFO</h1>
    <div class="half">
        <div class="half-half">
            <h2>Unverified Users:</h2>
        </div>
        <div class="half-half">
            <input type="text" id="searchUnverified" class="search-input" onkeyup="filterTable('searchUnverified', 'unverifiedTable')" placeholder="Search for names...">
        </div>
    </div>

    <div class="scrollable">
        <table id="unverifiedTable" class="table">
            <tr>
                <th class="sort-btn" onclick="sortTable(0, 'unverifiedTable')">Last Name</th>
                <th class="sort-btn" onclick="sortTable(1, 'unverifiedTable')">First Name</th>
                <th class="sort-btn" onclick="sortTable(2, 'unverifiedTable')">Middle Name</th>
                <th>Action</th>
                <!-- Add more column headers as per your table structure -->
            </tr>
            <?php if (count($data['unverified_members']) != 0) { ?>
                <?php foreach ($data['unverified_members'] as $unverified) { ?>
                    <tr>
                        <td><?= htmlspecialchars($unverified['last_name']) ?></td>
                        <td><?= htmlspecialchars($unverified['given_name']) ?></td>
                        <td><?= htmlspecialchars($unverified['middle_name']) ?></td>
                        <td>
                            <!-- Button to trigger modal -->
                            <button type="button" class="edit button" data-target="modal-<?= $unverified['user_id'] ?>">
                                <i class="fa fa-eye" aria-hidden="true"></i> View
                            </button>
                        </td>
                    </tr>
                    <!-- Modal -->
                    <div id="modal-<?= $unverified['user_id'] ?>" class="modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <span class="close" data-target="modal-<?= $unverified['user_id'] ?>">&times;</span>
                                    <br>
                                    <div class="containeraaa">
                                        <div class="rowaa">
                                            <?php
                                            $pic = $unverified['face_pic'];
                                            $pic_path = '/hofin/' . $pic . '/0.jpg';
                                            if (!empty($pic) && file_exists($_SERVER['DOCUMENT_ROOT'] . $pic_path)) {
                                            ?>
                                                <img draggable="false" src="<?php echo $pic_path; ?>" alt="Profile pic" class="img-fluid" />
                                            <?php
                                            } else {
                                            ?>
                                                <img draggable="false" src="/hofin/face/noprofile.jpg" alt="Profile pic" class="img-fluid" />
                                            <?php
                                            }
                                            ?>
                                        </div>
                                        <div class="rowaa">
                                            <div class="user-info">
                                                <form action="../functions.php?id=<?= $unverified['user_id'] ?>" method="POST">
                                                    <h2>User Information</h2>
                                                    <p><strong>Last Name:</strong> <?= $unverified['last_name'] ?></p>
                                                    <p><strong>First Name:</strong> <?= $unverified['given_name'] ?></p>
                                                    <p><strong>Middle Name:</strong> <?= $unverified['middle_name'] ?></p>
                                                    <p><strong>Gender:</strong> <?= $unverified['gender'] ?></p>
                                                    <?php
                                                    $date = new DateTime($unverified['bday']);
                                                    $formatted_date = $date->format('F j, Y');
                                                    ?>

                                                    <p><strong>Birthday:</strong> <?= $formatted_date ?></p>
                                                    <p><strong>Email:</strong> <?= $unverified['email'] ?></p>
                                                    <div class="buttonsss">
                                                        <!-- <input type="submit" class="edit button" value="&#10003; Approve"
                                                            name="approvemember">
                                                        <input type="submit" class="delete button" value="&#10007; Decline"
                                                            name="declinemember"> -->

                                                        <button type="submit" class="edit button" name="approvemember">&#10003; Approve</button>
                                                        <button type="submit" class="delete button" name="declinemember">&#10007; Decline</button>


                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Get all the buttons that open a modal
                            var buttons = document.querySelectorAll('.edit.button');
                            buttons.forEach(function(button) {
                                button.addEventListener('click', function() {
                                    var target = button.getAttribute('data-target');
                                    var modal = document.getElementById(target);
                                    if (modal) {
                                        modal.style.display = 'block';
                                    }
                                });
                            });

                            // Get all the elements that close a modal
                            var closeButtons = document.querySelectorAll('.close');
                            closeButtons.forEach(function(button) {
                                button.addEventListener('click', function() {
                                    var target = button.getAttribute('data-target');
                                    var modal = document.getElementById(target);
                                    if (modal) {
                                        modal.style.display = 'none';
                                    }
                                });
                            });


                            // Close the modal if the user clicks anywhere outside of the modal content
                            window.addEventListener('click', function(event) {
                                if (event.target.classList.contains('modal')) {
                                    event.target.style.display = 'none';
                                }
                            });
                        });

                        document.addEventListener('keydown', function(event) {
                            if (event.key === 'Escape') {
                                var modals = document.querySelectorAll('.modal');
                                modals.forEach(function(modal) {
                                    modal.style.display = 'none';
                                });
                            }
                        });
                    </script>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6">No unverified users found.</td>
                </tr>
            <?php } ?>
        </table>
    </div>



    <div class="half">
        <div class="half-half">
            <h2>Incomplete User Information:</h2>
        </div>
        <div class="half-half">
            <input type="text" id="searchIncomplete" class="search-input" onkeyup="filterTable('searchIncomplete', 'incompleteTable')" placeholder="Search for names...">
        </div>
    </div>
    <div class="scrollable">
        <table id="incompleteTable" class="table">
            <tr>
                <th class="sort-btn" onclick="sortTable(0, 'incompleteTable')">Last Name</th>
                <th class="sort-btn" onclick="sortTable(1, 'incompleteTable')">First Name</th>
                <th class="sort-btn" onclick="sortTable(2, 'incompleteTable')">Middle Name</th>
                <th>Action</th>
                <!-- Add more column headers as per your table structure -->
            </tr>
            <?php if (count($data['incomplete_members']) != 0) { ?>
                <?php foreach ($data['incomplete_members'] as $incomplete) { ?>
                    <tr>
                        <td><?= htmlspecialchars($incomplete['last_name']) ?></td>
                        <td><?= htmlspecialchars($incomplete['given_name']) ?></td>
                        <td><?= htmlspecialchars($incomplete['middle_name']) ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="submit" class="edit button" name="edit" formaction="edit_info.php?id=<?= htmlspecialchars($incomplete['user_id']) ?>&type=incomplete" value="&#xf044; EDIT">
                                <input type="submit" class="delete button" value="&#xf2ed; DELETE" name="deleted" formaction="../functions.php?id=<?= htmlspecialchars($incomplete['user_id']) ?>&type=incomplete" onclick="return confirm('Are you sure you want to delete this account?')">
                            </form>
                        </td>
                        <td hidden>Search not found.</td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6">No unverified users found.</td>
                </tr>
            <?php } ?>
        </table>
    </div>


    <div class="half">
        <div class="half-half">
            <h2>Complete User Information:</h2>
        </div>
        <div class="half-half">
            <input type="text" id="searchComplete" class="search-input" onkeyup="filterTable('searchComplete', 'completeTable')" placeholder="Search for names...">
        </div>
    </div>

    <div class="scrollable">
        <table id="completeTable" class="table">
            <tr>
                <th class="sort-btn" onclick="sortTable(0, 'completeTable')">Last Name</th>
                <th class="sort-btn" onclick="sortTable(1, 'completeTable')">First Name</th>
                <th class="sort-btn" onclick="sortTable(2, 'completeTable')">Middle Name</th>
                <th>Action</th>
            </tr>
            <?php if (count($data['completed_members']) != 0) { ?>
                <?php foreach ($data['completed_members'] as $complete) { ?>
                    <tr>
                        <td><?= htmlspecialchars($complete['last_name']) ?></td>
                        <td><?= htmlspecialchars($complete['given_name']) ?></td>
                        <td><?= htmlspecialchars($complete['middle_name']) ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="submit" class="edit button" name="edit" formaction="edit_info.php?id=<?= htmlspecialchars($complete['user_id']) ?>&type=complete" value="&#xf044; EDIT">
                                <input type="submit" class="delete button" name="deleted" value="&#xf2ed; DELETE" formaction="../functions.php?id=<?= htmlspecialchars($complete['user_id']) ?>" onclick="return confirm('Are you sure you want to delete this account?')">
                            </form>
                        </td>
                        <td hidden>Search not found.</td>
                    </tr>
                <?php }
            } else { ?>
                <tr>
                    <td colspan="6">No unverified users found.</td>
                </tr>
            <?php } ?>
    </div>

</body>

<script>
    function filterTable(inputId, tableId) {
        var input, filter, table, tr, td, i, j, txtValue, rowVisible, noSearchTd;
        input = document.getElementById(inputId);
        filter = input.value.toUpperCase();
        table = document.getElementById(tableId);
        tr = table.getElementsByTagName("tr");
        rowVisible = false;

        for (i = 1; i < tr.length; i++) { // Start from index 1 to skip the header row
            td = tr[i].getElementsByTagName("td");
            tr[i].style.display = "none";
            noSearchTd = tr[i].getElementsByClassName("no-search")[0];

            if (noSearchTd) {
                noSearchTd.hidden = true;
            }

            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        rowVisible = true;
                        break;
                    }
                }
            }
        }

        // Show the 'Search not found' message if no row is visible
        if (!rowVisible) {
            var newRow = table.insertRow(-1);
            var newCell = newRow.insertCell(0);
            newCell.colSpan = tr[0].cells.length;
            newCell.className = "no-search";
            newCell.textContent = "Search not found.";
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