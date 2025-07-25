<?php
session_start();
ob_start();
include("../connection.php");

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

if (!isset($_SESSION['name']) || empty($_SESSION['name'])) {
    header("Location: signin.php");
    exit();
}

if (!isset($_SESSION['location'])) {
    $_SESSION['location'] = 'Unknown';
}
$loc = $_SESSION['location'];

// Function to get counts safely
function get_count($connection, $query) {
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query Failed: " . mysqli_error($connection)); // Error handling
    }
    $row = mysqli_fetch_assoc($result);
    return isset($row['count']) ? (int)$row['count'] : 0;
}

// Fetch counts
$total_users = get_count($connection, "SELECT COUNT(*) AS count FROM login");
$total_feedbacks = get_count($connection, "SELECT COUNT(*) AS count FROM user_feedback");
$total_donations = get_count($connection, "SELECT COUNT(*) AS count FROM food_donations");

// Debugging: Print values to check correctness
error_log("Total Users: " . $total_users);
error_log("Total Feedbacks: " . $total_feedbacks);
error_log("Total Donations: " . $total_donations);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admin.css">
    <title>Admin Dashboard Panel</title>
    <style>
        select {
            width: 100%;
            max-width: 200px;
            padding: 5px;
            font-size: 16px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .assign-btn {
            background-color: green;
            color: white;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }

        .assign-btn:hover {
            background-color: darkgreen;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        th, td {
            padding: 10px;
            border: 1px solid black;
        }
    </style>
</head>
<body>

    <nav>
        <div class="logo-name">
            <span class="logo_name">ADMIN</span>
        </div>
        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="analytics.php"><i class="uil uil-chart"></i> Analytics</a></li>
                <li><a href="donate.php"><i class="uil uil-heart"></i> Donations</a></li>
                <li><a href="feedback.php"><i class="uil uil-comments"></i> Feedbacks</a></li>
                <li><a href="adminprofile.php"><i class="uil uil-user"></i> Profile</a></li>
                <li><a href="unassigned_donations.php"><i class="uil uil-list-ul"></i> Unassigned Donations</a></li>
            </ul>
            <ul class="logout-mode">
                <li><a href="../logout.php"><i class="uil uil-signout"></i> Logout</a></li>
            </ul>
        </div>
    </nav>

    <section class="dashboard">
        <div class="top">
            <p class="logo">Food <b style="color: #06C167;">Donate</b></p>
        </div>

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class="uil uil-tachometer-fast-alt"></i>
                    <span class="text">Dashboard</span>
                </div>

                <div class="boxes">
                    <div class="box box1">
                        <i class="uil uil-user"></i>
                        <span class="text">Total Users</span>
                        <span class='number'><?php echo $total_users; ?></span>
                    </div>
                    <div class="box box2">
                        <i class="uil uil-comments"></i>
                        <span class="text">Feedbacks</span>
                        <span class='number'><?php echo $total_feedbacks; ?></span>
                    </div>
                    <div class="box box3">
                        <i class="uil uil-heart"></i>
                        <span class="text">Total Donations</span>
                        <span class='number'><?php echo $total_donations; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Debugging - Check if PHP is sending correct values
        console.log("Total Users:", <?php echo $total_users; ?>);
        console.log("Total Feedbacks:", <?php echo $total_feedbacks; ?>);
        console.log("Total Donations:", <?php echo $total_donations; ?>);

        // Check if elements exist before adding event listeners
        document.addEventListener("DOMContentLoaded", () => {
            const modeToggle = document.querySelector(".mode-toggle");
            const sidebarToggle = document.querySelector(".sidebar-toggle");
            const sidebar = document.querySelector("nav");
            const body = document.querySelector("body");

            if (!modeToggle) console.warn("⚠️ modeToggle button not found!");
            if (!sidebarToggle) console.warn("⚠️ sidebarToggle button not found!");

            let getMode = localStorage.getItem("mode");
            if (getMode === "dark") {
                body.classList.add("dark");
            }

            let getStatus = localStorage.getItem("status");
            if (getStatus === "close") {
                sidebar.classList.add("close");
            }

            if (modeToggle) {
                modeToggle.addEventListener("click", () => {
                    body.classList.toggle("dark");
                    localStorage.setItem("mode", body.classList.contains("dark") ? "dark" : "light");
                });
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener("click", () => {
                    sidebar.classList.toggle("close");
                    localStorage.setItem("status", sidebar.classList.contains("close") ? "close" : "open");
                });
            }
        });
    </script>

    <script src="admin.js"></script>
</body>
</html>
