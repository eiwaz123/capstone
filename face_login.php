<?php

function decryptLabelsData($filePath, $key)
{
  $encryptedDataWithIV = file_get_contents($filePath);
  if ($encryptedDataWithIV !== false) {
    $iv_hex = substr($encryptedDataWithIV, 0, 32); // Extract IV from the beginning
    $encryptedData = substr($encryptedDataWithIV, 32); // Extract encrypted data without IV
    $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, hex2bin($iv_hex));
    if ($decryptedData !== false) {
      return $decryptedData;
    } else {
      echo '<script>console.log("Failed to decrypt data from labels.json.");</script>';
    }
  } else {
    echo '<script>console.log("Failed to read data from labels.json.");</script>';
  }
}

try {
  $labelsData = decryptLabelsData('./face/labels.json', 'Adm1n123');
  $labelsArray = json_decode($labelsData, true);
} catch (Exception $e) {
  echo '<script>console.log("ERROR: ' . $e->getMessage() . '");</script>';
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Face Login</title>

  <script src="face-api.min.js"></script>

  <link rel="stylesheet" href="<?php $_SERVER['DOCUMENT_ROOT'] ?>/hofin/styles/css/face_login.css">
  <?php require ($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>

</head>

<body>


  <div class="left">
    <div class="centered">

      <?php
      // echo "<pre>";
      // print_r($_SESSION);
      // echo time();
      // echo "</pre>";
      if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $response = login($username, $password);
        if ($response != "success") {
          echo "<label>
                    <input type='checkbox' class='alertCheckbox' autocomplete='off' />
                    <div class='alert warna'>
                        <span class='alertClose'>X</span>
                        <span class='alertText'>
                        <i class='fa-solid fa-x'></i> " . $response . "
                        <br class='clear' /></span>
                    </div>
                    </label>";
        } else {
          echo "<label>
                    <input type='checkbox' class='alertCheckbox' autocomplete='off' />
                    <div class='alert successa'>
                        <span class='alertClose'>X</span>
                        <span class='alertText'>
                        <i class='fa-solid fa-check'></i> Login Success
                        <br class='clear' /></span>
                    </div>
                    </label>";
        }
      }
      ?>

      <h1>Face Scan Here:</h1>
      <div id="camalert" hidden>
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
      <video id="video" width="640" height="480" autoplay hidden></video>

      <canvas id="overlay" class="overlay"></canvas>
      <form id="facelogin" method="post" action="">

        <span id="usernamespan" hidden class="display: none; visibility: hidden;"></span>
        <span id="passwordspan" hidden class="display: none; visibility: hidden;"></span>
        <input type="hidden" id="username" name="username">
        <input type="hidden" id="password" name="password">

        <button disabled id="submitButton" type="submit" name="submitButton" class="login">Login</button>
        <a href="./index.php" class="login">Back</a>
      </form>
    </div>
  </div>
  <div class="right">
    <div class="centered">
      <h1>Registered Face:</h1>
      <div class="image-container">
        <img draggable="false" id="relatedImage" class="relatedImageContainer" src="./face/labels/Unknown/0.jpg">
      </div>
      <div id="Name:"></div>
      <div id="confidence"></div>
      <br>
    </div>
</body>

<script>
  const labelsArray = <?php echo json_encode($labelsArray); ?>;
  const video = document.getElementById("video");
  const overlay = document.getElementById("overlay");
  const relatedImageContainer = document.getElementById("relatedImageContainer");

  const usernamespan = document.getElementById("usernamespan");
  const passwordspan = document.getElementById("passwordspan");

  const confidenceDisplay = document.getElementById("confidence");
  const submitButton = document.getElementById("submitButton");

  const displaySize = { width: video.width, height: video.height };


  // Submit form and container
  document.addEventListener("DOMContentLoaded", function () {
    const submitButton = document.getElementById("submitButton");

    const usernameinput = document.getElementById("username");
    const passwordinput = document.getElementById("password");

    submitButton.addEventListener("click", () => {

      const username = usernamespan.textContent;
      const password = passwordspan.textContent;

      usernameinput.value = username;
      passwordinput.value = password;

      document.getElementById("facelogin").submit();
    });
  });

  Promise.all([
    faceapi.nets.ssdMobilenetv1.loadFromUri("models"),
    faceapi.nets.faceRecognitionNet.loadFromUri("models"),
    faceapi.nets.faceLandmark68Net.loadFromUri("models"),
  ]).then(startWebcam);

  async function startWebcam() {
    try {
      navigator.mediaDevices
        .getUserMedia({
          video: true,
          audio: false,
        })
        .then((stream) => {
          video.srcObject = stream;
          video.onloadedmetadata = () => {
            overlay.width = video.videoWidth;
            overlay.height = video.videoHeight;

            const labeledFaceDescriptors = getLabeledFaceDescriptions(labelsArray);
            labeledFaceDescriptors.then((labeledFaceDescriptors) => {
              const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors);

              setInterval(async () => {
                const detections = await faceapi
                  .detectAllFaces(video, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.5, minFaceSize: 100 }))
                  .withFaceLandmarks()
                  .withFaceDescriptors();

                const resizedDetections = faceapi.resizeResults(detections, displaySize);

                const context = overlay.getContext("2d");
                context.clearRect(0, 0, overlay.width, overlay.height);

                let bestMatch = null;
                let bestMatchScore = 0;

                resizedDetections.forEach((detection, i) => {
                  const result = faceMatcher.findBestMatch(detection.descriptor);
                  const confidence = 1 - result.distance;
                  if (confidence > bestMatchScore) {
                    bestMatch = result;
                    bestMatchScore = confidence;
                  }

                  const box = detection.detection.box;
                  const drawBox = new faceapi.draw.DrawBox(box, {
                    label: `${result.toString()} (${Math.round(confidence * 100)}% match)`,
                  });
                  drawBox.draw(context);
                });

                if (bestMatch && bestMatchScore >= 0.57) {
                  const relatedImageSrc = `./face/labels/${bestMatch.label}/1.jpg`;
                  updateRelatedImage(relatedImageSrc);
                  displayUserDetails(bestMatch.label);
                  confidenceDisplay.innerText = `Confidence Score: ${parseFloat((bestMatchScore).toFixed(2))}`;

                  submitButton.disabled = false;
                } else {

                  submitButton.disabled = true;
                  usernamespan.textContent = "";
                  passwordspan.textContent = "";
                  confidenceDisplay.innerText = "Confidence Score: N/A";

                  // Set the related image to the placeholder for "Unknown"
                  updateRelatedImage("./face/labels/Unknown/0.jpg");
                }
                // Draw the outline with the specified color
              }, 100);
            });
          };
        })
        .catch((error) => {
          console.error(error);
        });
    } catch (error) {
      console.error('Error starting webcam:', error);
    }
  }

  async function getLabeledFaceDescriptions(labels) {
    try {
      return Promise.all(
        labels.map(async (labelData) => {
          const { name } = labelData;
          console.log(name);
          const descriptions = [];
          for (let i = 0; i <= 2; i++) {
            try {
              let img;
              try {
                img = await faceapi.fetchImage(`./face/labels/${name}/${i}.jpg`);
              } catch (jpgError) {
                console.error(`Error fetching JPG image ${i} for label ${name}:`, jpgError);
                img = await faceapi.fetchImage(`./face/labels/${name}/${i}.png`);
              }
              const detections = await faceapi.detectSingleFace(img).withFaceLandmarks().withFaceDescriptor();
              if (detections) {
                descriptions.push(detections.descriptor);
              } else {
                console.log(`No face detected in image ${i} for label ${name}`);
              }
            } catch (error) {
              console.error(`Error fetching image ${i} for label ${name}:`, error);
            }
          }
          return new faceapi.LabeledFaceDescriptors(name, descriptions);
        })
      );
    } catch (error) {
      console.error('Error fetching labeled face descriptions:', error);
      throw error;
    }
  }

  function updateRelatedImage(src) {
    const existingImage = document.querySelector("#relatedImage");
    if (existingImage) {
      existingImage.src = src;
    } else {
      const relatedImage = document.createElement("img");
      relatedImage.id = "relatedImage";
      relatedImage.src = src;
      relatedImageContainer.appendChild(relatedImage);
    }
  }

  function displayUserDetails(label) {
    try {
      const user = labelsArray.find(item => item.name === label);
      if (user) {
        usernamespan.textContent = user.username;
        passwordspan.textContent = user.password;
      } else {
        usernamespan.textContent = "";
        passwordspan.textContent = "";
      }
    } catch (error) {
      console.error('Error displaying user details:', error);
    }
  }

  navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
      video.srcObject = stream;
      document.getElementById('video').hidden = false;
    })
    .catch(error => {
      console.error('Error accessing camera:', error);
      document.getElementById('camalert').hidden = false;
    });

</script>

</html>