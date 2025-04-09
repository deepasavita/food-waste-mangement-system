<?php
session_start();
include '../connection.php'; // Ensure this file connects properly

$msg = "";

// Check if form is submitted
if (isset($_POST['sign'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Sanitize input
    $sanitized_emailid = mysqli_real_escape_string($connection, $email);
    $sanitized_password = mysqli_real_escape_string($connection, $password);

    // Fetch admin details from the database
    $sql = "SELECT * FROM admin WHERE email = '$sanitized_emailid'";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        die("Query Failed: " . mysqli_error($connection)); // Debugging
    }

    if ($row = mysqli_fetch_assoc($result)) {
        $db_password = $row['password']; // Get stored password

        // ✅ Handle both plain text and hashed passwords
        if ($db_password === $sanitized_password || password_verify($sanitized_password, $db_password)) {
            // Store session variables
            $_SESSION['email'] = $row['email'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['location'] = $row['location'];
            $_SESSION['Aid'] = $row['Aid'];

            // Redirect to admin panel
            header("Location: admin.php");
            exit();
        } else {
            $msg = "❌ Incorrect password!";
        }
    } else {
        $msg = "⚠️ Account does not exist!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="formstyle.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .login-container {
            background: white;
            padding: 20px;
            width: 400px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }
        .error {
            color: red;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #06C167;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        button:hover {
            background: #049a50;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2><span style="color:#06C167;">Food</span> <strong>Donate</strong></h2>
        <h3>Welcome back!</h3>

        <form action="" method="post">
            <div class="input-group">
                <input type="text" name="email" placeholder="Email" required>
            </div>
            
            <div class="input-group password">
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <?php if ($msg != "") { echo "<p class='error'>$msg</p>"; } ?>

            <button type="submit" name="sign">Sign in</button>

            <p class="signup-text">Don't have an account? <a href="register.php">Register</a></p>
        </form>
    </div>
</body>
</html>
