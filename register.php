<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>

    <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/register.css">
    <?php require ($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>

</head>

<body>
    <div class="left">

        <div class='alert graya'>
            Captured Image: <p id="captureCountDisplay"></p>
        </div>
        <video id="video" width="100%" height="auto" autoplay></video>
        <div id="alert" hidden>
            <label>
                <input type='checkbox' class='alertCheckbox' autocomplete='off' />
                <div class='alert warna'>
                    <span class='alertClose'>X</span>
                    <span class='alertText'>
                        <i class='fa-solid fa-x'></i> Camera Not Found, Please use device with camera and try again.
                        <br class='clear' /></span>
                </div>
            </label>
        </div>
        <p class="note1">
            - Please take picture on well lit area <br>
            - Capture 3 times before proceeding to registration <br>
            - If there's an error, CAPTURE again
        </p>
        <br>
        <button id="capture-btn" class="login">Capture</button>
    </div>
    <div class="right">
        <div class="centered">
            <?php
            if ($_SERVER["REQUEST_METHOD"] === "POST") {

                $given_name = $_POST['given_name'];
                $middle_name = $_POST['middle_name'];
                $last_name = $_POST['last_name'];

                $gender = $_POST['gender'];
                $bday = $_POST['bday'];

                $username = $_POST['username'];
                $password = $_POST['password'];

                $confirm = $_POST['confirm'];
                $email = $_POST['email'];

                $image_data = json_decode($_POST['image-data']);
                $response = register($given_name, $middle_name, $last_name, $gender, $bday, $username, $password, $confirm, $email, $image_data);
                if ($response == "success") {

                    $_POST['given_name'] = '';
                    $_POST['middle_name'] = '';
                    $_POST['last_name'] = '';

                    $_POST['gender'] = '';
                    $_POST['bday'] = '';

                    $_POST['username'] = '';
                    $_POST['password'] = '';

                    $_POST['confirm'] = '';
                    $_POST['email'] = '';

                    echo "<label>
                    <input type='checkbox' class='alertCheckbox' autocomplete='off' />
                    <div class='alert successa'>
                        <span class='alertClose'>X</span>
                        <span class='alertText'>
                        <i class='fa-solid fa-check'></i> Registration Successful! Please wait for the admin to approve your account
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
            <br>
            <form id="info-form" method="post" enctype="multipart/form-data">
                <h1 class="reg">Register Account</h1>
                <div class="personal-information">
                    <h3 class="info">Personal Information</h3>
                    <br>
                    <div class="personal-info">
                        <div class="personal-info-col"><label for="given-name">Given Name</label></div>
                        <div class="personal-info-col"><label for="middle-name">Middle Name</label></div>
                        <div class="personal-info-col"><label for="last-name">Last Name</label></div>
                        <div class="personal-info-col"><input type="text" name="given_name" required autofocus
                                placeholder="First Name" value="<?php echo @$_POST['given_name']; ?>"></div>
                        <div class="personal-info-col"><input type="text" name="middle_name" required
                                placeholder="Middle Name" value="<?php echo @$_POST['middle_name']; ?>"></div>
                        <div class="personal-info-col-last"><input type="text" name="last_name" required
                                placeholder="Last Name" value="<?php echo @$_POST['last_name']; ?>"></div>
                    </div>
                    <div class="double-half">
                        <div class="personal-half">
                            <label for="gender">Gender
                                <select name="gender" name="gender" required>
                                    <option value="" selected hidden>Please select gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </label>
                        </div>
                        <div class="personal-half">
                            <label for="birthday">Birthday
                                <input type="date" name="bday" max="<?php echo date('Y-m-d'); ?>" id="bday"
                                    value="<?php echo @$_POST['bday']; ?>" required>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="account-information">
                    <br>
                    <h3 class="info">Account Information</h3>

                    <label for="username">Username</label>
                    <input type="text" name="username" value="<?php $user = create_user();
                    echo $user ?>" required readonly>
                    <p class="note">Please don't forget this, This will be your permanent username</p>

                    <div class="account-info">
                        <div class="account-info-col"><label for="password">Password</label></div>
                        <div class="account-info-col"><label for="confirm-password">Confirm Password</label></div>
                        <div class="account-info-col">
                            <input type="password" name="password" required placeholder="Password"
                                pattern="(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()_+]).{8,}"
                                title="Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 symbol, and be at least 8 characters long"
                                value="<?php echo @$_POST['confirm']; ?>" class="<?php
                                   if ($response == 'Passwords do not match.' || $response == 'Password is too short, must be 8-24 characters' || $response == 'Password is too long, must be 8-24 characters') {
                                       echo 'wrong';
                                   } else {
                                       echo '';
                                   }
                                   ?>">
                        </div>
                        <div class="account-info-col-last">
                            <input type="password" name="confirm" required placeholder="Confirm Password"
                                pattern="(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()_+])[A-Za-z\d!@#$%^&*()_+]{8,}"
                                title="Password must contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 symbol, and be at least 8 characters long"
                                value="<?php echo @$_POST['confirm']; ?>" class="<?php
                                   if ($response == 'Passwords do not match.' || $response == 'Password is too short, must be 8-24 characters' || $response == 'Password is too long, must be 8-24 characters') {
                                       echo 'wrong';
                                   } else {
                                       echo '';
                                   }
                                   ?>">
                        </div>
                    </div>

                    <label for="email">Email
                        <input type="email" name="email" placeholder="Email" value="<?php echo @$_POST['email']; ?>"
                            required>
                    </label>

                    <input type="hidden" id="image-data" name="image-data">
                    <button type="submit" class="button" id="submit-btn" class="login"
                        title="Please capture 3 times before registering" disabled>Register</button>
                    <a href="./index.php" class="login">Back</a>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        const video = document.getElementById('video');
        const captureBtn = document.getElementById('capture-btn');
        const submitBtn = document.getElementById('submit-btn');
        const imageDataInput = document.getElementById('image-data');
        let captureCount = 0;
        let capturedImages = [];

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(error => {
                console.error('Error accessing camera:', error);
                document.getElementById('alert').hidden = false;
                captureBtn.disabled = true;
                submitBtn.disabled = true;
            });

        captureBtn.addEventListener('click', () => {
            if (captureCount < 3) { // Capture three images
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                const imageData = canvas.toDataURL('image/jpeg');
                capturedImages.push(imageData);
                document.getElementById('captureCountDisplay').textContent = (captureCount + 1); // Update the capture count in the HTML
                if (captureCount >= 2) { // Disable button after capturing three images
                    captureBtn.disabled = true;
                    submitBtn.disabled = false; // Enable the submit button after capturing three images
                }
            } else {
                alert('You have captured the maximum number of photos.');
                submitBtn.disabled = false; // Enable the submit button if the user wants to proceed without capturing more images
            }
            captureCount++; // Increment the capture count
        });


        document.getElementById('info-form').addEventListener('submit', event => {
            event.preventDefault();
            imageDataInput.value = JSON.stringify(capturedImages);
            document.getElementById('info-form').submit(); // Submit the form
        });
    </script>



</html>