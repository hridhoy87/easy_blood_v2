<?php
session_start();

// Debug line to print whether the session is set
$check_bool = !isset($_SESSION["email"]) || $_SESSION["email"] === "";
// Comment out this line in production
// echo $check_bool;

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["email"]) || $_SESSION["email"] === ""){
    header("location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Blood Bank Data Entry</title>
    <link rel="icon" href="img.png" type="image/png">
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="img.png" alt="Blood Bank Logo">
            <h1>Blood Bank Data Entry</h1>
            <a href="logout.php" class="logout">Logout</a>
        </div>
        <form action="submit_data.php" method="post">
            <!-- Blood Bank Information -->
            <div class="form-group">
                <label for="bbname">Blood Bank Name</label>
                <input type="text" class="form-control" id="bbname" name="bbname" required>
            </div>
            <div class="form-group">
                <label for="lat">Latitude</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="lat" name="lat" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary location-btn" onclick="useCurrentLocation()">Use Current Location</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="long">Longitude</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="long" name="long" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary location-btn" onclick="useCurrentLocation()">Use Current Location</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="plus_code">Plus Code</label>
                <input type="text" class="form-control" id="plus_code" name="plus_code" required>
            </div>

            <!-- Blood Stock and Price Information -->
            <h4>Blood Stock and Price Information</h4>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="aPOS">A+ve Stock</label>
                        <input type="number" class="form-control" id="aPOS" name="aPOS" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label for="price_aPOS">A+ve Price (Taka)</label>
                        <input type="number" class="form-control" id="price_aPOS" name="price_aPOS" min="0" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="bPOS">B+ve Stock</label>
                        <input type="number" class="form-control" id="bPOS" name="bPOS" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label for="price_bPOS">B+ve Price (Taka)</label>
                        <input type="number" class="form-control" id="price_bPOS" name="price_bPOS" min="0" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="abPOS">AB+ve Stock</label>
                        <input type="number" class="form-control" id="abPOS" name="abPOS" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label for="price_abPOS">AB+ve Price (Taka)</label>
                        <input type="number" class="form-control" id="price_abPOS" name="price_abPOS" min="0" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="oPOS">O+ve Stock</label>
                        <input type="number" class="form-control" id="oPOS" name="oPOS" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label for="price_oPOS">O+ve Price (Taka)</label>
                        <input type="number" class="form-control" id="price_oPOS" name="price_oPOS" min="0" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="aNEG">A-ve Stock</label>
                        <input type="number" class="form-control" id="aNEG" name="aNEG" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label for="price_aNEG">A-ve Price (Taka)</label>
                        <input type="number" class="form-control" id="price_aNEG" name="price_aNEG" min="0" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="bNEG">B-ve Stock</label>
                        <input type="number" class="form-control" id="bNEG" name="bNEG" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label for="price_bNEG">B-ve Price (Taka)</label>
                        <input type="number" class="form-control" id="price_bNEG" name="price_bNEG" min="0" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="abNEG">AB-ve Stock</label>
                        <input type="number" class="form-control" id="abNEG" name="abNEG" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label for="price_abNEG">AB-ve Price (Taka)</label>
                        <input type="number" class="form-control" id="price_abNEG" name="price_abNEG" min="0" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-6">
                        <label for="oNEG">O-ve Stock</label>
                        <input type="number" class="form-control" id="oNEG" name="oNEG" min="0" required>
                    </div>
                    <div class="col-md-6">
                        <label for="price_oNEG">O-ve Price (Taka)</label>
                        <input type="number" class="form-control" id="price_oNEG" name="price_oNEG" min="0" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-custom">Submit</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        function useCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    document.getElementById('lat').value = position.coords.latitude;
                    document.getElementById('long').value = position.coords.longitude;
                }, function(error) {
                    alert("Error retrieving location: " + error.message);
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        }
    </script>
</body>
</html>
