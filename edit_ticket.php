<?php
// Include database connection file
include_once "server/connection.db.php";

// Initialize variables
$ticket_id = $_GET['id']; 

// Fetch ticket details from the database
$query = "SELECT * FROM ticket WHERE id = '$ticket_id'";
$result = mysqli_query($conn, $query);

if (!$result) {
   
    $error_message = "Error fetching ticket details: " . mysqli_error($conn);
} else {
    // Check if ticket exists
    if (mysqli_num_rows($result) > 0) {
        // Ticket found, fetch its details
        $ticket = mysqli_fetch_assoc($result);

        // Fetch dropdown options from the database (assignees, departments, statuses, priorities)
        $assignees_query = "SELECT * FROM user";
        $assignees_result = mysqli_query($conn, $assignees_query);
        $assignees = mysqli_fetch_all($assignees_result, MYSQLI_ASSOC);

        $departments_query = "SELECT * FROM department";
        $departments_result = mysqli_query($conn, $departments_query);
        $departments = mysqli_fetch_all($departments_result, MYSQLI_ASSOC);

        $statuses_query = "SELECT * FROM status";
        $statuses_result = mysqli_query($conn, $statuses_query);
        $statuses = mysqli_fetch_all($statuses_result, MYSQLI_ASSOC);

        $priorities_query = "SELECT * FROM priorities";
        $priorities_result = mysqli_query($conn, $priorities_query);
        $priorities = mysqli_fetch_all($priorities_result, MYSQLI_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ticket</title>
    <link rel="stylesheet" href="app.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
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
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($ticket['id']); ?>">					
                    <div class="card-body">
                        <label>Equipment</label>										
                        <select name="equip_id" class="form-control">
                            <option value="">--Select Equipment--</option>
                            <option value="1" <?php if ($ticket['equip_id'] == 1) echo 'selected'; ?>>Laptops</option>
                            <option value="2" <?php if ($ticket['equip_id'] == 2) echo 'selected'; ?>></option>
                            <option value="3" <?php if ($ticket['equip_id'] == 3) echo 'selected'; ?>></option>
                        </select>
                    </div>
                    <div class="card-body">
                        <label>Subject</label>
                        <input type="text" name="subject" class="form-control" placeholder="Subject" value="<?php echo htmlspecialchars($ticket['subject']); ?>">
                    </div>
                    <div class="card-body">
                        <label>Assignee</label>
                        <select name="assigned_to_id" class="form-control">
                            <option value="">--Select Assignee--</option>
                            <?php foreach ($assignees as $assignee): ?>
                                <option value="<?php echo htmlspecialchars($assignee['id']); ?>" <?php if ($ticket['assigned_to_id'] == $assignee['id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($assignee['first_name']) . ' ' . htmlspecialchars($assignee['last_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="card-body">
                        <label>Department</label>
                        <select name="department_id" class="form-control">
                            <option value="">--Select Department--</option>
                            <?php foreach ($departments as $department): ?>
                                <option value="<?php echo htmlspecialchars($department['id']); ?>" <?php if ($ticket['department_id'] == $department['id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($department['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="card-body">
                        <label>Status</label>
                        <select name="status_id" class="form-control">
                            <option value="">--Select Status--</option>
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?php echo htmlspecialchars($status['id']); ?>" <?php if ($ticket['status_id'] == $status['id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($status['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="card-body">
                        <label>Priority</label>
                        <select name="priority_id" class="form-control">
                            <option value="">--Select Priority--</option>
                            <?php foreach ($priorities as $priority): ?>
                                <option value="<?php echo htmlspecialchars($priority['id']); ?>" <?php if ($ticket['priorities_id'] == $priority['id']) echo 'selected'; ?>>
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
                        <textarea class="form-control" name="comment" rows="4" placeholder=" Comment"><?php echo htmlspecialchars($ticket['comment']); ?></textarea>
                    </div>
                    <!-- Additional hidden fields -->
                    <input type="hidden" name="file_location" value="<?php echo htmlspecialchars($ticket['file_location']); ?>">
                    <input type="hidden" name="requested_by_id" value="<?php echo htmlspecialchars($ticket['requested_by_id']); ?>">
                    <input type="hidden" name="closed_by" value="<?php echo htmlspecialchars($ticket['closed_by']); ?>">
                    <input type="hidden" name="created_on" value="<?php echo htmlspecialchars($ticket['created_on']); ?>">
                    <div class="card-body">											
                        <button type="submit" name="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
