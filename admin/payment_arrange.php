<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/admin/payment_arrange.css">
    <?php require($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>
</head>
<?php
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] === 'ADMIN') {

} else {
    // If not an admin, redirect to the login page or an error page
    header("Location: ../index.php");
    exit();
}
$id = $_GET['id'];
$data= paymentarrangeme($id);
$arrange = $data['row'];

?>

<?php if ($arrange): ?>
    <form action="../functions.php?id=<?php echo $id ?>" method="POST" class="container">
        <h1>Payment Arrangement</h1>
        <h3 class="name">Name: <?php echo $arrange['given_name']; ?> <?php echo $arrange['middle_name']; ?>, <?php echo $arrange['last_name']; ?></h3>
        <span>
            <label for="due">Set deadline this month:</label>
            <input type="date" id="due" name="due" placeholder="Due date" required min="<?php echo date('Y-m-d'); ?>">
        </span>
        <span class="currency-code">
            <label for="amount">Amount:</label>
            ₱ <input type="text" id="amount" name="amount" placeholder="Amount" value="<?php echo $arrange['total']; ?>" required />
        </span>
        <input type="submit" name="arrange" class="button remind" value="Remind">
    </form>
<?php else: ?>
    <script>
        alert("User ID not found");
        window.location.href = "/payment_arrange";
    </script>
<?php endif; ?>

</html>


<script>
    // Function to add thousand commas to a number
    // function addThousandCommas(number) {
    //     return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    // }

    // // Get the input element
    // const amountInput = document.getElementById('amount');

    // // Add event listener for input change
    // amountInput.addEventListener('input', function(event) {
    //     // Remove commas from the input value
    //     let inputValue = event.target.value.replace(/,/g, '');
    //     // Add commas back to the input value
    //     event.target.value = addThousandCommas(inputValue);
    // });

    // // Format the initial value on load
    // amountInput.value = addThousandCommas(amountInput.value);
</script>

</html>