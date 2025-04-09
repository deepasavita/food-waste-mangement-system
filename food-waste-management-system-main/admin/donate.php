<?php
session_start();
include "../connection.php"; // Use only this connection file

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="admin.css">
    <title>Admin Dashboard Panel</title> 
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
                <li><a href="donate.php" class="active"><i class="uil uil-heart"></i> Donates</a></li>
                <li><a href="feedback.php"><i class="uil uil-comments"></i> Feedbacks</a></li>
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
        <br><br>

        <div class="activity">
            <div class="location">
                <form method="post">
                    <label for="district" class="logo">Select District:</label>
                    <select id="district" name="district" onchange="updateSubAreas()">
                        <option value="mumbai-city">Mumbai City</option>
                        <option value="mumbai-suburban">Mumbai Suburban</option>
                        <option value="thane">Thane</option>
                        <option value="navi-mumbai">Navi Mumbai</option>
                        <option value="palghar">Palghar</option>
                    </select>

                    <label for="subarea">Select Area:</label>
                    <select id="subarea" name="subarea">
                        <option value="">Select an area</option>
                    </select>

                    <input type="submit" value="Get Details">
                </form>
                <br>

                <?php
                if (isset($_POST['district']) && isset($_POST['subarea'])) {
                    $district = mysqli_real_escape_string($connection, $_POST['district']);
                    $subarea = mysqli_real_escape_string($connection, $_POST['subarea']);
                    $location = "$district - $subarea";

                    $query = "SELECT * FROM food_donations WHERE location='$location'";
                    $result = mysqli_query($connection, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        echo "<div class='table-container'>";
                        echo "<div class='table-wrapper'>";
                        echo "<table class='table'>";
                        echo "<thead><tr>
                                <th>Name</th>
                                <th>Food</th>
                                <th>Category</th>
                                <th>Phone</th>
                                <th>Date/Time</th>
                                <th>Address</th>
                                <th>Quantity</th>
                              </tr></thead><tbody>";

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                    <td>{$row['name']}</td>
                                    <td>{$row['food']}</td>
                                    <td>{$row['category']}</td>
                                    <td>{$row['phoneno']}</td>
                                    <td>{$row['date']}</td>
                                    <td>{$row['address']}</td>
                                    <td>{$row['quantity']}</td>
                                  </tr>";
                        }
                        echo "</tbody></table></div></div>";
                    } else {
                        echo "<p>No results found for $location.</p>";
                    }
                }
                ?>
            </div>
        </div>
    </section>

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

    <script src="admin.js"></script>
</body>
</html>
