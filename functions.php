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
function getpic($id)
{
	$mysqli = connect();
	if (!$mysqli) {
		return false;
	}
	// Prepare and execute the SQL statement
	$stmt = $mysqli->prepare("SELECT face_pic FROM tbl_face WHERE user_id = ?");
	$stmt->bind_param('i', $id);
	$stmt->execute();
	// Get the result
	$result = $stmt->get_result();
	if ($result && $result->num_rows > 0) {
		$row = $result->fetch_array();
		return $row['face_pic'];
	} else {
		return false;
	}
}
function userchecker($id)
{
	$mysqli = connect();
	if (!$mysqli) {
		return false;
	}
	// Prepare and execute the SQL statement
	$stmt1 = $mysqli->prepare("SELECT is_admin, is_deleted, is_verified FROM tbl_useracc WHERE user_id = ?");
	$stmt1->bind_param('i', $id);
	$stmt1->execute();
	// Get the result
	$result1 = $stmt1->get_result();
	// Check if there are results and fetch the data
	if ($result1 && $result1->num_rows > 0) {
		$row1 = $result1->fetch_array();
		// Prepare and execute the SQL statement
		$stmt2 = $mysqli->prepare("SELECT given_name, last_name	FROM tbl_userinfo WHERE user_id = ?");
		$stmt2->bind_param('i', $id);
		$stmt2->execute();
		// Get the result
		$result2 = $stmt2->get_result();
		$row2 = $result2->fetch_array();
		$is_admin = $row1['is_admin'];
		$is_deleted = $row1['is_deleted'];
		$is_verified = $row1['is_verified'];
		$name = $row2['given_name'] . ' ' . $row2['last_name'];
		if (isset($is_deleted) && $is_deleted == "no") {
			if (isset($is_verified) && $is_verified == "yes") {
				if (isset($is_admin) && $is_admin == "yes") {
					$_SESSION['user_id'] = $id;
					$_SESSION['is_admin'] = 'yes';
					$_SESSION['usertype'] = 'ADMIN';
					$_SESSION['fullname'] = $name;
					echo "  <script>
						// Simulate loading delay
						setTimeout(function() {
						// Redirect to another page after 3 seconds
						window.location.href = './admin/dashboard.php';
						}, 1500); // 2000 milliseconds = 3 seconds
					</script>";
					return 'success';
				} else {
					$_SESSION['user_id'] = $id;
					$_SESSION['is_admin'] = 'no';
					$_SESSION['usertype'] = 'USER';
					$_SESSION['fullname'] = $name;
					echo "  <script>
						// Simulate loading delay
						setTimeout(function() {
						// Redirect to another page after 3 seconds
						window.location.href = './member/home.php';
						}, 1500); // 2000 milliseconds = 3 seconds
					</script>";
					return 'success';
				}
			} else {
				return 'Account is not yet verified, please wait for the admin to verify your account.';
			}
		} else {
			return 'Account was deleted, if you think this is a mistake please contact an admin.';
		}
	}
}
function create_user()
{
	$mysqli = connect();
	if (!$mysqli) {
		return false;
	}
	// Get the last user ID
	$result = mysqli_query($mysqli, "SELECT user_id FROM tbl_useracc ORDER BY user_id DESC LIMIT 1");
	// Check if the query returned any rows
	if ($result && mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		$lastId = $row['user_id'];
	} else {
		// If no rows returned, set lastId to 0
		$lastId = 0;
	}
	// Generate the username
	$username = date('Y') . sprintf('%04d', $lastId + 1);
	// Close the database connection
	mysqli_close($mysqli);
	return $username;
}
function register($given_name, $middle_name, $last_name, $gender, $bday, $username, $password, $confirm, $email, $image_data)
{
	// Establish a database connection.
	$mysqli = connect();
	// If there's an error in database the program will stop function
	if (!$mysqli) {
		return false;
	}
	// Check if the passwords match
	if ($password !== $confirm) {
		return "Passwords do not match.";
	}
	// Check if the password is too long.
	if (strlen($password) < 8) {
		// If password is too long, return an error message.
		return "Password is too short, must be 8-24 characters";
	}
	if (strlen($password) > 24) {
		// If password is too long, return an error message.
		return "Password is too long, must be 8-24 characters";
	}
	if (isset($given_name) && isset($middle_name) && isset($last_name) && isset($gender) && isset($bday) && isset($username) 
	&& isset($password) && isset($confirm) && isset($email) && isset($image_data)) {
		// Check if the fields are not empty
		if (!empty($given_name) && !empty($middle_name) && !empty($last_name) && !empty($gender) 
		&& !empty($bday) && !empty($username) && !empty($password) && !empty($image_data)) {
			// Function to create a folder
			$folderName = $given_name . ' ' . $middle_name . ' ' . $last_name;
			function createFolder($folderName)
			{
				// Specify the directory where the folder will be created
				$directory = "face/labels/";
				// Check if the folder already exists
				if (!is_dir($directory . $folderName)) {
					// Create the folder
					if (mkdir($directory . $folderName, 0777, true)) {
						return true;
					} else {
						return false;
					}
				} else {
					return true;
				}
			}
			// Create folder if it doesn't exist
			if (!createFolder($folderName)) {
				echo '<div class="alert alert-danger" role="alert">Failed to create folder.</div>';
				exit;
			}
			// Save the images
			foreach ($image_data as $index => $imageData) {
				$imagePath = "face/labels/$folderName/" . ($index) . ".jpg";
				$folderpath = "face/labels/$folderName/";
				if (!file_put_contents($imagePath, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData)))) {
					echo '<div class="alert alert-danger" role="alert">Failed to save image $index.</div>';
					exit;
				}
			}
			// Prepare data to save to labels.json
			$dataToSave = array(
				'name' => $given_name . ' ' . $middle_name . ' ' . $last_name,
				'gender' => $gender,
				'bday' => $bday,
				'username' => $username,
				'password' => $password,
			);
			// Read existing data from labels.json
			$labelsFilePath = './face/labels.json';
			$existingData = array();
			if (file_exists($labelsFilePath)) {
				$encryptedDataWithIV = file_get_contents($labelsFilePath);
				if ($encryptedDataWithIV !== false) {
					$iv_hex = substr($encryptedDataWithIV, 0, 32); // Extract IV from the beginning
					$encryptedData = substr($encryptedDataWithIV, 32); // Extract encrypted data without IV
					$decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', 'Adm1n123', 0, hex2bin($iv_hex));
					if ($decryptedData !== false) {
						$existingData = json_decode($decryptedData, true);
					} else {
						return 'Failed to decrypt data from labels.json.';
					}
				} else {
					return 'Failed to read data from labels.json.';
				}
			}
			// Check if the name already exists
			$nameExists = false;
			foreach ($existingData as $data) {
				if (
					$data['name'] == $dataToSave['name'] &&
					$data['gender'] == $dataToSave['gender'] &&
					$data['bday'] == $dataToSave['bday']
				) {
					$nameExists = true;
					break;
				}
			}
			if ($nameExists) {
				return 'Name already exists in the database.';
			}
			// Append new data to existing data
			$existingData[] = $dataToSave;
			// Encrypt and write updated data back to labels.json
			$iv = openssl_random_pseudo_bytes(16); // Generate a random IV of 16 bytes (128 bits)
			$iv_hex = bin2hex($iv); // Convert the binary IV to hexadecimal representation
			$encryptedData = openssl_encrypt(json_encode($existingData), 'aes-256-cbc', 'Adm1n123', 0, $iv);
			$encryptedDataWithIV = $iv_hex . $encryptedData; // Combine IV and encrypted data
			if (file_put_contents($labelsFilePath, $encryptedDataWithIV)) {
				echo '<script>console.log("Data saved successfully.");</script>';
				$hashed_password = password_hash($password, PASSWORD_DEFAULT);
				// Prepare the SQL query
				$is_admin = "no";
				$is_deleted = "no";
				$is_verified = "no";
				$stmt1 = $mysqli->prepare("INSERT INTO tbl_useracc (username, password, email, is_admin, is_deleted, is_verified) VALUES (?, ?, ?, ?, ?, ?)");
				$stmt1->bind_param("ssssss", $username, $hashed_password, $email, $is_admin, $is_deleted, $is_verified);
				// Execute the query
				if ($stmt1->execute()) {
					$user_id = $mysqli->insert_id;
					$stmt2 = $mysqli->prepare("INSERT INTO tbl_userinfo (user_id, given_name, middle_name, last_name, gender, bday) VALUES (?, ?, ?, ?, ?, ?)");
					$stmt2->bind_param("isssss", $user_id, $given_name, $middle_name, $last_name, $gender, $bday);
					if ($stmt2->execute()) {
						$stmt3 = $mysqli->prepare("INSERT INTO tbl_face (user_id, face_pic) VALUES (?, ?)");
						$stmt3->bind_param("is", $user_id, $folderpath);
						if ($stmt3->execute()) {
							// Close the statement and connection
							$stmt1->close();
							$stmt2->close();
							$stmt3->close();
							echo "  <script>
								setTimeout(function() {
								window.location.href = './index.php';
								}, 3000); // 3000 milliseconds = 3 seconds
							</script>";
							return "success";
						} else {	// If there's an error, log it
							$error = $stmt1->error;
							$error_date = date("F j, Y, g:i a");
							$message = "{$error} | {$error_date} \r\n";
							file_put_contents("db-log.txt", $message, FILE_APPEND);
							// Close the statement and connection
							$stmt1->close();
							$mysqli->close();
							return "Registration failed.";
						}
					} else {	// If there's an error, log it
						$error = $stmt1->error;
						$error_date = date("F j, Y, g:i a");
						$message = "{$error} | {$error_date} \r\n";
						file_put_contents("db-log.txt", $message, FILE_APPEND);
						// Close the statement and connection
						$stmt1->close();
						$mysqli->close();
						return "Registration failed.";
					}
				} else {
					// If there's an error, log it
					$error = $stmt1->error;
					$error_date = date("F j, Y, g:i a");
					$message = "{$error} | {$error_date} \r\n";
					file_put_contents("db-log.txt", $message, FILE_APPEND);
					// Close the statement and connection
					$stmt1->close();
					$mysqli->close();
					return "Registration failed.";
				}
			} else {
				echo '<script>console.log("Failed to save data to labels.json.");</script>';
			}
		} else {
			// Return error message if required fields are empty
			return 'There are missing fields that are required';
		}
	} else {
		// Return error message if required keys are not set
		return 'All fields are required.';
	}
	// Hash the password
}
function login($username, $password)
{
	$mysqli = connect();
	if (!$mysqli) {
		return "Database connection error";
	}
	$username = trim($username);
	$password = trim($password);
	if ($username == "" || $password == "") {
		return "Both fields are required";
	}
	$username = filter_var($username, FILTER_SANITIZE_STRING);
	$password = filter_var($password, FILTER_SANITIZE_STRING);
	$sql = "SELECT username, password, user_id FROM tbl_useracc WHERE username = ?";
	$stmt = $mysqli->prepare($sql);
	if (!$stmt) {
		return "Database error: " . $mysqli->error;
	}
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$result = $stmt->get_result();
	$data = $result->fetch_assoc();
	$max_attempts = 2;
	$lockout_time = 5; // 5 minutes in seconds


	if (isset($_SESSION['last_attempt']) && $_SESSION['last_attempt'] !== null) {
		$remaining_time = $lockout_time - (time() - $_SESSION['last_attempt']);
		if ($remaining_time > 0) {
			return "Account is locked. Please try again in $remaining_time seconds.";
		} else {
			$_SESSION['last_attempt'] = null; // Reset lockout
		}
	}



	if (!isset($_SESSION['login_attempts'])) {
		$_SESSION['login_attempts'] = 1;
	} else {
		$_SESSION['login_attempts']++;
	}

	if ($_SESSION['login_attempts'] > $max_attempts) {
		if (!isset($_SESSION['last_attempt'])) {
			$_SESSION['last_attempt'] = time();
		}
	}

	if (
		$_SESSION['login_attempts'] >= 3 &&
		(time() - $_SESSION['last_attempt']) > $lockout_time
	) {
		$_SESSION['login_attempts'] = 1;
		$_SESSION['last_attempt'] = null;
	}

	if (
		$_SESSION['login_attempts'] > $max_attempts &&
		(time() - $_SESSION['last_attempt']) < $lockout_time
	) {
		// Account is locked
		$_SESSION['login_attempts'] = 3;
		$remaining_time = $lockout_time - (time() - $_SESSION['last_attempt']);
		return "Account is locked. Please try again in <span id='remainingTime'>$remaining_time</span> seconds.";
	}
	if ($data == NULL || password_verify($password, $data["password"]) == false) {
		if ($_SESSION['login_attempts'] >= 3) {
			$_SESSION['last_attempt'] = time();
		}
		return "Wrong username or password";
	} else {
		unset($_SESSION['login_attempts']);
		unset($_SESSION['last_attempt']);
		$id = $data["user_id"];
		return userchecker($id);
	}
}




