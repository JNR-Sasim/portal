<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection file
include 'server/connection.db.php';

// Start the session
session_start();

// Check if the connection is successful
if ($conn === null) {
    die("Database connection failed");
}

// Initialize error array
$errors = [];

// Check if form is submitted
if (isset($_POST['sign-in'])) {
    $role_id = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Debugging output
    echo "Role ID: " . htmlspecialchars($role_id) . "<br>";
    echo "Email: " . htmlspecialchars($email) . "<br>";

    // Query to fetch user data based on email, role, and password
    $sql = "SELECT u.id, u.password FROM user u JOIN user_role r ON u.role_id = r.id WHERE u.email = ? AND r.id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        $errors[] = "Statement preparation failed: " . $conn->error;
    } else {
        $stmt->bind_param("si", $email, $role_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            $user = $result->fetch_assoc();
            var_dump($user); // Check what data is fetched

            // Verify user data and password with MD5
            if ($user && md5($password) === $user['password']) {
                // Store user data in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $role_id;

                // Redirect to a protected page
                header('Location: tickets.php');
                exit();
            } else {
                $errors[] = "Invalid role, email, or password";
            }
        } else {
            $errors[] = "SQL query failed: " . $stmt->error;
        }
    }
}

// Fetch role data from the user_role table
$sql = "SELECT id, name FROM user_role";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $roles = [];
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }
} else {
    $errors[] = "Error fetching role data from database: " . $conn->error;
    $roles = []; // Empty array if no data found
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Customer Service Management System</title>
    <link rel="stylesheet" href="app.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-J1GCZxHh1N1C5vzMOfXzFWeMiSdeAPn5A6+M3nxT4UCUqLpt9qW/LnUSxR+0zMHpvc0R47lWnmSlgrIcRlDEWQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="text-center mt-4">
                            <h1 class="h2">Welcome back!</h1>
                            <p class="lead">Sign in to your account to continue</p>
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
                                    <form method="post">
                                        <div class="mb-3">
                                            <label class="form-label">User Role</label>
                                            <select class="form-control form-control-lg" name="role" id="roleSelect" required>
                                                <option value="" disabled selected>Select your role</option>
                                                <?php foreach ($roles as $role): ?>
                                                    <option value="<?php echo htmlspecialchars($role['id']); ?>">
                                                        <?php echo htmlspecialchars($role['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input class="form-control form-control-lg" type="email" name="email" id="emailField" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input class="form-control form-control-lg" type="password" name="password" id="passwordField" required />
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary" name="sign-in">Sign in</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <a href="forgot-password.php">Forgot Password</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="js/app.js"></script>
    <script src="js/tab.js"></script>
</body>
</html>


