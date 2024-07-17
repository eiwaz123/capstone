<?php

// $mysqli = connect();
// if (!$mysqli) {
// 	return false; // Handle connection failure appropriately
// }


// if (isset($_POST['proceed'])) {
//     echo "proceed". "<br>";
//     session_start();
//     echo $user_id = $_SESSION['user_id']. "<br>";
//     echo $code = $_SESSION['code']. "<br>";
//     echo $payment_id = $_SESSION['transac_id']. "<br>";
//  $rawcode=$_POST['code-confirm'];

//     $code_confirm =strtoupper();

//     echo $code_confirm. "<br>";
// 			// if ($code_confirm == $code) {
// 			// 	$codeQuery = "
// 			// 	UPDATE 
//             //             tbl_transaction 
//             //         SET 
//             //             code = %s,
//             //             transc_type = 'Cash',
//             //             is_verified = 'no'
//             //         WHERE 
//             //             transac_id = ?
//             //         AND 
//             //             user_id = ?;
// 			// 	";
// 			// 	$update_stmt = $mysqli->prepare($codeQuery);
// 			// 	if (!$update_stmt) {
// 			// 		echo "Statement preparation failed: " . $mysqli->error;
// 			// 		return false;
// 			// 	}
// 			// 	$update_stmt->bind_param("sii", $code, $payment_id, $user_id);
// 			// 	if ($update_stmt->execute()) {
// 			// 		$mysqli->commit();
// 			// 		header("Location: dashboard.php");
// 			// 		exit();
// 			// 	} else {
// 			// 		// Handle error
// 			// 		echo "SQL Error: " . $update_stmt->error;
// 			// 		include 'error.php'; // include your error handling page
// 			// 		exit();
// 			// 	}
// 			// } else {
// 			// 	$message = 1;
// 			// 	return ['message' => $message];
// 			// 	include 'members/payment_cash.php'; // include your form page with an error message
// 			// 	exit();
// 			// }
// }

session_start();

// Assuming $code is defined somewhere else in your code
$code = "your_code_value"; // Replace with your actual code value

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming $_POST['code_confirm'] is set from your form
    $codes = isset($_POST['code_confirm']) ? $_POST['code_confirm'] : '';
    $code_confirm=strtoupper($codes);

    // Check if $code_confirm matches $code
    if ($code_confirm == $code) {
        // Assuming $mysqli is properly initialized and connected earlier
        $codeQuery = "
            UPDATE tbl_transaction 
            SET 
                code = ?, 
                transc_type = 'Cash', 
                is_verified = 'no' 
            WHERE 
                transac_id = ? 
                AND user_id = ?;
        ";

        $update_stmt = $mysqli->prepare($codeQuery);

        if (!$update_stmt) {
            echo "Statement preparation failed: " . $mysqli->error;
            exit();
        }

        // Assuming $payment_id and $user_id are defined somewhere in your code
        $payment_id =$_SESSION['transac_id']; // Replace with your actual payment_id value
        $user_id = $_SESSION['user_id'];    // Replace with your actual user_id value

        $update_stmt->bind_param("sii", $code, $payment_id, $user_id);

        if ($update_stmt->execute()) {
            $mysqli->commit();
            header("Location: member/home.php");
            exit();
        } else {
            // Handle SQL execution error
            echo "SQL Error: " . $update_stmt->error;
            include 'error.php'; // include your error handling page
            exit();
        }
    } else {
        // Code confirmation failed
        $message = "Code confirmation failed. Please try again.";
        include 'member/payment_cash.php'; // include your form page with an error message
        exit();
    }
}