function update_password($given_name, $middle_name, $last_name, $gender, $bday, $username, $password)
{
	if (isset($given_name) && isset($middle_name) && isset($last_name) && isset($gender) && isset($bday) && isset($username) && isset($password)) {
		// Prepare data to save to labels.json
		$dataToSave = array(
			'name' => $given_name . ' ' . $middle_name . ' ' . $last_name,
			'username' => $username,
			'password' => $password,
			'gender' => $gender,
			'bday' => $bday,
		);
		// Read existing data from labels.json
		$labelsFilePath = './face/labels.json';
		$existingData = array();
		if (file_exists($labelsFilePath)) {
			$encryptedDataWithIV = file_get_contents($labelsFilePath);
			if ($encryptedDataWithIV !== false) {
				$iv_hex = substr($encryptedDataWithIV, 0, 32); // Extract IV from the beginning
				$encryptedData = substr($encryptedDataWithIV, 32); // Extract encrypted data without IV
				$decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', 'Adm1n123', 0, hex2bin($iv_hex));
				if ($decryptedData !== false) {
					$existingData = json_decode($decryptedData, true);
				} else {
					return 'Failed to decrypt data from labels.json.';
				}
			} else {
				return 'Failed to read data from labels.json.';
			}
		}
		// Check if the username already exists and update the data if it does
		$userExists = false;
		foreach ($existingData as &$data) {
			if (
				$data['name'] == $dataToSave['name'] &&
				$data['gender'] == $dataToSave['gender'] &&
				$data['bday'] == $dataToSave['bday']
			) {
				$data = $dataToSave; // Update existing user data
				$userExists = true;
				break;
			}
		}
		// If the user does not exist, append new data
		if (!$userExists) {
			$existingData[] = $dataToSave;
		}
		// Encrypt and write updated data back to labels.json
		$iv = openssl_random_pseudo_bytes(16); // Generate a random IV of 16 bytes (128 bits)
		$iv_hex = bin2hex($iv); // Convert the binary IV to hexadecimal representation
		$encryptedData = openssl_encrypt(json_encode($existingData), 'aes-256-cbc', 'Adm1n123', 0, $iv);
		$encryptedDataWithIV = $iv_hex . $encryptedData; // Combine IV and encrypted data
		if (file_put_contents($labelsFilePath, $encryptedDataWithIV)) {
			return "success";
		} else {
			return "Failed to save data to labels.json.";
		}
	} else {
		return 'All fields are required.';
	}
}



