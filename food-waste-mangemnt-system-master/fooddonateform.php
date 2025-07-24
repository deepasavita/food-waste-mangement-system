<?php
include("login.php"); 
if($_SESSION['name']==''){
	header("location: signin.php");
}

$emailid= $_SESSION['email'];
$connection=mysqli_connect("localhost","root","");
$db=mysqli_select_db($connection,'demo');

if(isset($_POST['submit']))
{
    $foodname=mysqli_real_escape_string($connection, $_POST['foodname']);
    $meal=mysqli_real_escape_string($connection, $_POST['meal']);
    $category=$_POST['image-choice'];
    $quantity=mysqli_real_escape_string($connection, $_POST['quantity']);
    $phoneno=mysqli_real_escape_string($connection, $_POST['phoneno']);
    $district=mysqli_real_escape_string($connection, $_POST['district']);
    $subarea=mysqli_real_escape_string($connection, $_POST['subarea']); // New field for area inside district
    $address=mysqli_real_escape_string($connection, $_POST['address']);
    $name=mysqli_real_escape_string($connection, $_POST['name']);

    $query="INSERT INTO food_donations(email, food, type, category, phoneno, location, address, name, quantity) 
            VALUES('$emailid', '$foodname', '$meal', '$category', '$phoneno', '$district - $subarea', '$address', '$name', '$quantity')";

    $query_run= mysqli_query($connection, $query);
    if($query_run) {
        echo '<script type="text/javascript">alert("Data saved successfully!")</script>';
        header("location:delivery.html");
    } else {
        echo '<script type="text/javascript">alert("Data not saved. Please try again.")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate</title>
    <link rel="stylesheet" href="loginstyle.css">
</head>
<body style="background-color: #06C167;">
    <div class="container">
        <div class="regformf">
            <form action="" method="post">
                <p class="logo">Food <b style="color: #06C167;">Donate</b></p>

                <div class="input">
                    <label for="foodname">Food Name:</label>
                    <input type="text" id="foodname" name="foodname" required/>
                </div>

                <div class="radio">
                    <label for="meal">Meal Type:</label> 
                    <br><br>
                    <input type="radio" name="meal" id="veg" value="veg" required/>
                    <label for="veg" style="padding-right: 40px;">Veg</label>
                    <input type="radio" name="meal" id="Non-veg" value="Non-veg">
                    <label for="Non-veg">Non-veg</label>
                </div>

                <br>
                <div class="input">
                    <label for="food">Select the Category:</label>
                    <br><br>
                    <div class="image-radio-group">
                        <input type="radio" id="raw-food" name="image-choice" value="raw-food">
                        <label for="raw-food"><img src="img/raw-food.png" alt="raw-food"></label>
                        <input type="radio" id="cooked-food" name="image-choice" value="cooked-food" checked>
                        <label for="cooked-food"><img src="img/cooked-food.png" alt="cooked-food"></label>
                        <input type="radio" id="packed-food" name="image-choice" value="packed-food">
                        <label for="packed-food"><img src="img/packed-food.png" alt="packed-food"></label>
                    </div>
                    <br>
                </div>

                <div class="input">
                    <label for="quantity">Quantity (number of persons/kg):</label>
                    <input type="text" id="quantity" name="quantity" required/>
                </div>

                <b><p style="text-align: center;">Contact Details</p></b>
                <div class="input">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" value="<?php echo $_SESSION['name']; ?>" required/>
                </div>
                <div>
                    <label for="phoneno">Phone No:</label>
                    <input type="text" id="phoneno" name="phoneno" maxlength="10" pattern="[0-9]{10}" required />
                </div>

                <!-- Mumbai-Only District and Area Selection -->
                <div class="input">
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

                <div class="input">
                    <label for="address" style="padding-left: 10px;">Address:</label>
                    <input type="text" id="address" name="address" required/><br>
                </div>

                <div class="btn">
                    <button type="submit" name="submit">Submit</button>
                </div>
            </form>
        </div>
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
