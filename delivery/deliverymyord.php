<?php
ob_start();
include '../connection.php';
include("connect.php");

// Check if the session is set, otherwise redirect to login
if (!isset($_SESSION['name']) || empty($_SESSION['name'])) {
    header("location:deliverylogin.php");
    exit();
}

$name = $_SESSION['name'];
$id = $_SESSION['Did'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Food Donate</title>
    <link rel="stylesheet" href="delivery.css">
    <link rel="stylesheet" href="../home.css">
</head>
<body>

<header>
    <div class="logo">Food <b style="color: #06C167;">Donate</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="delivery.php">Home</a></li>
            <li><a href="openmap.php">Map</a></li>
            <li><a href="deliverymyord.php" class="active">My Orders</a></li>
        </ul>
    </nav>
</header>

<script>
    document.querySelector(".hamburger").onclick = function() {
        document.querySelector(".nav-bar").classList.toggle("active");
    };
</script>

<style>
    .itm {
        background-color: white;
        display: grid;
    }
    .itm img {
        width: 400px;
        height: 400px;
        margin: auto;
    }
    p {
        text-align: center;
        font-size: 28px;
        color: black;
    }
    @media (max-width: 767px) {
        .itm img {
            width: 350px;
            height: 350px;
        }
    }
</style>

<div class="itm">
    <img src="../img/delivery.gif" alt="Delivery" width="400" height="400">
</div>

<div class="get">
    <?php
    // Fetch orders assigned to the delivery person
    $sql = "SELECT fd.Fid, fd.name, fd.phoneno, fd.date, fd.address AS From_address, 
                   ad.name AS delivery_person_name, ad.address AS To_address
            FROM food_donations fd
            LEFT JOIN admin ad ON fd.assigned_to = ad.Aid 
            WHERE fd.assigned_to = '$id'";

    $result = mysqli_query($connection, $sql);

    // Check for errors
    if (!$result) {
        die("Error executing query: " . mysqli_error($connection));
    }

    // Fetch data into an array
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Update assigned order when a request is made
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['delivery_person_id'])) {
        $order_id = $_POST['order_id'];
        $delivery_person_id = $_POST['delivery_person_id'];

        $update_sql = "UPDATE food_donations SET delivery_by = '$delivery_person_id' WHERE Fid = '$order_id'";
        $update_result = mysqli_query($connection, $update_sql);

        if (!$update_result) {
            die("Error assigning order: " . mysqli_error($connection));
        }

        // Refresh the page to prevent duplicate submission
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
    }
    ?>

    <div class="log">
        <a href="delivery.php">Take Orders</a>
        <p>Orders Assigned to You</p>
    </div>

    <div class="table-container">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Phone No</th>
                        <th>Date/Time</th>
                        <th>Pickup Address</th>
                        <th>Delivery Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data as $row) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['phoneno']) ?></td>
                            <td><?= htmlspecialchars($row['date']) ?></td>
                            <td><?= htmlspecialchars($row['From_address']) ?></td>
                            <td><?= htmlspecialchars($row['To_address']) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
