<?php
if (isset($_POST['verified'])) {
    payment_verified();
}

function connect()
{
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "dbhofin";

    $mysqli = new mysqli($server, $username, $password, $database);
    // Error checker
    if ($mysqli->connect_errno != 0) {
        // error retriever
        $error = $mysqli->connect_error;
        // Date of error
        $error_date = date("F j, Y, g:i a");
        // Error message with date
        $message = "{$error} | {$error_date} \r\n";
        // Put the error in db-log.txt
        file_put_contents("db-log.txt", $message, FILE_APPEND);
        return false;
    } else {
        // Connection Successful
        $mysqli->set_charset("utf8mb4");
        return $mysqli;
    }
}


function payment_verified()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['verified'])) {

            $id = $_GET['id'];
            echo $id;
            

            $mysqli = connect();
            if (!$mysqli) {
                return false;
            }
            $sql = "SELECT * FROM tbl_transaction WHERE transac_id = ?";
            $stmt = $mysqli->prepare($sql); // Prepare the SQL statement

            if (!$stmt) {
                // Check if statement preparation was successful
                echo "Failed to prepare statement: " . $mysqli->error;
                return false;
            }

            $stmt->bind_param("i", $id); // Bind the transaction ID as an integer parameter
            $stmt->execute(); // Execute the statement
            $result = $stmt->get_result(); // Get the result set
            $data = $result->fetch_assoc(); // Fetch data as an associative array

            if ($data) {
                // If data is retrieved, extract necessary fields
                $sqlbalance = $data['balance_debt']; // Adjust column name as per your table structure
                $sqlcode = $data['code']; // Adjust column name as per your table structure
                $amount = intval($_POST['amount']); // Get and convert amount from POST data
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $amount = intval($_POST['amount']);

                    if ($amount <= $sqlbalance) {
                        if ($_POST['code'] == $sqlcode) {
                            $update_sql = "
                                UPDATE tbl_transaction
                                SET is_verified = 'yes',
                                    amount = ?,
                                    date = NOW()
                                WHERE transac_id = ?
                            ";
                            $update_stmt = $mysqli->prepare($update_sql);
                            $update_stmt->bind_param("ii", $amount, $id);
                            $update_stmt->execute();
                            $mysqli->commit();
                            header("Location: /admin/payment_verification.php");
                            exit();
                        } else {
                            $_SESSION['error'] = "Code is incorrect, please check your code and try again.";
                            header("Location: /admin/payment_verify.php?");
                            exit();
                        }
                    } else {
                        $_SESSION['error'] = "The amount you entered is greater than the amount of debt, please try again.";
                        header("Location: /admin/payment_verify.php");
                        exit();
                    }
                }
            }
        }
    }
}
