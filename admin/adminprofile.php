<?php
session_start();
include("connect.php"); 

// Redirect if not logged in
if (!isset($_SESSION['name'])) {
    header("location:signin.php");
    exit();
}

// Ensure `Aid` is set
if (!isset($_SESSION['Aid'])) {
    die("Session variable 'Aid' is not set.");
}

$id = $_SESSION['Aid'];

// Use prepared statements to prevent SQL Injection
$sql = "SELECT Fid, name, food, type, category, phoneno, date, address, quantity FROM food_donations WHERE assigned_to = ?";
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

// Fetch data
$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .table-container {
            width: 90%;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #06C167;
            color: white;
        }
        .no-data {
            text-align: center;
            font-size: 18px;
            color: red;
            padding: 20px;
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
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="analytics.php">Analytics</a></li>
            <li><a href="donate.php">Donates</a></li>
            <li><a href="feedback.php">Feedbacks</a></li>
            <li><a href="#">Profile</a></li>
        </ul>
    </div>
</nav>

<section class="dashboard">
    <div class="top">
        <p class="logo">Your <b style="color: #06C167;">History</b></p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Food</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Phone No</th>
                    <th>Date/Time</th>
                    <th>Address</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data)) : ?>
                    <tr><td colspan="9" class="no-data">No donation history found.</td></tr>
                <?php else : ?>
                    <?php foreach ($data as $row) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Fid']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['food']) ?></td>
                            <td><?= htmlspecialchars($row['type']) ?></td>
                            <td><?= htmlspecialchars($row['category']) ?></td>
                            <td><?= htmlspecialchars($row['phoneno']) ?></td>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                            <td><?= htmlspecialchars($row['address']) ?></td>
                            <td><?= htmlspecialchars($row['quantity']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<script src="admin.js"></script>
</body>
</html>
