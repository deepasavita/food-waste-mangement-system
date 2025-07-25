<?php
include '../connection.php';
$msg = 0;

if (isset($_POST['sign'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $region = $_POST['region']; // Mumbai region
    $location = $_POST['district']; // Area
    $address = $_POST['address'];

    $pass = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT * FROM admin WHERE email='$email'";
    $result = mysqli_query($connection, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 1) {
        echo "<h1><center>Account already exists</center></h1>";
    } else {
        $query = "INSERT INTO admin(name, email, password, location, address) 
                  VALUES('$username', '$email', '$pass', '$location', '$address')";
        $query_run = mysqli_query($connection, $query);

        if ($query_run) {
            header("location:signin.php");
        } else {
            echo '<script type="text/javascript">alert("Data not saved")</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="formstyle.css" />
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
  <title>Register</title>
</head>
<body>
  <div class="container">
    <form action="" method="post" id="form">
      <span class="title">Register</span>
      <br><br>

      <div class="input-group">
        <label for="username">Name</label>
        <input type="text" id="username" name="username" required />
      </div>

      <div class="input-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required />
      </div>

      <div class="input-group">
        <label for="password">Password</label>
        <div class="password">
          <input type="password" name="password" id="password" required />
          <i class="uil uil-eye-slash showHidePw" id="showpassword"></i>
          <?php
          if (isset($msg) && $msg == 1) {
              echo '<p class="error">Passwords don\'t match.</p>';
          }
          ?>
        </div>
      </div>

      <div class="input-group">
        <label for="address">Address</label>
        <textarea id="address" name="address" required></textarea>
      </div>

      <div class="input-field">
        <label for="region">Region</label>
        <select id="region" name="region" required style="padding:10px; padding-left: 20px;">
          <option value="">Select Region</option>
          <option value="mumbai-city">Mumbai City</option>
          <option value="mumbai-suburban">Mumbai Suburban</option>
          <option value="thane">Thane</option>
          <option value="navi-mumbai">Navi Mumbai</option>
          <option value="palghar">Palghar</option>
        </select>
      </div>

      <div class="input-field">
        <label for="district">Area</label>
        <select id="district" name="district" required style="padding:10px; padding-left: 20px;">
          <option value="">Select Area</option>
        </select>
      </div>

      <button type="submit" name="sign">Register</button>

      <div class="login-signup">
        <span class="text">Already a member?
          <a href="signin.php" class="text login-link">Login Now</a>
        </span>
      </div>
    </form>
  </div>

  <!-- Cascading Dropdown JavaScript -->
  <script>
    const data = {
      "mumbai-city": ["Colaba", "Fort", "Churchgate", "Marine Lines", "Dadar", "Bandra", "Worli"],
      "mumbai-suburban": ["Kurla", "Mulund", "Vikhroli", "Ghatkopar", "Chembur", "Kandivali", "Borivali"],
      "thane": ["Thane West", "Thane East", "Kalyan", "Dombivli", "Ulhasnagar", "Ambernath", "Badlapur"],
      "navi-mumbai": ["Vashi", "Nerul", "Panvel", "Kharghar", "Belapur"],
      "palghar": ["Mira Bhayandar", "Vasai", "Virar", "Boisar", "Dahanu"]
    };

    const regionSelect = document.getElementById("region");
    const districtSelect = document.getElementById("district");

    regionSelect.addEventListener("change", function () {
      const selectedRegion = this.value;
      const areas = data[selectedRegion] || [];

      // Clear old options
      districtSelect.innerHTML = '<option value="">Select Area</option>';

      // Add new options
      areas.forEach(area => {
        const option = document.createElement("option");
        option.value = area;
        option.textContent = area;
        districtSelect.appendChild(option);
      });
    });
  </script>

  <!-- Optional: Password show/hide toggle logic (if needed) -->
  <script>
    const showPassIcon = document.getElementById("showpassword");
    const passwordField = document.getElementById("password");

    showPassIcon.addEventListener("click", () => {
      const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
      passwordField.setAttribute("type", type);
      showPassIcon.classList.toggle("uil-eye");
      showPassIcon.classList.toggle("uil-eye-slash");
    });
  </script>
</body>
</html>
