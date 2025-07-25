<?php
session_start();
include 'connection.php';

// Enable error reporting to debug issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if database connection is working
if (!$connection) {
    die("❌ Database Connection Failed: " . mysqli_connect_error());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Validate inputs
    if (empty($name) || empty($email) || empty($message)) {
        die("❌ All fields are required!");
    }

    // Sanitize inputs
    $sanitized_name = mysqli_real_escape_string($connection, $name);
    $sanitized_email = mysqli_real_escape_string($connection, $email);
    $sanitized_message = mysqli_real_escape_string($connection, $message);

    // Insert query
    $query = "INSERT INTO user_feedback(name, email, message) VALUES('$sanitized_name', '$sanitized_email', '$sanitized_message')";
    $query_run = mysqli_query($connection, $query);

    // Check if query executed successfully
    if ($query_run) {
        echo "<script>
                alert('✅ Feedback submitted successfully!');
                window.location.href='contact.html';
              </script>";
        exit();
    } else {
        die("❌ Query Failed: " . mysqli_error($connection));
    }
}
?>