//+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++   ADMIN   ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

function edituser()
{
	if (isset($_POST['approvemember'])) {
		$mysqli = connect();
		$stmt = $mysqli->prepare("UPDATE tbl_useracc SET is_verified = 'yes' WHERE user_id = ?");
		$stmt->bind_param('i', $_GET['id']);
		$stmt->execute();
		$stmt2 = $mysqli->prepare("INSERT INTO tbl_property (user_id) VALUES (?)");
		$stmt2->bind_param('i', $_GET['id']);
		$stmt2->execute();
		header('Location: admin/members_info.php');
		exit;
	}

	if (isset($_POST['declinemember'])) {
		$mysqli = connect();
		$stmt = $mysqli->prepare("DELETE FROM tbl_useracc WHERE user_id = ?");
		$stmt->bind_param('i', $_GET['id']);
		$stmt->execute();
		header('Location: admin/members_info.php');
		exit;
	}
}
if (isset($_POST['editinfo'])) {
	edituserinfo();
}
function edituserinfo()
{
	if (isset($_POST['editinfo'])) {

		$mysqli = connect();
		$stmt = $mysqli->prepare("UPDATE tbl_userinfo SET given_name = ?, middle_name = ?, last_name = ?, gender = ?, bday = ? WHERE user_id = ?");
		$stmt->bind_param('sssssi', $_POST['given_name'], $_POST['middle_name'], $_POST['last_name'], $_POST['gender'], $_POST['bday'], $_GET['id']);
		$stmt->execute();
		$stmt2 = $mysqli->prepare("UPDATE tbl_property SET id_no = ?, blk_no = ?, lot_no = ?, homelot_area = ?, open_space = ?,sharein_loan = ?,principal_interest = ?,MRI=?,total = ? WHERE user_id = ?");
		$stmt2->bind_param('iiiiiiiiii', $_POST['id_no'], $_POST['blk_no'], $_POST['lot_no'], $_POST['homelot_area'], $_POST['open_space'], $_POST['sharein_loan'], $_POST['principal_interest'], $_POST['MRI'], $_POST['total'], $_GET['id']);
		$stmt2->execute();
		$mysqli->close();
		header('Location: admin/members_info.php');
		exit;
	}
}
function getinfouser($id)
{


	$mysqli = connect();
	if (!$mysqli) {
		return false;
	}
	$incQuery = '
        SELECT *
        FROM tbl_property
        JOIN tbl_userinfo ON tbl_property.user_id = tbl_userinfo.user_id
        JOIN tbl_useracc ON tbl_property.user_id = tbl_useracc.user_id
        WHERE is_admin = "no" 
            AND is_deleted = "no"
            AND is_verified = "yes"
            AND (blk_no IS NULL 
            OR lot_no IS NULL 
            OR homelot_area IS NULL 
            OR open_space IS NULL 
            OR sharein_loan IS NULL 
            OR principal_interest IS NULL 
            OR MRI IS NULL 
            OR total IS NULL)
			AND tbl_property.user_id = ?
    ';

	$stmt = $mysqli->prepare($incQuery);
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$incResult = $stmt->get_result();
	$inc = $incResult->fetch_all(MYSQLI_ASSOC);


	return [

		'incomplete_members' => $inc,


	];
}

