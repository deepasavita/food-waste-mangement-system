<?php
session_start();
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include("../connection.php"); // ✅ Ensure this file exists and connects correctly

// Ensure the database connection is established
if (!$connection) {
    die("❌ Database Connection Failed: " . mysqli_connect_error());
}

// Fetch unassigned donations (fixed query)
$query = "SELECT * FROM food_donations WHERE assigned_to IS NULL OR assigned_to = 0";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("❌ Query Failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unassigned Donations</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid black;
        }
        .assign-btn {
            background-color: green;
            color: white;
            padding: 8px 15px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            border-radius: 5px;
        }
        .assign-btn:hover {
            background-color: darkgreen;
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
            <li><a href="unassigned_donations.php" class="active"><i class="uil uil-list-ul"></i> Unassigned Donations</a></li>
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
        <h2>Unassigned Donations</h2>
        <table>
            <tr>
                <th>FID</th>
                <th>Donor Name</th>
                <th>Food</th>
                <th>Quantity</th>
                <th>Location</th>
                <th>Assign To</th>
            </tr>

            <?php 
            if (mysqli_num_rows($result) > 0) { 
                while ($row = mysqli_fetch_assoc($result)) { 
                    ?>
                    <tr>
                        <td><?php echo $row['Fid']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['food']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['location']; ?></td>
                        <td>
                            <form action="assign_donation.php" method="POST">
                                <input type="hidden" name="fid" value="<?php echo $row['Fid']; ?>">
                                <select name="delivery_id" required>
                                    <option value="" disabled selected>Select a delivery person</option>
                                    <?php
                                    $delivery_query = "SELECT * FROM delivery_persons"; // ✅ FIXED
                                    $delivery_result = mysqli_query($connection, $delivery_query);

                                    // Debugging: Check if query runs correctly
                                    if (!$delivery_result) {
                                        die("❌ Query Failed: " . mysqli_error($connection));
                                    }

                                    // Debugging: Check if delivery persons exist
                                    if (mysqli_num_rows($delivery_result) == 0) {
                                        echo "<option disabled>No delivery persons found</option>";
                                    } else {
                                        while ($delivery_row = mysqli_fetch_assoc($delivery_result)) {
                                            echo "<option value='{$delivery_row['Did']}'>{$delivery_row['name']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <button type="submit" name="assign" class="assign-btn">Assign</button>
                            </form>
                        </td>
                    </tr>
                    <?php 
                } 
            } else {
                echo "<tr><td colspan='6'>No unassigned donations found.</td></tr>";
            } 
            ?>
        </table>
    </div>
</section>

<script src="admin.js"></script>
</body>
</html>
