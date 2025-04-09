<?php
session_start();
include("../connection.php"); // Ensure database connection is included

// Ensure the database connection is established
if (!$connection) {
    die("❌ Database Connection Failed: " . mysqli_connect_error());
}

// Check if form is submitted
if (isset($_POST['assign'])) {
    $fid = $_POST['fid']; // Food donation ID
    $delivery_id = $_POST['delivery_id']; // Selected delivery person ID

    if (!empty($fid) && !empty($delivery_id)) {
        // Assign the donation to the selected delivery person
        $update_query = "UPDATE food_donations SET assigned_to = ? WHERE Fid = ?";
        $stmt = mysqli_prepare($connection, $update_query);
        mysqli_stmt_bind_param($stmt, "ii", $delivery_id, $fid);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('✅ Order assigned successfully!'); window.location='unassigned_donations.php';</script>";
        } else {
            echo "<script>alert('❌ Failed to assign order: " . mysqli_error($connection) . "');</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('❌ Please select a delivery person.');</script>";
    }
}
?>
