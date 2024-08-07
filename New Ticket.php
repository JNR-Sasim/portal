<?php

include 'server/connection.db.php';

// Function to fetch assignee data from the database
function fetchAssignees($conn) {
    $sql = "SELECT id, first_name, last_name FROM user";
    $result = $conn->query($sql);
    $data = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

// Function to fetch generic data from the database
function fetchData($conn, $tableName, $fieldName) {
    $sql = "SELECT id, $fieldName FROM $tableName";
    $result = $conn->query($sql);
    $data = [];

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
}

$errors = array();
$success = array();
$_SESSION['success'] = "";

// Create an instance of the Connection class
$connection = new Connection();
// Get the database connection
$conn = $connection->getConn();

// Fetch assignee data
$assignees = fetchAssignees($conn);

// Fetch priority data
$priorities = fetchData($conn, "priorities", "name");

// Fetch department data
$departments = fetchData($conn, "department", "name");

// Fetch status data
$statuses = fetchData($conn, "status", "name");


// Close the database connection
$conn->close();

// Handle form submission
if(isset($_POST['submit'])) {

    
    // Redirect to ticket.php after saving the ticket
    header("Location:tickets.php");
    exit(); 
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
</head>
<body>
<style>
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
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="user-profile.php">Users</a></li>
                <li><a href="tickets.php">Ticket</a></li>
            </ul>
        </nav>
        <div class="main">
            <div class="card">
                <form action="save_ticket.php" method="post" enctype="multipart/form-data">						
                <div class="card-body">
                        <label>Equipment</label>
                        <input type="text" name="subject" class="form-control" placeholder="Equipment">
                    </div>
                    <div class="card-body">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" placeholder="Subject">
                    </div>
                    <div class="card-body">
    <label for="assigned_to_id">Assignee</label>
    <select id="assigned_to_id" name="assigned_to_id" class="form-control">
        <option value="">--Select Assignee--</option>
        <?php foreach ($assignees as $assignee): ?>
            <option value="<?php echo htmlspecialchars($assignee['id']); ?>">
                <?php echo htmlspecialchars($assignee['first_name']) . ' ' . htmlspecialchars($assignee['last_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="card-body">
    <label for="department_id">Department</label>
    <select id="department_id" name="department_id" class="form-control">
        <option value="">--Select Department--</option>
        <?php foreach ($departments as $department): ?>
            <option value="<?php echo htmlspecialchars($department['id']); ?>">
                <?php echo htmlspecialchars($department['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="card-body">
    <label for="status_id">Status</label>
    <select id="status_id" name="status_id" class="form-control">
        <option value="">--Select Status--</option>
        <?php foreach ($statuses as $status): ?>
            <option value="<?php echo htmlspecialchars($status['id']); ?>">
                <?php echo htmlspecialchars($status['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="card-body">
    <label for="priorities_id">Priority</label>
    <select id="priorities_id" name="priorities_id" class="form-control">
        <option value="">--Select Priority--</option>
        <?php foreach ($priorities as $priority): ?>
            <option value="<?php echo htmlspecialchars($priority['id']); ?>">
                <?php echo htmlspecialchars($priority['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

                    <div class="card-body">
                        <label>Attachment</label>	
                        <input type="file" name="image" class="form-control">
                    </div>
                    <div class="card-body">
                        <label>Comment</label>	
                        <textarea class="form-control" name="body" rows="4" placeholder="Ticket Body"></textarea>
                    </div>
                    <!-- Additional fields -->
                    <input type="hidden" name="file_location">
                    <input type="hidden" name="requested_by_id">
                    <input type="hidden" name="closed_by">
                    <input type="hidden" name="created_on">
                    <input type="hidden" name="comment">
                    <div class="card-body">											
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