function getinfousercomp($id)
{
	$mysqli = connect();
	if (!$mysqli) {
		return false;
	}

	$compQuery = '
        SELECT *
        FROM tbl_property
        JOIN tbl_userinfo ON tbl_property.user_id = tbl_userinfo.user_id
        JOIN tbl_useracc ON tbl_property.user_id = tbl_useracc.user_id
        WHERE is_admin = "no" 
            AND is_deleted = "no"
            AND is_verified = "yes"
            AND (blk_no IS NOT NULL 
            OR lot_no IS NOT NULL 
            OR homelot_area IS NOT NULL 
            OR open_space IS NOT NULL 
            OR sharein_loan IS NOT NULL 
            OR principal_interest IS NOT NULL 
            OR MRI IS NOT NULL 
            OR total IS NOT NULL)
            AND tbl_property.user_id = ?
    ';

	$stmt = $mysqli->prepare($compQuery);
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$compResult = $stmt->get_result();
	$comp = $compResult->fetch_all(MYSQLI_ASSOC);

	return [
		'completed_members' => $comp
	];
}



function getinfo()
{

	// Establish a database connection.
	$mysqli = connect();
	// If there's an error in the database connection, the function will stop.
	if (!$mysqli) {
		return false;
	}

	// Fetch unverified members
	$unvQuery = "
        SELECT *
        FROM tbl_useracc, tbl_userinfo, tbl_face
        WHERE tbl_useracc.user_id = tbl_userinfo.user_id
			AND tbl_useracc.user_id = tbl_face.user_id
            AND tbl_useracc.is_verified = 'no'
            AND tbl_useracc.is_admin = 'no'
            AND tbl_useracc.is_deleted = 'no'
        ORDER BY tbl_userinfo.last_name ASC;
    ";
	$unvResult = $mysqli->query($unvQuery);
	$unv = $unvResult->fetch_all(MYSQLI_ASSOC);

	//Fetch approve member
	$aprvQuery = "
	SELECT *
	FROM tbl_useracc
	WHERE tbl_useracc.is_verified = 'yes'
		AND tbl_useracc.is_admin = 'no'
		AND tbl_useracc.is_deleted = 'no'
";
	$aprvResult = $mysqli->query($aprvQuery);
	$aprv = $aprvResult->fetch_all(MYSQLI_ASSOC);




	// Fetch incomplete property info
	$incQuery = "
        SELECT *
        FROM tbl_property
        JOIN tbl_userinfo ON tbl_property.user_id = tbl_userinfo.user_id
        JOIN tbl_useracc ON tbl_property.user_id = tbl_useracc.user_id
        WHERE is_admin = 'no' 
            AND is_deleted = 'no'
            AND is_verified = 'yes'
            AND (blk_no IS NULL 
            OR lot_no IS NULL 
            OR homelot_area IS NULL 
            OR open_space IS NULL 
            OR sharein_loan IS NULL 
            OR principal_interest IS NULL 
            OR MRI IS NULL 
            OR total IS NULL)
    ";
	$incResult = $mysqli->query($incQuery);
	$inc = $incResult->fetch_all(MYSQLI_ASSOC);


	// Fetch complete property info
	$compQuery = "
        SELECT *
        FROM tbl_property
        JOIN tbl_userinfo ON tbl_property.user_id = tbl_userinfo.user_id
        JOIN tbl_useracc ON tbl_property.user_id = tbl_useracc.user_id
        WHERE is_admin = 'no' 
            AND is_deleted = 'no'
            AND is_verified = 'yes'
            AND (blk_no IS NOT NULL 
            OR lot_no IS NOT NULL 
            OR homelot_area IS NOT NULL 
            OR open_space IS NOT NULL 
            OR sharein_loan IS NOT NULL 
            OR principal_interest IS NOT NULL 
            OR MRI IS NOT NULL 
            OR total IS NOT NULL)
    ";
	$compResult = $mysqli->query($compQuery);
	$comp = $compResult->fetch_all(MYSQLI_ASSOC);

	//Fetch deleted
	$delQuery = "
	SELECT *
	FROM tbl_useracc
	WHERE 
		tbl_useracc.is_deleted = 'yes'
";
	$delResult = $mysqli->query($delQuery);
	$delete = $delResult->fetch_all(MYSQLI_ASSOC);

	$paymentapprovalQuery = "
	 SELECT
            *
        FROM
            tbl_transaction, tbl_userinfo
        WHERE
            tbl_transaction.transc_type IN ('gcash', 'cash')
            AND tbl_transaction.is_verified = 'no'
            AND (tbl_transaction.code IS NOT NULL OR tbl_transaction.proof IS NOT NULL)
            
            AND tbl_userinfo.user_id = tbl_transaction.user_id
	";
	$paymentapprovalResult = $mysqli->query($paymentapprovalQuery);
	$paymentapproval = $paymentapprovalResult->fetch_all(MYSQLI_ASSOC);


	$historyQuery = " SELECT tbl_transaction.*, tbl_userinfo.*, tbl_useracc.*
            FROM tbl_transaction
            JOIN tbl_userinfo ON tbl_transaction.user_id = tbl_userinfo.user_id
            JOIN tbl_useracc ON tbl_transaction.user_id = tbl_useracc.user_id
            WHERE tbl_transaction.transc_type != 'arrangement'
            AND tbl_transaction.is_verified = 'yes'
            ORDER BY tbl_transaction.date;";
	$historyResult = $mysqli->query($historyQuery);
	$history = $historyResult->fetch_all(MYSQLI_ASSOC);

	$notyetpaidQuery = "
		SELECT *
				FROM
					tbl_transaction
				WHERE
					amount IS NULL AND DATE IS NULL AND is_verified = 'yes' AND transc_type = 'arrangement' 
				ORDER BY 
					due_date DESC;
		
	";
	$notyetpaidResult = $mysqli->query($notyetpaidQuery);
	$notyetpaid = $notyetpaidResult->fetch_all(MYSQLI_ASSOC);

	$moneycollectedQuery = "
	SELECT SUM(amount) AS amount
		FROM tbl_transaction;
	
	";
	$money_colected_result = $mysqli->query($moneycollectedQuery);
	$money_collected = $money_colected_result->fetch_all(MYSQLI_ASSOC);




	return [
		'approve_members' => $aprv,
		'unverified_members' => $unv,
		'incomplete_members' => $inc,
		'completed_members' => $comp,
		'deleted_members' => $delete,
		'payment_approval' => $paymentapproval,
		'history' => $history,
		'notyet_paid' => $notyetpaid,
		'money_collected' => $money_collected
	];
}
function paymentarrangement()
{
	$mysqli = connect();
	// If there's an error in the database connection, the function will stop.
	if (!$mysqli) {
		return false;
	}
	$newinfoQuery = "
	SELECT
		tbl_property.*,
		tbl_userinfo.*
	FROM
		tbl_property
	LEFT JOIN tbl_userinfo ON tbl_property.user_id = tbl_userinfo.user_id
	LEFT JOIN tbl_useracc ON tbl_property.user_id = tbl_useracc.user_id
	WHERE
		tbl_userinfo.user_id NOT IN(
		SELECT
			user_id
		FROM
			tbl_transaction
	) AND tbl_property.total IS NOT NULL AND tbl_useracc.is_admin = 'no' AND tbl_useracc.is_deleted = 'no' AND tbl_useracc.is_verified = 'yes'";
	$newinforesult = $mysqli->query($newinfoQuery);
	$new = $newinforesult->fetch_all(MYSQLI_ASSOC);
	$oldinfoQuery = " SELECT
            prop.*,
            userinfo.*
        FROM
            tbl_property prop
        LEFT JOIN tbl_userinfo userinfo ON prop.user_id = userinfo.user_id
        LEFT JOIN tbl_useracc useracc ON prop.user_id = useracc.user_id
        WHERE
            userinfo.user_id IN (
                SELECT
                    user_id
                FROM
                    tbl_transaction
                WHERE
                    MONTH(due_date) != MONTH(CURRENT_DATE())
            )
            AND prop.total IS NOT NULL
            AND useracc.is_admin = 'no'
            AND useracc.is_deleted = 'no'
            AND useracc.is_verified = 'yes'
            AND userinfo.user_id NOT IN (
                SELECT
                    user_id
                FROM
                    tbl_transaction
                WHERE
                    transc_type = 'arrangement'
            );";
	$oldinforesult = $mysqli->query($oldinfoQuery);
	$old = $oldinforesult->fetch_all(MYSQLI_ASSOC);
	return [
		'new' => $new,
		'old' => $old
	];
}
function paymentarrangeme($id)
{
	$mysqli = connect();
	// If there's an error in the database connection, the function will stop.
	if (!$mysqli) {
		return false;
	}
	$infoQuery = "
	  SELECT tbl_property.total, tbl_userinfo.*
        FROM tbl_property
        JOIN tbl_userinfo ON tbl_userinfo.user_id = tbl_property.user_id
        WHERE tbl_userinfo.user_id = ?
        LIMIT 1;
	";
	$stmt = $mysqli->prepare($infoQuery);
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_array(MYSQLI_ASSOC);

	return [
		'row' => $row,

	];
}
if (isset($_POST['arrange'])) {
	payment_arranged();
}
function payment_arranged()
{
	$mysqli = connect();
	// If there's an error in the database connection, the function will stop.
	if (!$mysqli) {
		return false;
	}
	$amount = $_POST['amount'];
	$due_date_str = $_POST['due'];

	// Validate the due date format
	$due_date = DateTime::createFromFormat('Y-m-d', $due_date_str);
	if (!$due_date) {
		echo "Error: Invalid date format. Please enter the date in YYYY-MM-DD format.";
		exit();
	}

	// Convert the due date to the required format
	$formatted_due_date = $due_date->format('Y-m-d');

	// Prepare the SQL query to insert the data into the tbl_transaction table
	$sql = "INSERT INTO `tbl_transaction` (`user_id`, `balance_debt`, `transc_type`, `due_date`, `is_verified`) 
            VALUES (?, ?, 'arrangement', ?, 'yes')";

	// Assuming $mysqli is your database connection
	$stmt = $mysqli->prepare($sql);
	if ($stmt === false) {
		echo "Error: Unable to prepare the SQL statement.";
		exit();
	}
	$user_id = $_GET['id'];
	// Bind the parameters and execute the query
	$stmt->bind_param('ids', $user_id, $amount, $formatted_due_date);

	if ($stmt->execute()) {
		// Redirect to the admin payment arrangement page upon successful execution
		header("Location: admin/payment_arrangement.php");
		exit();
	} else {
		// Display an error message if the query execution fails
		echo "Error: " . $stmt->error;
	}

	// Close the statement
	$stmt->close();
}

