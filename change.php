<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User Data</title>

    <?php include 'navbar.php'; ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #ffffff;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .form-container {
            max-width: 400px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Collect form data
            $given_name = $_POST['given_name'];
            $middle_name = $_POST['middle_name'];
            $last_name = $_POST['last_name'];

            $gender = $_POST['gender'];
            $bday = $_POST['bday'];

            $username = $_POST['username'];
            $password = $_POST['password'];

            $response = update_password($given_name, $middle_name, $last_name, $gender, $bday, $username, $password);
            if ($response == "success") {
                $_POST['given_name'] = '';
                $_POST['middle_name'] = '';
                $_POST['last_name'] = '';
                $_POST['gender'] = '';
                $_POST['bday'] = '';
                $_POST['username'] = '';
                $_POST['password'] = '';

                echo "<label>
                    <input type='checkbox' class='alertCheckbox' autocomplete='off' />
                    <div class='alert successa'>
                        <span class='alertClose'>X</span>
                        <span class='alertText'>
                        <i class='fa-solid fa-check'></i> Account Updated
                        <br class='clear' /></span>
                    </div>
                    </label>";
            } else {

                echo "<label>
                <input type='checkbox' class='alertCheckbox' autocomplete='off' />
                <div class='alert warna'>
                    <span class='alertClose'>X</span>
                    <span class='alertText'>
                    <i class='fa-solid fa-x'></i> " . $response . "
                    <br class='clear' /></span>
                </div>
                </label>";
            }

        }

        ?>

        <form action="" method="post">
            <label for="given_name">Given Name:</label>
            <input type="text" id="given_name" name="given_name" value="<?php echo @$_POST['given_name']; ?>"
                required><br><br>

            <label for="middle_name">Middle Name:</label>
            <input type="text" id="middle_name" name="middle_name"
                value="<?php echo @$_POST['middle_name']; ?>"><br><br>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required
                value="<?php echo @$_POST['last_name']; ?>"><br><br>

            <label for="gender">Gender
                <select name="gender" name="gender" required>
                    <option value="" selected hidden>Please select gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </label><br><br>

            <label for="birthday">Birthday
                <input type="date" name="bday" max="<?php echo date('Y-m-d'); ?>" id="bday"
                    value="<?php echo @$_POST['bday']; ?>" required>
            </label><br><br>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required
                value="<?php echo @$_POST['username']; ?>"><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required
                value="<?php echo @$_POST['password']; ?>"><br><br>

            <input type="submit" value="Update User">
        </form>
    </div>
</body>


</html>