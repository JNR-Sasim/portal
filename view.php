<?php
// Include database connection file
include_once "server/connection.db.php";

// Initialize an array to hold activities
$activities = [];

// Check if ticket ID is provided in the URL
if (isset($_GET['id'])) {
    // Sanitize ticket ID
    $ticket_id = mysqli_real_escape_string($conn, $_GET['id']);

    // Fetch ticket details from the database
    $query = "SELECT * FROM ticket WHERE id = '$ticket_id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) > 0) {
        $ticket = mysqli_fetch_assoc($result);
        
        // Fetch names for department, status, priority, requester, assignee, and equipment
        $department_query = "SELECT name FROM department WHERE id = '{$ticket['department_id']}'";
        $department_result = mysqli_query($conn, $department_query);
        $department_name = mysqli_fetch_assoc($department_result)['name'];

        $status_query = "SELECT name FROM status WHERE id = '{$ticket['status_id']}'";
        $status_result = mysqli_query($conn, $status_query);
        $status_name = mysqli_fetch_assoc($status_result)['name'];

        $priority_query = "SELECT name FROM priorities WHERE id = '{$ticket['priorities_id']}'";
        $priority_result = mysqli_query($conn, $priority_query);
        $priority_name = mysqli_fetch_assoc($priority_result)['name'];

        $requester_query = "SELECT first_name FROM user WHERE id = '{$ticket['requested_by_id']}'";
        $requester_result = mysqli_query($conn, $requester_query);
        $requester_name = mysqli_fetch_assoc($requester_result)['first_name'];

        $assignee_query = "SELECT first_name FROM user WHERE id = '{$ticket['assigned_to_id']}'";
        $assignee_result = mysqli_query($conn, $assignee_query);
        $assignee_name = mysqli_fetch_assoc($assignee_result)['first_name'];

        $equipment_query = "SELECT name FROM equipment WHERE equip_id = '{$ticket['equip_id']}'";
        $equipment_result = mysqli_query($conn, $equipment_query);
        $equipment_name = mysqli_fetch_assoc($equipment_result)['name'];
    } else {
        echo "Ticket not found!";
        exit();
    }

    // Fetch ticket activities from the database
    $activities_query = "SELECT * FROM ticket_replies WHERE ticket_id = '$ticket_id' ORDER BY created_on DESC";
    $activities_result = mysqli_query($conn, $activities_query);

    if (!$activities_result) {
        die("Activities query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($activities_result) > 0) {
        while ($activity = mysqli_fetch_assoc($activities_result)) {
            $activities[] = $activity;
        }
    } else {
        echo "No activities found for this ticket.";
    }

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="app.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    <title>Ticket Details</title>
   
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
            <main class="content">
                <div class="container-fluid p-0">
                    <div class="mb-3">
                        <h1 class="h3 d-inline align-middle">Ticket Details</h1>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xl-3">
                            <div class="card mb-3">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Ticket Details</h5>
                                </div>
                                <hr class="my-0" />
                                <div class="card-body" id="ticketDetails">
                                    <h5 class="h6 card-title">About</h5>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-1">Subject: <?php echo htmlspecialchars($ticket['subject']); ?></li>
                                        <li class="mb-1">Department: <?php echo htmlspecialchars($department_name); ?></li>
                                        <li class="mb-1">Status: <?php echo htmlspecialchars($status_name); ?></li>
                                        <li class="mb-1">Priority: <?php echo htmlspecialchars($priority_name); ?></li>
                                        <li class="mb-1">Requester: <?php echo htmlspecialchars($requester_name); ?></li>
                                        <li class="mb-1">Assignee: <?php echo htmlspecialchars($assignee_name); ?></li>
                                        <li class="mb-1">Closed By: <?php echo htmlspecialchars($ticket['closed_by']); ?></li>
                                        <li class="mb-1">Closed At: <?php echo htmlspecialchars($ticket['closed_at']); ?></li>
                                        <li class="mb-1">Attachment: <?php echo htmlspecialchars($ticket['file_location']); ?></li>
                                        <li class="mb-1">Equipment: <?php echo htmlspecialchars($equipment_name); ?></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Ticket Activities Section -->
                        <div class="col-md-8 col-xl-6">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Ticket Activities</h5>
        </div>
        <div class="card-body h-100" id="ticketActivities">
            <?php if (count($activities) > 0): ?>
                <?php foreach ($activities as $activity): ?>
                    <div class="d-flex align-items-start">
                        <div class="flex-grow-1">
                            <small class="float-end text-navy"><?php echo htmlspecialchars($activity['created_on']); ?></small>
                            <span class="badge bg-info"><?php echo htmlspecialchars($status_name); ?></span>
                            <strong>
                                <?php 
                                    // Fetch user name based on user ID
                                    $user_id = $activity['user_id'];
                                    $user_query = "SELECT first_name FROM user WHERE id = '$user_id'";
                                    $user_result = mysqli_query($conn, $user_query);
                                    if ($user_result && mysqli_num_rows($user_result) > 0) {
                                        $user_data = mysqli_fetch_assoc($user_result);
                                        echo htmlspecialchars($user_data['first_name']);
                                    } else {
                                        echo "Unknown User";
                                    }
                                ?>
                            </strong><br />
                            <div class="border text-muted p-2 mt-1">
                                <?php echo $activity['message']; ?>
                            </div>
                            <?php if ($activity['file_location']): ?>
                                <div class="text-muted p-2 mt-1"><a href="<?php echo htmlspecialchars($activity['file_location']); ?>" target="_blank">Attachment</a></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr />
                <?php endforeach; ?>
            <?php else: ?>
                <p>No activities found for this ticket</p>
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