function payment_verification()
{
	$mysqli = connect();
	// If there's an error in the database connection, the function will stop.
	if (!$mysqli) {
		return false;
	}
	$verifyQuery = "
	  SELECT
            *
        FROM
            tbl_transaction, tbl_userinfo
        WHERE
            tbl_transaction.transc_type IN ('gcash', 'cash')
            AND tbl_transaction.is_verified = 'no'
            AND (tbl_transaction.code IS NOT NULL OR tbl_transaction.proof IS NOT NULL)
            
            AND tbl_userinfo.user_id = tbl_transaction.user_id
	";
	$verifyResult = $mysqli->query($verifyQuery);
	$unverified = $verifyResult->fetch_all(MYSQLI_ASSOC);


	return [
		'unverified' => $unverified,
	];
}
function payment_verify($transac_id)
{
	$mysqli = connect();
	if (!$mysqli) {
		return false;
	}
	$to_verifyQuery = " SELECT
                *
            FROM
                tbl_transaction, tbl_userinfo
            WHERE
                tbl_transaction.transac_id = ?
            AND 
                tbl_userinfo.user_id = tbl_transaction.user_id ";
	// $stmt = $mysqli->prepare($to_verifyQuery);
	// $stmt->bind_param('i', $transac_id);
	// $stmt->execute();
	// $result = $stmt->get_result();

	// $data = $result->fetch_array(MYSQLI_ASSOC);


	// return['data'=>$data];
	if ($stmt = $mysqli->prepare($to_verifyQuery)) {
		$stmt->bind_param('i', $transac_id);
		$stmt->execute();
		$result = $stmt->get_result();

		if ($result) {
			$data = $result->fetch_array(MYSQLI_ASSOC);
			// Process the data as needed
		} else {
			// Handle query execution failure
			echo "Error executing query: " . $mysqli->error;
		}

		// Clean up
		$stmt->close();
	} else {
		// Handle statement preparation failure
		echo "Error preparing statement: " . $mysqli->error;
	}
	return ['data' => $data];
}



