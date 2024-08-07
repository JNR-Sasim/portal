<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: sign-in.php');
    exit();
}

// Establish database connection
$server = "localhost";
$username = "root";
$password = "";
$database = "sasimfup_helpdesk_cms";

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in user's ID
$loggedInUserId = $_SESSION['user_id'];

// Define the function to get user name by ID
function getUserNameById($conn, $userId) {
    $sql = "SELECT first_name, last_name FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    return $user ? $user['first_name'] . ' ' . $user['last_name'] : 'Unknown User';
}

// Get the logged-in user's name
$loggedInUserName = getUserNameById($conn, $loggedInUserId);

// Fetch the latest ticket created by the logged-in user
$sql = "SELECT t.id, t.equip_id, t.subject, t.assigned_to_id, t.department_id, s.name AS status_name, t.priorities_id, t.file_location, t.requested_by_id, t.closed_by, t.created_on, t.comment 
        FROM ticket t
        INNER JOIN status s ON t.status_id = s.id
        WHERE t.requested_by_id = ? 
        ORDER BY t.created_on DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $loggedInUserId);
$stmt->execute();
$result = $stmt->get_result();

// Check if the query was successful
if (!$result) {
    die("Error executing query: " . $conn->error);
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

        .btn-view {
            background-color: yellow; /* Make the button yellow */
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="user-profile.php"><i class="fas fa-user"></i> Users</a></li>
               
            </ul>
        </nav>

        <div class="main">
            <header>
                <h1>Welcome, <?php echo htmlspecialchars($loggedInUserName); ?></h1>
            </header>

            <main class="content">
                <div class="container">
                    <div class="card">
                        <div class="card-header">
                            <a class="mb-3 tablink btn btn-secondary">Ticket</a>
                            <a href="New Ticket.php" class="mb-3 tablink btn btn-success">New Ticket</a>
                        </div>
                        <div class="card-body">
                            <div class="table-bordered table-responsive text-center" style="overflow-x:auto;">
                                <table class="table table-bordered" id="ticket-list">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Requester</th>
                                            <th>Ticket Summary</th>
                                            <th>Status</th>
                                            <th>Assignee</th>
                                            <th>Created On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                $requesterName = getUserNameById($conn, $row["requested_by_id"]);
                                                $assigneeName = getUserNameById($conn, $row["assigned_to_id"]);
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                                                echo "<td>" . htmlspecialchars($requesterName) . "</td>";
                                                echo "<td>" . htmlspecialchars($row["subject"]) . "</td>";
                                                echo "<td><span class='badge bg-danger'>" . htmlspecialchars($row["status_name"]) . "</span></td>";
                                                echo "<td>" . htmlspecialchars($assigneeName) . "</td>";
                                                echo "<td>" . htmlspecialchars($row["created_on"]) . "</td>";
                                                echo "<td>
                                                <a href='view.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-info btn-xs' title='View details'><i class='fas fa-eye'></i></a>
                                                <a href='edit_ticket.php?id=" . htmlspecialchars($row["id"]) . "' class='btn btn-info btn-xs' title='Edit details'><i class='fas fa-edit'></i></a>
                                                <button type='button' class='btn btn-danger btn-xs delete' title='Delete'><i class='fas fa-trash-alt'></i></button>
                                              </td>";
                                        
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='7'>No tickets found</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
