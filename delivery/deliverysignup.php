<?php
include '../connection.php';
$msg = 0;

if(isset($_POST['sign'])) {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);
    $district = mysqli_real_escape_string($connection, $_POST['district']);
    $subarea = mysqli_real_escape_string($connection, $_POST['subarea']); // New field for area inside district

    $pass = password_hash($password, PASSWORD_DEFAULT);
    $sql = "SELECT * FROM delivery_persons WHERE email='$email'";
    $result = mysqli_query($connection, $sql);
    $num = mysqli_num_rows($result);

    if($num == 1) {
        echo "<h1><center>Account already exists</center></h1>";
    } else {
        $query = "INSERT INTO delivery_persons(name, email, password, city) 
                  VALUES('$username', '$email', '$pass', '$district - $subarea')";
        $query_run = mysqli_query($connection, $query);
        if($query_run) {
            header("location:delivery.php");
        } else {
            echo '<script type="text/javascript">alert("Data not saved")</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Signup</title>
    <link rel="stylesheet" href="deliverycss.css">
</head>
<body>
    <div class="center">
        <h1>Register</h1>
        <form method="post" action="">
            <div class="txt_field">
                <input type="text" name="username" required/>
                <span></span>
                <label>Username</label>
            </div>
            <div class="txt_field">
                <input type="password" name="password" required/>
                <span></span>
                <label>Password</label>
            </div>
            <div class="txt_field">
                <input type="email" name="email" required/>
                <span></span>
                <label>Email</label>
            </div>

            <!-- Mumbai-Only District and Area Selection -->
            <div class="input-group">
                <label for="district">District:</label>
                <select id="district" name="district" style="padding:10px;" onchange="updateSubAreas()">
                    <option value="mumbai-city">Mumbai City</option>
                    <option value="mumbai-suburban">Mumbai Suburban</option>
                    <option value="thane">Thane</option>
                    <option value="navi-mumbai">Navi Mumbai</option>
                    <option value="palghar">Palghar</option>
                </select>

                <label for="subarea">Area:</label>
                <select id="subarea" name="subarea" style="padding:10px;">
                    <option value="">Select an area</option>
                </select>
            </div>

            <input type="submit" name="sign" value="Register">
            <div class="signup_link">
                Already a member? <a href="deliverylogin.php">Sign in</a>
            </div>
        </form>
    </div>

    <script>
    function updateSubAreas() {
        let district = document.getElementById("district").value;
        let subareaDropdown = document.getElementById("subarea");
        subareaDropdown.innerHTML = ""; // Clear previous options

        let areas = {
            "mumbai-city": ["Colaba", "Fort", "Churchgate", "Marine Lines", "Dadar", "Bandra", "Worli"],
            "mumbai-suburban": ["Kurla", "Mulund", "Vikhroli", "Ghatkopar", "Chembur", "Kandivali", "Borivali"],
            "thane": ["Thane West", "Thane East", "Kalyan", "Dombivli", "Ulhasnagar", "Ambernath", "Badlapur"],
            "navi-mumbai": ["Vashi", "Nerul", "Panvel", "Kharghar", "Belapur"],
            "palghar": ["Mira Bhayandar", "Vasai", "Virar", "Boisar", "Dahanu"]
        };

        if (district in areas) {
            areas[district].forEach(function(area) {
                let option = document.createElement("option");
                option.value = area.toLowerCase().replace(/\s/g, "-"); // Convert to lowercase with dashes
                option.text = area;
                subareaDropdown.appendChild(option);
            });
        } else {
            let defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.text = "Select an area";
            subareaDropdown.appendChild(defaultOption);
        }
    }
    </script>
</body>
</html>