if (isset($_POST['verified'])) {
	payment_verified();
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
							header("Location: ./admin/payment_verification.php");
							exit();
						} else {
							$_SESSION['error'] = "Code is incorrect, please check your code and try again.";
							header("Location: ./admin/payment_verify.php?id=" . $id);
							exit();
						}
					} else {
						$_SESSION['error'] = "The amount you entered is greater than the amount of debt, please try again.";
						header("Location: ./admin/payment_verify.php?id=" . $id);
						exit();
					}
				}
			}
		}
	}
}

function payment_history()
{
	$mysqli = connect();
	// If there's an error in the database connection, the function will stop.
	if (!$mysqli) {
		return false;
	}
	$historyQuery = " SELECT tbl_transaction.*, tbl_userinfo.*, tbl_useracc.*
            FROM tbl_transaction
            JOIN tbl_userinfo ON tbl_transaction.user_id = tbl_userinfo.user_id
            JOIN tbl_useracc ON tbl_transaction.user_id = tbl_useracc.user_id
            WHERE tbl_transaction.transc_type != 'arrangement'
            AND tbl_transaction.is_verified = 'yes'
            ORDER BY tbl_transaction.date;";
	$historyResult = $mysqli->query($historyQuery);
	$history = $historyResult->fetch_all(MYSQLI_ASSOC);

	return [
		'history' => $history,
	];
}
function view_history($id)
{
	$mysqli = connect();
	// If there's an error in the database connection, the function will stop.
	if (!$mysqli) {
		return false;
	}
	$viewQuery = " SELECT
            tbl_transaction.*,
            tbl_userinfo.*
        FROM
            tbl_transaction
        JOIN
            tbl_userinfo ON tbl_userinfo.user_id = tbl_transaction.user_id
        WHERE
            transac_id = ?";
	$stmt = $mysqli->prepare($viewQuery);
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$viewResult = $stmt->get_result();
	$viewHistory = $viewResult->fetch_all(MYSQLI_ASSOC);
	return [
		'viewHistory' => $viewHistory,
	];
}
function generate_pdf($id)
{
	$mysqli = connect();
	// If there's an error in the database connection, the function will stop.
	if (!$mysqli) {
		return false;
	}
	$generateQuery = " SELECT
            tbl_transaction.*,
            tbl_userinfo.*,
			tbl_property.*
        FROM
            tbl_transaction
        JOIN
            tbl_userinfo ON tbl_userinfo.user_id = tbl_transaction.user_id
		JOIN
			tbl_property ON tbl_property.user_id = tbl_transaction.user_id
        WHERE
            transac_id = ?";
	$stmt = $mysqli->prepare($generateQuery);
	$stmt->bind_param('i', $id);
	$stmt->execute();
	$generateResult = $stmt->get_result();
	$generateHistory = $generateResult->fetch_all(MYSQLI_ASSOC);
	return [
		'generate' => $generateHistory,
	];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	edituser();
}

