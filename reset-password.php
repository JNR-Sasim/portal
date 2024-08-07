<?php
// Include the database connection file
include 'server/connection.db.php';

// Start the session
session_start();

// Initialize error and success arrays
$errors = [];
$success = [];

// Check if token and form are submitted
if (isset($_GET['token']) && isset($_POST['reset-password'])) {
    $token = $_GET['token'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Query to fetch user data based on token
    $sql = "SELECT id FROM user WHERE reset_token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify token and reset password
    if ($user) {
        $sql = "UPDATE user SET password = ?, reset_token = NULL WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_password, $user['id']);
        $stmt->execute();

        $success[] = "Your password has been successfully reset.";
    } else {
        $errors[] = "Invalid token.";
    }
} elseif (isset($_GET['token'])) {
    // Token is present, show reset form
} else {
    $errors[] = "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Help Desk</title>
    <link rel="stylesheet" href="app.css"> 
</head>
<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="text-center mt-4">
                            <h1 class="h2">Reset Password</h1>
                            <p class="lead">Enter your new password below</p>
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
                                    <?php if (isset($_GET['token']) && empty($success)): ?>
                                    <form method="post">
                                        <div class="mb-3">
                                            <label class="form-label">New Password</label>
                                            <input class="form-control form-control-lg" type="password" name="new_password" required placeholder="Enter your Password"/>
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary" name="reset-password">Reset Password</button>
                                        </div>
                                    </form>
                                    <?php endif; ?>
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
