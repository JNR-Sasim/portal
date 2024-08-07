<?php
// Include the database connection file
include 'server/connection.db.php';

// Start the session
session_start();

// Include the PHPMailer library
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

// Initialize error and success arrays
$errors = [];
$success = [];

// Check if form is submitted
if (isset($_POST['forgot-password'])) {
    $email = $_POST['email'];

    // Query to fetch user data based on email
    $sql = "SELECT id FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify user data
    if ($user) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));

        // Store token in database
        $sql = "UPDATE user SET reset_token = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Send reset link to user email using PHPMailer
        $resetLink = "http://customer-helpdesk.sasimit.co.za/reset-password.php?token=" . $token;
        $emailContent = "Click the link below to reset your password:\n\n" . $resetLink;

        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'theunity.aserv.co.za'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;
            $mail->Username = 'helpdesk@sasimit.co.za'; // SMTP username
            $mail->Password = 'help@Sasimit'; // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            //Recipients
            $mail->setFrom('noreply@customer-helpdesk.sasimit.co.za', 'Customer Helpdesk');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(false);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = $emailContent;

            $mail->send();
            $success[] = "Password reset link has been sent to your email.";
        } catch (Exception $e) {
            $errors[] = 'Message could not be sent. Mailer Error: '. $mail->ErrorInfo;
        }
    } else {
        $errors[] = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Help Desk</title>
    <link rel="stylesheet" href="app.css"> 
</head>
<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="text-center mt-4">
                            <h1 class="h2">Forgot Password</h1>
                            <p class="lead">Enter your email to reset your password</p>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-3">
                                    <?php if (!empty($errors)): ?>
                                        <div class="alert alert-danger">
                                            <?php foreach ($errors as $error): ?>
                                                <p><?php echo htmlspecialchars($error); ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($success)): ?>
                                        <div class="alert alert-success">
                                            <?php foreach ($success as $msg): ?>
                                                <p><?php echo htmlspecialchars($msg); ?></p>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    <form method="post">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input class="form-control form-control-lg" type="email" name="email" required placeholder="Enter your Email" />
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary" name="forgot-password">Send Reset Link</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <a href="index.php">Back to Sign In</a>
							
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="js/app.js"></script>
</body>
</html>