if (isset($_POST['deleted'])) {
	deleteinfo();
}
function deleteinfo()
{
	$mysqli = connect();
	// If there's an error in the database connection, the function will stop.
	if (!$mysqli) {
		return false;
	}
	$id = $_GET['id'];
	$delete_Qeury = "
	UPDATE
                tbl_useracc
            SET
                is_deleted = 'yes'
            WHERE
                user_id = ?;
	";
	$update_delete = $mysqli->prepare($delete_Qeury);
	$update_delete->bind_param("i", $id);
	$update_delete->execute();
	$mysqli->commit();
	header("Location: ./admin/members_info.php");
	exit();
}
//+++++++++++++++++++++++++++++++++++++++++++++++++++++MEMBERS++++++++++++++++++++++++++++++++++++++++++++++++++++
function home()
{
	$mysqli = connect();
	// If there's an error in the database connection, the function will stop.
	if (!$mysqli) {
		return false;
	}
	$memberInfo = " SELECT
            *
        FROM
            tbl_transaction
        WHERE
            amount IS NULL AND DATE IS NULL AND is_verified = 'yes' AND transc_type = 'arrangement' AND user_id = ?";

	$stmt = $mysqli->prepare($memberInfo);
	$userid = $_SESSION['user_id'];
	$stmt->bind_param('i', $userid);
	$stmt->execute();
	$result = $stmt->get_result();
	$rows = [];
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$_SESSION['transac_id'] = $row['transac_id'];
		$rows[] = $row;
	}
	$historyQuery = " SELECT tbl_transaction.*, tbl_userinfo.*, tbl_useracc.*
	FROM tbl_transaction
	JOIN tbl_userinfo ON tbl_transaction.user_id = tbl_userinfo.user_id
	JOIN tbl_useracc ON tbl_transaction.user_id = tbl_useracc.user_id
	WHERE tbl_transaction.transc_type != 'arrangement'
	AND tbl_transaction.is_verified = 'yes'
	AND tbl_transaction.user_id = $userid
	ORDER BY tbl_transaction.date;";
	$historyResult = $mysqli->query($historyQuery);
	$history = $historyResult->fetch_all(MYSQLI_ASSOC);



	return [
		'row' => $rows,
		'history' => $history
	];
}
function payment()
{
	$mysqli = connect();
	if (!$mysqli) {
		return false;
	}
	$user_id = $_SESSION['user_id'];
	$pendingQuery = "
	SELECT
	*
FROM
	tbl_transaction
WHERE
	amount IS NULL AND DATE IS NULL AND is_verified = 'yes' AND transc_type = 'arrangement' AND user_id = $user_id
ORDER BY 
	due_date DESC;";

	$pendingResult = $mysqli->query($pendingQuery);
	$pending = $pendingResult->fetch_all(MYSQLI_ASSOC);

	return [
		'pending' => $pending,
	];
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
if (isset($_POST['proceed'])) {
	proceed();
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
			header("Location: member/payment_gcash.php");
		}
	}
}


