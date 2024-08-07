<?php
session_start();

// Include the database connection file
include 'server/connection.db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: sign-in.php'); // Redirect to login page if not logged in
    exit();
}

$user_id = $_SESSION['user_id']; // Get user ID from session

// Initialize error array
$errors = [];

// Fetch user role data from the user_role table
$sql_roles = "SELECT id, name FROM user_role";
$result_roles = $conn->query($sql_roles);

// Check if query was successful
$roles = [];
if ($result_roles && $result_roles->num_rows > 0) {
    while ($row_role = $result_roles->fetch_assoc()) {
        $roles[] = $row_role;
    }
} else {
    $errors[] = "Error fetching user roles from database";
}

// Fetch user data from the database
$sql_user = "SELECT * FROM user WHERE id = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result_user = $stmt->get_result();
$user = $result_user->fetch_assoc();

if (!$user) {
    die("Error fetching user data");
}

// Check if form is submitted
if (isset($_POST['update'])) {
    // Retrieve form data
    $first_Name = $_POST['first_Name'];
    $second_Name = $_POST['second_Name'];
    $last_Name = $_POST['last_Name'];
    $role_id = $_POST['role_id'];
    $manager_id = $_POST['manager_id'];
    $company= $_POST['company'];
    $job_title = $_POST['job_title'];
    $business_phone = $_POST['business_phone'];
    $contact_Number = $_POST['contact_Number'];
    $fax_number = $_POST['fax_number'];
    $street_address = $_POST['street_address'];
    $city = $_POST['city'];
    $state_province = $_POST['state_province'];
    $zip_postal_code = $_POST['zip_postal_code'];
    $country_region = $_POST['country_region'];
    $email = $_POST['email'];
    $notes = $_POST['notes'];

    // Update user data in the database
    $sql_update = "UPDATE user SET first_Name = ?, second_Name = ?, last_Name = ?, role_id = ?, manager_id = ?, company_id = ?, job_title = ?, business_phone = ?, contact_number = ?, fax_number = ?, street_address = ?, city = ?, state_province = ?, zip_postal_code = ?, country_region = ?, email = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssssssssssssssssi", $first_Name, $second_Name, $last_Name, $role_id, $manager_id, $company_id, $job_title, $business_phone, $contact_number, $fax_number, $street_address, $city, $state_province, $zip_postal_code, $country_region, $email, $user_id);
    if ($stmt_update->execute()) {
        echo "Profile updated successfully.";
    } else {
        echo "Error updating profile: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="app.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .profile-container {
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            gap: 20px;
        }

        .profile-pic-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-pic {
            width: 290px; 
            height: 280px; 
            background: #ccc;
            border-radius: 0; 
            margin-bottom: 20px;
        }

        .profile-form {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            flex-grow: 1;
            max-width: 600px;
        }

        .profile-form div {
            margin-bottom: 10px;
        }

        .profile-form label {
            display: block;
            margin-bottom: 5px;
        }

        .profile-form input, .profile-form select, .profile-form textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .notes {
            grid-column: span 2;
        }

        .notes textarea {
            width: 100%;
            height: 100px;
        }

        .btn {
            grid-column: span 2;
            margin-top: 20px;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start; /* Align items at the start */
        }

        nav ul li {
            margin-bottom: 10px;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff; 
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #555; 
        }

        nav ul li a i {
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="tickets.php">Tickets</a></li>
               
            </ul>
        </nav>

        <div class="main">
            <main class="content">
                <div class="container-fluid p-0">
                    <div class="mb-3">
                        <h1 class="h3 d-inline align-middle">Profile</h1>
                    </div>

                    <div class="row">
                        <div class="col-md-8 col-xl-9">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Edit Profile</h5>
                                </div>

                                <div class="card-body h-100">
                                    <div class="profile-container">
                                        <form action="edit -profile.php" method="post" enctype="multipart/form-data" class="profile-form">
                                            <div>
                                                <label for="firstName">First Name:</label>
                                                <input type="text" name="first_Name" class="form-control" placeholder="First Name" value="<?php echo htmlspecialchars($user['first_name'] ?? ''); ?>">
                                            </div>
                                            <div>
                                                <label for="secondName">Second Name:</label>
                                                <input type="text" name="second_Name" class="form-control" placeholder="Second Name" value="<?php echo htmlspecialchars($user['second_name'] ?? ''); ?>">
                                            </div>
                                            <div>
                                                <label for="lastName">Last Name:</label>
                                                <input type="text" name="last_Name" class="form-control" placeholder="Last Name" value="<?php echo htmlspecialchars($user['last_name'] ?? ''); ?>">
                                            </div>
                                            <div>
                                                <label for="role_id">Role ID:</label>
                                                <select name="role_id" class="form-control">
                                                    <option value="" disabled>Select Role</option>
                                                    <?php foreach ($roles as $role): ?>
                                                        <option value="<?php echo $role['id']; ?>" <?php echo (isset($user['role_id']) && $user['role_id'] == $role['id']) ? 'selected' : ''; ?>><?php echo $role['name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="manager_id">Manager ID:</label>
                                                <input type="text" name="manager_id" class="form-control" placeholder="Manager ID" value="<?php echo htmlspecialchars($user['manager_id'] ?? ''); ?>">
                                            </div>
                                            <div>
                                                <label for="company">Company:</label>
                                                <input type="text" name="company" class="form-control" placeholder="Company" value="<?php echo htmlspecialchars($user['company_id'] ?? ''); ?>">
                                            </div>
                                            <div>
                                                <label for="jobTitle">Job Title:</label>
                                                <input type="text" name="job_title" class="form-control" placeholder="Job Title" value="<?php echo htmlspecialchars($user['job_title'] ?? ''); ?>">
                                            </div>
                                            <div>
                                                <label for="businessPhone">Business Phone:</label>
                                                <input type="text" name="business_phone" class="form-control" placeholder="Business Phone" value="<?php echo htmlspecialchars($user['business_phone'] ?? ''); ?>">
                                            </div>
                                            <div>
                                                <label for="contactNumber">Contact Number:</label>
                                                <input type="text" name="contact_Number" class="form-control" placeholder="Mobile Phone" value="<?php echo htmlspecialchars($user['contact_number'] ?? ''); ?>">
                                            </div>
                                            <div>
                                                <label for="faxNumber">Fax Number:</label>
                                                <input type="text" name="fax_number" class="form-control" placeholder="Fax Number" value="<?php echo htmlspecialchars($user['fax_number'] ?? ''); ?>">
                                            </div>
                                            <div>
                                                <label for="streetAddress">Street Address:</label>
                                                <input type="text" name="street_address" class="form-control" placeholder="Street Address" value="<?php echo htmlspecialchars($user['street_address'] ?? ''); ?>">
                                            </div>
                                            <div>
                                                <label for="city">City:</label>
                                                <input type="text" name="city" class="form-control" placeholder="City" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
                                            </div>
                                            <div>
                                                <label for="state">State/Province:</label>
                                                <input type="text" name="state_province" class="form-control" placeholder="State/Province" value="<?php echo htmlspecialchars($user['state_province'] ?? ''); ?>">
                                            </div>
                                            <div>
                                                <label for="zipCode">Zip/Postal Code:</label>
                                                <input type="text" name="zip_postal_code" class="form-control" placeholder="Zip/Postal Code" value="<?php echo htmlspecialchars($user['zip_postal_code'] ?? ''); ?>">
                                            </div>
                                            <div>
                                                <label for="country">Country/Region:</label>
                                                <input type="text" name="country_region" class="form-control" placeholder="Country/Region" value="<?php echo htmlspecialchars($user['country_region'] ?? ''); ?>">
                                            </div>
                                            <div>
                                                <label for="email">Email:</label>
                                                <input type="email" name="email" class="form-control" placeholder="Email Address" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                                            </div>
                                            <div class="notes">
                                                <label for="notes">Notes:</label>
                                                <textarea name="notes" class="form-control"><?php echo htmlspecialchars($user['notes'] ?? ''); ?></textarea>
                                            </div>
                                            <div class="btn">
                                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                        <div class="profile-pic">
    <?php if (!empty($user['profile_picture'])): ?>
        <!-- If the user has a profile picture, display it -->
        <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture">
    <?php else: ?>
        <!-- If the user does not have a profile picture, display a default image -->
        <img src="default_profile_picture.jpg" alt="Default Profile Picture">
    <?php endif; ?>
</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
</body>
</html>
