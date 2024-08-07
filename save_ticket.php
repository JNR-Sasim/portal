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

    public function getConn(){
        $conn = new mysqli($this->server, $this->username, $this->password, $this->database);
        if ($conn->connect_error) {
            die('Database Connection Error: ' . $conn->connect_error);
        }
        return $conn;
    }
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $connObj = new Connection();
    $conn = $connObj->getConn();

    if ($conn) {
        // Assuming the user's ID is stored in the session
        if (isset($_SESSION['user_id'])) {
            $requested_by_id = $_SESSION['user_id'];
        } else {
            array_push($errors, "User is not logged in.");
            header("Location: index.php");
            exit();
        }

        // Check if an ID is provided to determine if we're updating an existing ticket
        $ticket_id = isset($_POST['id']) ? $_POST['id'] : null;

        // Prepare the SQL query based on whether we're updating or inserting
        if ($ticket_id) {
            // Update existing ticket
            $stmt = $conn->prepare("UPDATE ticket SET equip_id = ?, subject = ?, assigned_to_id = ?, department_id = ?, status_id = ?, priorities_id = ?, file_location = ?, requested_by_id = ?, closed_by = ?, created_on = ?, comment = ? WHERE id = ?");
        } else {
            // Insert new ticket
            $stmt = $conn->prepare("INSERT INTO ticket (equip_id, subject, assigned_to_id, department_id, status_id, priorities_id, file_location, requested_by_id, closed_by, created_on, comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        }

        if (!$stmt) {
            array_push($errors, "Error preparing statement: " . $conn->error);
        } else {
            // Set parameters
            $equip_id = $_POST['equip_id'];
            $subject = $_POST['subject'];
            $assigned_to_id = $_POST['assigned_to_id'];
            $department_id = $_POST['department_id'];
            $status_id = $_POST['status_id'];
            $priorities_id = $_POST['priorities_id'];
            $file_location = ""; // Handle file upload logic if necessary
            $closed_by = null; // Set closed_by to null initially
            $created_on = date('Y-m-d H:i:s');
            $comment = $_POST['comment']; // Handle this value if necessary

            if ($ticket_id) {
                // Bind parameters to the update statement
                $stmt->bind_param("sssssssssssi", 
                    $equip_id, 
                    $subject, 
                    $assigned_to_id, 
                    $department_id, 
                    $status_id, 
                    $priorities_id, 
                    $file_location, 
                    $requested_by_id, 
                    $closed_by, 
                    $created_on, 
                    $comment, 
                    $ticket_id
                );
            } else {
                // Bind parameters to the insert statement
                $stmt->bind_param("sssssssssss", 
                    $equip_id, 
                    $subject, 
                    $assigned_to_id, 
                    $department_id, 
                    $status_id, 
                    $priorities_id, 
                    $file_location, 
                    $requested_by_id, 
                    $closed_by, 
                    $created_on, 
                    $comment
                );
            }

            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['success'] = $ticket_id ? "Ticket updated successfully!" : "Ticket created successfully!";
            } else {
                array_push($errors, "Error: " . $stmt->error);
            }

            // Close statement
            $stmt->close();
        }

        // Close connection
        $conn->close();
    } else {
        array_push($errors, "Database connection failed");
    }

    // If there are errors, store them in the session for later display
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
    }
}

// Redirect back to the form page
header("Location: index.php");
exit();
?>