function member_payment_cash()
{
	$mysqli = connect();
	if (!$mysqli) {
		return false; // Handle connection failure appropriately
	}
	$id = $_SESSION['user_id'];
	$code = $_SESSION['code'];
	$transac_id = $_SESSION['transac_id'];
	$pendingpaymentQuery = " SELECT
            *
        FROM
            tbl_transaction
        WHERE transac_id = $transac_id AND user_id = $id;";
	$pendingpaymentResult = $mysqli->query($pendingpaymentQuery);
	$pending = $pendingpaymentResult->fetch_all(MYSQLI_ASSOC);
	return [
		'pending' => $pending,
		'code' => $code
	];
}
function proceed()
{
	$mysqli = connect();
	if (!$mysqli) {
		return false; // Handle connection failure appropriately
	}
	$user_id = $_SESSION['user_id'];
	$code = $_SESSION['code'];
	$payment_id = $_SESSION['transac_id'];
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['proceed'])) {
			$code_confirm = strtoupper($code);

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
				$payment_id = $_SESSION['transac_id']; // Replace with your actual payment_id value
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
				$_SESSION['error'] = $message;
				include 'member/payment_cash.php'; // include your form page with an error message
				exit();
			}
		}
	}
}
if (isset($_POST['gcash_upload'])) {
	upload_proof();
}

function gcash()
{
	$mysqli = connect();
	if (!$mysqli) {
		return false; // Handle connection failure appropriately
	}
	$gcashQuery = '
		SELECT
            *
        FROM
            tbl_transaction
        WHERE transac_id = ' . $_SESSION['transac_id'] . ' AND user_id = ' . $_SESSION['user_id'] . ';
	
	';
	$gcashresult = $mysqli->query($gcashQuery);
	$gcash = $gcashresult->fetch_all(MYSQLI_ASSOC);

	return [
		'gcash' => $gcash
	];
}
function upload_proof()
{
	$mysqli = connect();
	if (!$mysqli) {
		return false; // Handle connection failure appropriately
	}
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$amount = $_POST['amount'];

		if (isset($_FILES['proof']) && $_FILES['proof']['error'] == UPLOAD_ERR_OK) {
			$file = $_FILES['proof'];

			if ($file['name'] != "") {
				if (!isset($_SESSION['transac_id'])) {
					$_SESSION['error'] = "Payment ID not found.";
					header("Location: payment.php");
					exit();
				}

				$payment_id = $_SESSION['transac_id'];
				$directory = "./styles/proof/";
				$sql_directory = "./proof/";

				$filename = basename($payment_id . ".jpg");
				$target_file = $directory . $filename;

				if (move_uploaded_file($file['tmp_name'], $target_file)) {
					$directory_path = $sql_directory . $filename;

					$now = new DateTime();
					$sql_now = $now->format('Y-m-d H:i:s');
					$paymentQuery = "UPDATE tbl_transaction SET proof = ?, date = ?, transc_type = 'Gcash', is_verified = 'no', amount = ? WHERE transac_id = ?";

					$update_stmt = $mysqli->prepare($paymentQuery);
					$update_stmt->bind_param("ssii", $directory_path, $sql_now, $amount, $_SESSION['transac_id']);

					if (!$update_stmt) {
						echo "Statement preparation failed: " . $mysqli->error;
						exit();
					}

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


					// Continue with database update or other processing
				} else {
					$_SESSION['error'] = "Failed to upload file.";
					header("Location: payment.php");
					exit();
				}
			}
		}
	}
}
function member_payment_history()
{
	$id = $_SESSION['user_id'];
	$mysqli = connect();
	// If there's an error in the database connection, the function will stop.
	if (!$mysqli) {
		return false;
	}
	$member_historyQuery = " SELECT tbl_transaction.*, tbl_userinfo.*, tbl_useracc.*
            FROM tbl_transaction
            JOIN tbl_userinfo ON tbl_transaction.user_id = tbl_userinfo.user_id
            JOIN tbl_useracc ON tbl_transaction.user_id = tbl_useracc.user_id
            WHERE tbl_transaction.transc_type != 'arrangement'
            AND tbl_transaction.is_verified = 'yes'
            AND tbl_transaction.user_id = $id
            ORDER BY tbl_transaction.date 
			";
	$member_historyResult = $mysqli->query($member_historyQuery);
	$member_history = $member_historyResult->fetch_all(MYSQLI_ASSOC);

	return [
		'member_history' => $member_history,
	];
}

// function accountverify()
// {
//     // Connect to the database
//     $mysqli = connect();
//     if (!$mysqli) {
//         return false;
//     }

//     // Define the query
//     $queryverifyyet = "SELECT COUNT(*) FROM tbl_useracc WHERE is_admin='no' AND is_deleted='no'  AND is_verified='no'";
// 	$queryverifyyet = "SELECT COUNT(*) FROM tbl_useracc WHERE is_admin='no' AND is_deleted='no'  AND is_verified='no'";

//     // Execute the query
//     $resultverifyyet = mysqli_query($mysqli, $queryverifyyet);

//     // Check if the query was successful
//     if (!$resultverifyyet) {
//         // Query failed, return false or handle the error
//         return false;
//     }

//     // Fetch the result
//     $rowverfyet = mysqli_fetch_array($resultverifyyet);

//     // Check if fetching the result was successful
//     if (!$rowverfyet) {
//         // Fetch failed, return false or handle the error
//         return false;
//     }

//     // Get the count
//     $countyet = $rowverfyet[0];

//     // Output the count
//     echo $countyet;

//     // Close the database connection
//     mysqli_close($mysqli);
// }
