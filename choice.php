<?php
require($_SERVER['DOCUMENT_ROOT'] . '/hofin/config.php');
function connect()
{
	$mysqli = new mysqli(SERVER, USERNAME, PASSWORD, DATABASE);
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
function cash_code()
{
    $length = 5;
    $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $max_attempts = 100; // Limit the number of attempts to avoid infinite loops
    $attempt = 0;
    
    do {
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $alphabet[rand(0, strlen($alphabet) - 1)];
        }
        $attempt++;
        if ($attempt >= $max_attempts) {
            throw new Exception('Unable to generate a unique code after multiple attempts');
        }
    } while (code_exists($code));
    
    return $code;
}

function code_exists($code)
{
    $query = "SELECT COUNT(*) FROM tbl_transaction WHERE code = ?";
    $mysqli = connect();
    if (!$mysqli) {
        return false; // Handle connection failure appropriately
    }
    
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('s', $code);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    $mysqli->close();
    
    return $count > 0;
}

if (isset($_POST['verify'])) {
    choice();
}

function choice()
{
    $mysqli = connect();
    if (!$mysqli) {
        return false; // Handle connection failure appropriately
    }

    if (isset($_POST['verify'])) {
        $choice = $_POST['choice'];
        if ($choice == "cash") {
            try {
                $code = cash_code();
                $_SESSION['code'] = $code;
                header("Location: member/payment_cash.php");
            } catch (Exception $e) {
                // Handle the exception appropriately
                echo "Error: " . $e->getMessage();
            }
        } else {
            header("Location: member/gcash.php");
        }
    }
}


?>