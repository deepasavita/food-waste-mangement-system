<?php
session_start();
include("../connection.php");

// Redirect if not logged in
if (!isset($_SESSION['name']) || empty($_SESSION['name'])) {
    header("location: signin.php");
    exit();
}

// Function to get count safely
function get_count($connection, $query) {
    $result = mysqli_query($connection, $query);
    if (!$result) {
        die("Query Failed: " . mysqli_error($connection));
    }
    $row = mysqli_fetch_assoc($result);
    return isset($row['count']) ? (int)$row['count'] : 0;
}

// Fetch user counts by gender
$male = get_count($connection, "SELECT COUNT(*) AS count FROM login WHERE LOWER(gender) = 'male'");
$female = get_count($connection, "SELECT COUNT(*) AS count FROM login WHERE LOWER(gender) = 'female'");

// Fetch donation locations dynamically
$location_counts = [];
$query = "SELECT LOWER(location) AS location, COUNT(*) AS count FROM food_donations GROUP BY LOWER(location)";
$result = mysqli_query($connection, $query);
while ($row = mysqli_fetch_assoc($result)) {
    $location_counts[$row['location']] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Panel</title> 
    <link rel="stylesheet" href="admin.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <style>
        /* General styling */
        .chart-container {
            width: 80%;
            height: 400px; /* Fixed height to prevent overlap */
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        .charts-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 40px; /* Adds spacing between charts */
        }

        .overview {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .box {
            width: 250px;
            height: 100px;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .box1 { background-color: #6C9BCF; color: white; }
        .box2 { background-color: #F8D568; color: black; }
        .box3 { background-color: #D4A5F0; color: black; }
    </style>
</head>
<body>

<nav>
    <div class="logo-name">
        <span class="logo_name">ADMIN</span>
    </div>
    <div class="menu-items">
        <ul class="nav-links">
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="#">Analytics</a></li>
            <li><a href="donate.php">Donates</a></li>
            <li><a href="feedback.php">Feedbacks</a></li>
            <li><a href="adminprofile.php">Profile</a></li>
        </ul>
        <ul class="logout-mode">
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<section class="dashboard">
    <div class="top">
        <p class="logo">Food <b style="color: #06C167;">Donate</b></p>
    </div>

    <div class="dash-content">
        <div class="overview">
            <div class="box box1">
                <span class="text">Total Users</span>
                <span class="number"><?php echo ($male + $female); ?></span>
            </div>
            <div class="box box2">
                <span class="text">Feedbacks</span>
                <span class="number"><?php echo get_count($connection, "SELECT COUNT(*) AS count FROM user_feedback"); ?></span>
            </div>
            <div class="box box3">
                <span class="text">Total Donates</span>
                <span class="number"><?php echo get_count($connection, "SELECT COUNT(*) AS count FROM food_donations"); ?></span>
            </div>
        </div>

        <br><br>

        <!-- Chart Wrapper for better spacing -->
        <div class="charts-wrapper">
            <!-- Gender Chart -->
            <div class="chart-container">
                <canvas id="genderChart"></canvas>
            </div>

            <!-- Donation Locations Chart -->
            <div class="chart-container">
                <canvas id="donateChart"></canvas>
            </div>
        </div>

    </div>
</section>

<script>
window.onload = function() {
    var genderChartCanvas = document.getElementById("genderChart").getContext("2d");
    var donateChartCanvas = document.getElementById("donateChart").getContext("2d");

    // Gender Chart
    new Chart(genderChartCanvas, {
        type: "bar",
        data: {
            labels: ["Male", "Female"],
            datasets: [{
                label: "Gender Distribution",
                backgroundColor: ["#06C167", "blue"],
                data: [<?php echo $male; ?>, <?php echo $female; ?>]
            }]
        },
        options: { 
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } }
        }
    });

    // Donation Locations Chart (Dynamic)
    new Chart(donateChartCanvas, {
        type: "bar",
        data: {
            labels: <?php echo json_encode(array_keys($location_counts)); ?>,
            datasets: [{
                label: "Donations by Location",
                backgroundColor: ["#06C167", "blue", "red", "orange", "purple"],
                data: <?php echo json_encode(array_values($location_counts)); ?>
            }]
        },
        options: { 
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } }
        }
    });
};
</script>

</body>
</html>
