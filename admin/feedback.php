<?php
session_start();
include '../connection.php'; // Use only this connection file

// Redirect to login if admin is not logged in
if (!isset($_SESSION['name']) || empty($_SESSION['name'])) {
    header("location:signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admin.css">
    <title>Admin Dashboard - Feedbacks</title> 
</head>
<body>
    <nav>
        <div class="logo-name">
            <span class="logo_name">ADMIN</span>
        </div>
        <div class="menu-items">
            <ul class="nav-links">
                <li><a href="admin.php"><i class="uil uil-estate"></i> Dashboard</a></li>
                <li><a href="analytics.php"><i class="uil uil-chart"></i> Analytics</a></li>
                <li><a href="donate.php"><i class="uil uil-heart"></i> Donations</a></li>
                <li><a href="feedback.php" class="active"><i class="uil uil-comments"></i> Feedbacks</a></li>
                <li><a href="adminprofile.php"><i class="uil uil-user"></i> Profile</a></li>
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

        <div class="activity">
            <div class="table-container">
                <div class="table-wrapper">
                    <h2>Feedbacks</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM user_feedback";
                            $result = mysqli_query($connection, $query);
                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr><td>{$row['name']}</td><td>{$row['email']}</td><td>{$row['message']}</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3'>No feedbacks available</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script src="admin.js"></script>
</body>
</html>
