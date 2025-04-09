<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include '../connection.php';

session_start(); // Start session for CSRF token

// Generate CSRF token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check database connection
if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

$errors = []; // Store errors

if (isset($_POST['sign'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errors[] = "⚠️ Invalid CSRF token!";
    }

    $username = trim($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $gender = $_POST['gender'];

    // Validate email format
    if (!$email) {
        $errors[] = "❌ Invalid email format!";
    }

    // Validate password (min 6 characters, at least 1 number & 1 special character)
    if (strlen($password) < 6 || !preg_match('/[0-9]/', $password) || !preg_match('/[!@#$%^&*]/', $password)) {
        $errors[] = "❌ Password must be at least 6 characters long and include 1 number & 1 special character!";
    }

    if (empty($errors)) {
        $pass = password_hash($password, PASSWORD_DEFAULT);

        // Check if email exists (Prevent SQL Injection with Prepared Statements)
        $stmt = $connection->prepare("SELECT * FROM login WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "⚠️ Account already exists! Try logging in.";
        } else {
            // Insert new user
            $stmt = $connection->prepare("INSERT INTO login (name, email, password, gender) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $pass, $gender);
            if ($stmt->execute()) {
                header("Location: signin.php");
                exit();
            } else {
                $errors[] = "❌ Data not saved. Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Food Donate</title>
    <link rel="stylesheet" href="../loginstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            padding: 20px;
            width: 400px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }
        .logo {
            font-size: 25px;
            font-weight: bold;
            color: black;
        }
        .logo b {
            color: #06C167;
        }
        .input, .password, .radio, .btn {
            margin-top: 10px;
        }
        input {
            width: 90%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
        }
        .btn button {
            width: 100%;
            padding: 10px;
            background: #06C167;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn button:hover {
            background: #049a50;
        }
        .password {
            position: relative;
        }
        .showHidePw {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: left;
        }
    </style>
</head>
<body>

<div class="container">
    <p class="logo">Food <b>Donate</b></p>
    <p id="heading">Create Your Account</p>

    <!-- Display Errors -->
    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error) {
                echo "<p>$error</p>";
            } ?>
        </div>
    <?php endif; ?>

    <form action="signup.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <div class="input">
            <label class="textlabel" for="name">User Name</label><br>
            <input type="text" id="name" name="name" required />
        </div>
        
        <div class="input">
            <label class="textlabel" for="email">Email</label>
            <input type="email" id="email" name="email" required />
        </div>
        
        <label class="textlabel" for="password">Password</label>
        <div class="password">
            <input type="password" name="password" id="password" required />
            <i class="uil uil-eye-slash showHidePw" id="showpassword"></i>                
        </div>

        <div class="radio">
            <input type="radio" name="gender" id="male" value="male" required />
            <label for="male">Male</label>
            <input type="radio" name="gender" id="female" value="female">
            <label for="female">Female</label>
        </div>

        <div class="btn">
            <button type="submit" name="sign">Continue</button>
        </div>

        <div class="signin-up">
            <p style="font-size: 16px;">Already have an account? <a href="signin.php">Sign in</a></p>
        </div>
    </form>
</div>

<script src="../admin/login.js"></script>

</body>
</html>
