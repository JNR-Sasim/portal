<?php
date_default_timezone_set('Africa/Johannesburg');

if (!isset($_SESSION)) { 
    session_start(); 
} 

$errors = array(); 
$success = array();
$_SESSION['success'] = "";

class Connection {
    private $server = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "sasimfup_helpdesk_cms";

    public function getConn() {
        global $errors;
        $conn = new mysqli($this->server, $this->username, $this->password, $this->database);
        if ($conn->connect_error) {
            array_push($errors, 'Database Connection Error: ' . $conn->connect_error);
            return null;
        }
        return $conn;
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form fields are set
    if (isset($_POST['password'], $_POST['first_Name'], $_POST['second_Name'], $_POST['last_Name'], $_POST['role_id'], $_POST['email'], $_POST['contact_Number'], $_POST['company_id'], $_POST['job_title'], $_POST['business_phone'], $_POST['fax_number'], $_POST['street_address'], $_POST['city'], $_POST['state_province'], $_POST['zip_postal_code'], $_POST['country_region'])) {
        $connObj = new Connection();
        $conn = $connObj->getConn();

        if ($conn) {
            // Prepare the SQL statement
            $stmt = $conn->prepare("
                INSERT INTO user (first_Name, second_Name, last_Name, role_id, email, password, contact_Number, company_id, job_title, business_phone, fax_number, street_address, city, state_province, zip_postal_code, country_region, createdOn) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            if ($stmt) {
                // Hash the password using MD5
                $password = $_POST['password'];
                if (empty($password)) {
                    $errors[] = "Password cannot be empty";
                } else {
                    $hashed_password = md5($password);
                }

                // Set parameters
                $first_Name = $_POST['first_Name'];
                $second_Name = $_POST['second_Name'];
                $last_Name = $_POST['last_Name'];
                $role_id = $_POST['role_id'];
                $email = $_POST['email'];
                $contact_Number = $_POST['contact_Number'];
                $company_id = $_POST['company_id'];
                $job_title = $_POST['job_title'];
                $business_phone = $_POST['business_phone'];
                $fax_number = $_POST['fax_number'];
                $street_address = $_POST['street_address'];
                $city = $_POST['city'];
                $state_province = $_POST['state_province'];
                $zip_postal_code = $_POST['zip_postal_code'];
                $country_region = $_POST['country_region'];
                $createdOn = date('Y-m-d H:i:s'); // Current timestamp in 'YYYY-MM-DD HH:MM:SS' format

                // Bind parameters
                $stmt->bind_param("sssssisssssssssss", $first_Name, $second_Name, $last_Name, $role_id, $email, $hashed_password, $contact_Number, $company_id, $job_title, $business_phone, $fax_number, $street_address, $city, $state_province, $zip_postal_code, $country_region, $createdOn);

                // Execute the statement
                if ($stmt->execute()) {
                    $_SESSION['success'] = "User created successfully!";
                } else {
                    array_push($errors, "Error: " . $stmt->error);
                }

                $stmt->close();
            } else {
                array_push($errors, "Statement preparation failed: " . $conn->error);
            }

            $conn->close();
        } else {
            array_push($errors, "Database connection failed");
        }
    } else {
        array_push($errors, "Form fields missing");
    }

    // Redirect based on the result
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header("Location: sign-in.php");
    } else {
        header("Location: sign-in.php");
    }
    exit();
}
?>
