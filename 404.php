<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        /* body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .error-container {
            text-align: center;
        }

        .error-heading {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .back-link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
            background-color: #0056b3;
        } */

        * {
            font-family: Google sans, Arial;
        }

        .flex-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
            animation: colorSlide 15s cubic-bezier(0.075, 0.82, 0.165, 1) infinite;

            .text-center {
                text-align: center;

                h1,
                h3 {
                    margin: 10px;
                    cursor: default;

                    .fade-in {
                        animation: fadeIn 2s ease infinite;
                    }
                }

                h1 {
                    font-size: 8em;
                    transition: font-size 200ms ease-in-out;
                    border-bottom: 1px dashed white;

                    span#digit1 {
                        animation-delay: 200ms;
                    }

                    span#digit2 {
                        animation-delay: 300ms;
                    }

                    span#digit3 {
                        animation-delay: 400ms;
                    }
                }

                button {
                    border: 1px solid white;
                    background: transparent;
                    outline: none;
                    padding: 10px 20px;
                    font-size: 1.1rem;
                    font-weight: bold;
                    color: white;
                    text-transform: uppercase;
                    transition: background-color 200ms ease-in;
                    margin: 20px 0;

                    &:hover {
                        background-color: white;
                        color: #555;
                        cursor: pointer;
                    }
                }
            }
        }

        @keyframes colorSlide {
            0% {
                background-color: #6FB9B9;
            }

            25% {
                background-color: #6fb98d;
            }

            50% {
                background-color: seagreen;
            }

            75% {
                background-color: #6f90b9;
            }

            100% {
                background-color: #6FB9B9;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            ;

            100% {
                opacity: 1;
            }
        }
    </style>
    <?php require ($_SERVER['DOCUMENT_ROOT'] . '/hofin/navbar.php'); ?>

</head>

<body>
    <div class="flex-container">
        <div class="text-center">
            <h1>
                <span class="fade-in" id="digit1">4</span>
                <span class="fade-in" id="digit2">0</span>
                <span class="fade-in" id="digit3">4</span>
            </h1>
            <h3 class="fadeIn">The page you requested either was moved or doesn’t exist.</h3>
            <button type="button" name="button" onclick="location.href='/hofin'">Return To Home</button>
        </div>
    </div>
</body>

</html>