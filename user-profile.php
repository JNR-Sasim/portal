<?php
// Include the database connection file
include 'server/connection.db.php';

// Initialize error array
$errors = [];

// Fetch user role data from the user role table
$sql_roles = "SELECT id, name FROM user_role";
$result_roles = $conn->query($sql_roles);

// Check if query was successful
if ($result_roles && $result_roles->num_rows > 0) {
    $roles = [];
    while ($row_role = $result_roles->fetch_assoc()) {
        $roles[] = $row_role;
    }
} else {
    $errors[] = "Error fetching user roles from database";
    $roles = []; // Empty array if no data found
}
// Fetch company data from the company table
$sql_companies = "SELECT id, name FROM company";
$result_companies = $conn->query($sql_companies);

// Check if query was successful
if ($result_companies && $result_companies->num_rows > 0) {
    $companies = [];
    while ($row_company = $result_companies->fetch_assoc()) {
        $companies[] = $row_company;
    }
} else {
    $errors[] = "Error fetching companies from database";
    $companies = []; // Empty array if no data found
}

// Check if form is submitted
if (isset($_POST['Save'])) {
    // Retrieve form data
    $first_Name = $_POST['first_Name'];
    $second_Name = $_POST['second_Name'];
    $last_Name = $_POST['last_Name'];
    $role_id = $_POST['role_id']; 
    $manager_id = $_POST['manager_id'];
    $company_id = $_POST['company_id'];
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

    // Hash the password
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

   

    echo "First Name: $first_Name<br>";
    echo "Second Name: $second_Name<br>";
    echo "Last Name: $last_Name<br>";
    echo "Role ID: $role_id<br>";
    echo "Company_id: $company_id<br>";
    echo "Job Title: $job_title<br>";
    echo "Business Phone: $business_phone<br>";
    echo "Contact Number: $contact_Number<br>";
    echo "Fax Number: $fax_number<br>";
    echo "Street Address: $street_address<br>";
    echo "City: $city<br>";
    echo "State/Province: $state_province<br>";
    echo "Zip/Postal Code: $zip_postal_code<br>";
    echo "Country/Region: $country_region<br>";
    echo "Email: $email<br>";
    echo "Notes: $notes<br>";
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
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
            align-items: flex-start;
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
                                    <h5 class="card-title mb-0">Create Profile</h5>
                                    <!-- Edit Profile Button -->
                                    <button class="btn btn-secondary" onclick="window.location.href='edit -profile.php'">Edit Profile</button>
                                </div>

                                <div class="card-body h-100">
                                    <div class="profile-container">
                                    <form action="save_profile.php" method="post" enctype="multipart/form-data" class="profile-form">
    <div>
        <label for="firstName">First Name:</label>
        <input type="text" name="first_Name" class="form-control" placeholder="First Name">
    </div>
    <div>
        <label for="secondName">Second Name:</label>
        <input type="text" name="second_Name" class="form-control" placeholder="Second Name">
    </div>
    <div>
        <label for="lastName">Last Name:</label>
        <input type="text" name="last_Name" class="form-control" placeholder="Last Name">
    </div>
    <div>
        <label for="role_id">Role ID:</label>
        <select name="role_id" class="form-control">
            <option value="" disabled selected>Select Role</option>
            <?php foreach ($roles as $role): ?>
                <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label for="password">Password:</label>
        <input type="password" name="password" class="form-control" placeholder="Password">
    </div>
    <div>
        <label for="company_id">Company:</label>
        <select name="company_id" class="form-control">
            <option value="" disabled selected>Select Company</option>
            <?php foreach ($companies as $company): ?>
                <option value="<?php echo $company['id']; ?>"><?php echo $company['name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label for="jobTitle">Job Title:</label>
        <input type="text" name="job_title" class="form-control" placeholder="Job Title">
    </div>
    <div>
        <label for="businessPhone">Business Phone:</label>
        <input type="text" name="business_phone" class="form-control" placeholder="Business Phone">
    </div>
    <div>
        <label for="contactNumber">Contact Number:</label>
        <input type="text" name="contact_Number" class="form-control" placeholder="Mobile Phone">
    </div>
    <div>
        <label for="faxNumber">Fax Number:</label>
        <input type="text" name="fax_number" class="form-control" placeholder="Fax Number">
    </div>
    <div>
        <label for="streetAddress">Street Address:</label>
        <input type="text" name="street_address" class="form-control" placeholder="Street Address">
    </div>
    <div>
        <label for="city">City:</label>
        <input type="text" name="city" class="form-control" placeholder="City">
    </div>
    <div>
        <label for="stateProvince">State/Province:</label>
        <input type="text" name="state_province" class="form-control" placeholder="State/Province">
    </div>
    <div>
        <label for="zipPostalCode">Zip/Postal Code:</label>
        <input type="text" name="zip_postal_code" class="form-control" placeholder="Zip/Postal Code">
    </div>
    <div>
        <label for="countryRegion">Country/Region:</label>
        <input type="text" name="country_region" class="form-control" placeholder="Country/Region">
    </div>
    <div>
        <label for="email">Email:</label>
        <input type="email" name="email" class="form-control" placeholder="Email">
    </div>
    <div class="notes">
        <label for="notes">Notes:</label>
        <textarea name="notes" class="form-control" placeholder="Notes"></textarea>
    </div>
    <div class="btn">
        <input type="submit" name="Save" value="Save Profile" class="btn btn-primary">
    </div>
</form>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-xl-3">
                            <div class="profile-pic-container">
                                <div class="profile-pic">
                                    <img src="uploads/vecteezy_profile-icon-design-vector_5544718.jpg" alt="Default Profile Picture" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                                <div>
                                                <label for="profilePicture">Profile Picture:</label>
                                                <input type="file" name="profile_picture" class="form-control-file">
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
