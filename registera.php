<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Affectionate Acroama - Sign In</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
        }

        .container {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .brand-heading {
            font-family: 'Your Preferred Stylish Font', sans-serif;
            /* Replace with your preferred font */
            font-size: 36px;
            text-align: center;
            font-weight: bold;
            /* Bold font */
            margin-bottom: 20px;
        }

        .sign-in-form {
            background-color: #ffffff;
            /* White background */
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3), inset 0 0 20px rgba(255, 255, 255, 0.3);
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
        }

        .sign-in-form h2 {
            text-align: center;
            color: #000000;
            /* Black text color */
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-size: 18px;
            color: #000000;
            /* Black text color */
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #000000;
            /* Black border */
            border-radius: 5px;
            font-size: 16px;
            background-color: #ffffff;
            /* White background */
            color: #000000;
            /* Black text color */
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .form-group input:focus {
            background-color: #f5f5f5;
            /* Light gray color on focus */
            border-color: #000000;
            /* Black border on focus */
        }

        .form-group button {
            background-color: #000000;
            /* Black background */
            color: #ffffff;
            /* White text color */
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            margin: 0 auto;
            /* Center the button */
        }

        .form-group button:hover {
            background-color: #333333;
            /* Dark gray color on hover */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="brand-heading">
            Affectionate Acroama
        </div>

        <div class="sign-in-form">
            <?php
            // Include PHPMailer Autoloader
            require 'PHPMailer/Exception.php';
            require 'PHPMailer/PHPMailer.php';
            require 'PHPMailer/SMTP.php';


            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $name = $_POST['name'];
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $confirmPassword = $_POST['confirm_password'];

                // Check if passwords match
                if ($password !== $confirmPassword) {
                    echo "<div class='alert alert-danger' role='alert'>Passwords do not match</div>";
                } else {
                    // Database connection
                    $conn = new mysqli('localhost', 'root', '', 'affectionate_acroama');
                    if ($conn->connect_error) {
                        die('Connection Failed : ' . $conn->connect_error);
                    } else {
                        // Check if username already exists
                        $check_query = "SELECT * FROM sign_in WHERE username=?";
                        $stmt = $conn->prepare($check_query);
                        $stmt->bind_param("s", $username);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            echo "<div class='alert alert-danger' role='alert'>Username already exists. Please choose a different username.</div>";
                        } else {
                            // Insert new record
                            $insert_query = "INSERT INTO sign_in (name, username, email, password) VALUES (?, ?, ?, ?)";
                            $stmt = $conn->prepare($insert_query);
                            $stmt->bind_param("ssss", $name, $username, $email, $password); // Assuming all are strings
                            if ($stmt->execute()) {
                                // Registration Successful
                                echo "<div class='alert alert-success' role='alert'>Registration Successful....</div>";

                                // Send email using PHPMailer
                                $mail = new PHPMailer(true);

                                try {
                                    // SMTP settings
                                    $mail->isSMTP();
                                    $mail->Host = 'smtp.gmail.com';
                                    $mail->SMTPAuth = true;
                                    $mail->Username = 'affectionateacroama@gmail.com';
                                    $mail->Password = 'jdiemguemzryfvem';
                                    $mail->SMTPSecure = 'tls';
                                    $mail->Port = 587;

                                    // Email content
                                    $mail->setFrom('affectionateacroama@gmail.com', 'Hetvi');
                                    $mail->addAddress($email, $name);
                                    $mail->Subject = "welcome $name";
                                    $mail->Body = 'Greetings from Affectionate Acroama';

                                    // Send email
                                    $mail->send();
                                    echo "<div class='alert alert-success' role='alert'>Email sent successfully</div>";
                                } catch (Exception $e) {
                                    echo "<div class='alert alert-danger' role='alert'>Failed to send email. Error: {$mail->ErrorInfo}</div>";
                                }
                            } else {
                                echo "<div class='alert alert-danger' role='alert'>Error: " . $stmt->error . "</div>";
                            }
                        }

                        $stmt->close();
                        $conn->close();
                    }
                }
            }
            ?>
            <h2>Sign Up</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="mb-3 form-group">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="mb-3 form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required>
                </div>
                <div class="mb-3 form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3 form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3 form-group">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-dark btn-block">Sign Up</button>
                </div>
            </form>
            <div class="form-group">
                <h4>Already have an account!<a href="index.html">Log In</a></h4>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
