

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/admin/edit_info.css">
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>
    <?php

?>

    <title>Edit User Info</title>
</head>
<?php
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'ADMIN') {

} else {
    // If not an admin, redirect to the login page or an error page
    header("Location: ../index.php");
    exit();
}
$id = $_GET['id'];
$type = $_GET['type'];

if ($type === 'incomplete') {
    $data = getinfouser($id);
    $user = $data['incomplete_members'][0] ?? null;
} else if ($type === 'complete') {
    $data = getinfousercomp($id);
    $user = $data['completed_members'][0] ?? null;
} else {
    $user = null;
}

?>
<body>
    <form method="POST" action="../functions.php?id=<?= htmlspecialchars($user['user_id']) ?>">
        <main>
            <h1>EDIT USER INFO</h1>
            <h3>Personal Information</h3>
            <div class="property-information">
                <?php if ($user) { ?>
                    <label for="given_name">Given Name
                        <input type="text" id="given_name" name="given_name" value="<?= htmlspecialchars($user['given_name']) ?>" required>
                    </label>
                    <label for="middle_name">Middle Name
                        <input type="text" id="middle_name" name="middle_name" value="<?= htmlspecialchars($user['middle_name']) ?>" required>
                    </label>
                    <label for="last_name">Last Name
                        <input type="text" id="last_name" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                    </label>
                </div>
                <div class="personal-information up">
                    <label for="gender">Gender
                        <select class="select" name="gender" id="gender" required>
                            <option value="" selected hidden>Please Select One</option>
                            <option <?= $user['gender'] === "Male" ? "selected" : "" ?>>Male</option>
                            <option <?= $user['gender'] === "Female" ? "selected" : "" ?>>Female</option>
                            <option <?= $user['gender'] === "Other" ? "selected" : "" ?>>Other</option>
                        </select>
                    </label>
                    <label for="birthdate">Birthday
                        <input type="date" id="bday" name="bday" class="bday" value="<?= htmlspecialchars($user['bday']) ?>" required>
                    </label>
                </div>
                <?php } else { ?>
                    <p>No user found.</p>
                <?php } ?>

        <h3>Property Information</h3>
        <div class="property-information">
            <label for="id_no">ID No.
                <input type="number" id="id_no" value="<?= htmlspecialchars($user['id_no']) ?>"  name="id_no" required>
            </label>
            <label for="blk_no">Block No.
                <input type="number" id="blk_no" value="<?= htmlspecialchars($user['blk_no']) ?>" name="blk_no" required>
            </label>
            <label for="lot_no">Lot No.
                <input type="number" id="lot_no" value="<?= htmlspecialchars($user['lot_no']) ?>"  name="lot_no" required>
            </label>
            <label for="homelot_area">Homelot Area
                <input type="number" id="homelot_area" value="<?= htmlspecialchars($user['homelot_area']) ?>"  name="homelot_area" required>
            </label>
            <label for="open_space">Open Space
                <input type="number" id="open_space" value="<?= htmlspecialchars($user['open_space']) ?>" name="open_space" required>
            </label>
            <label for="sharein_loan">Share In Loan
                <input type="text" id="sharein_loan" name="sharein_loan" value="<?= htmlspecialchars($user['sharein_loan']) ?>" required oninput="formatNumber(this); calculateTotal()">
            </label>

            <label for="principal_interest">Principal Interest
                <input type="text" id="principal_interest" value="<?= htmlspecialchars($user['principal_interest']) ?>"   name="principal_interest" required oninput="formatNumber(this); calculateTotal()">
            </label>

            <label for="MRI">MRI
                <input type="text" id="MRI" value="<?= htmlspecialchars($user['MRI']) ?>"  name="MRI" required oninput="formatNumber(this); calculateTotal()">
            </label>

            <label for="total">Total
                <input type="text" id="total" name="total" value="<?= htmlspecialchars($user['total']) ?>" required readonly>
            </label>
        </div>
        <script>
            function formatNumber(input) {
                // Remove non-numeric characters
                const value = input.value.replace(/\D/g, '');
                // Add commas for thousands separator
                // const formattedValue = Number(value).toLocaleString();
                // input.value = formattedValue;
            }

            function calculateTotal() {
                const principal = parseFloat(document.getElementById('principal_interest').value.replace(/\D/g, ''));
                const MRI = parseFloat(document.getElementById('MRI').value.replace(/\D/g, ''));
                const total = principal + MRI;
                if (!isNaN(total)) {
                    document.getElementById('total').value = total.toFixed();
                } else {
                    document.getElementById('total').value = '';
                }
            }
        </script>
        <div class="buttons">
            <input class="update button" type="submit" name="editinfo" value="&#xf044;   Update" onclick="return confirm('Are you sure you want to update this info?')">
            <a href="members_info.php" class="close button"><i class="fa-regular fa-circle-xmark"></i> CLOSE</a>
        </div>
        </main>
    </form>
</body>

</html>