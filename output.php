<?php
require_once("conn.php");

// Function to validate and sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Function to calculate Haversine distance
function haversine_distance($lat1, $lon1, $lat2, $lon2, $earth_radius) {
    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    $dlat = $lat2 - $lat1;
    $dlon = $lon2 - $lon1;

    $a = sin($dlat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dlon / 2) ** 2;
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earth_radius * $c;
}

// Check if POST data is set
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bloodGP'], $_POST['latitude'], $_POST['longitude'])) {
    $bdGP = sanitize_input($_POST['bloodGP']);
    $lat_user = filter_var($_POST['latitude'], FILTER_VALIDATE_FLOAT);
    $lon_user = filter_var($_POST['longitude'], FILTER_VALIDATE_FLOAT);

    if ($lat_user === false || $lon_user === false) {
        echo "Invalid latitude or longitude.";
        exit;
    }

    // Radius of Earth in meters
    $earth_radius = 6371000;

    // Prepare the SQL query using prepared statements
    $query = "SELECT 
                bbdb.id AS id,
                bbdb.name, 
                state.{$bdGP} AS qty, 
                price.{$bdGP} AS ppb, 
                bbdb.lat,
                bbdb.long,
                bbdb.plus_code AS loc,
                state.last_updated_at 
            FROM `easy_blood`.`bbdb` 
            INNER JOIN `easy_blood`.`state` ON bbdb.name = state.bbname 
            INNER JOIN price ON state.bbname = price.bbname";

    $stmt = $conn->prepare($query);
    
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();

        // Array to hold all blood bank data
        $blood_banks = [];

        while ($row = $result->fetch_assoc()) {
            $lat_db = $row['lat'];
            $lon_db = $row['long'];

            // Calculate distance
            $distance = haversine_distance($lat_user, $lon_user, $lat_db, $lon_db, $earth_radius);

            $blood_banks[] = [
                'name' => htmlspecialchars($row['name']),
                'qty' => htmlspecialchars($row['qty']),
                'ppb' => htmlspecialchars($row['ppb']),
                'loc' => htmlspecialchars($row['loc']),
                'distance' => $distance
            ];
        }

        // Sort blood banks by distance
        usort($blood_banks, function($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        echo '<html>
                <head>
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
                    <title>Easy Blood</title>
                    <link rel="icon" href="img.png" type="image/png">    
                </head>
                <body>
                    <div class="container">
                        <div class="text-center my-4">
                            <img src="img.png" alt="Easy Blood Logo" style="width: 150px; height: auto;">
                        </div>
                        <h1 class="text-center my-4">Blood Bank Information</h1>
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Name of the Blood Bank</th>
                                    <th scope="col">No of Bloods remaining</th>
                                    <th scope="col">Price/Bag</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Distance (km)</th>
                                </tr>
                            </thead>';

        foreach ($blood_banks as $bank) {
            $url_map = 'https://www.google.com/maps/dir/' . $lat_user . ',' . $lon_user . '/' . $bank['loc'] . '/';
            $distance_km = round($bank['distance'] / 1000, 2); // Convert meters to kilometers

            echo '<tr>
                    <td>' . $bank['name'] . '</td>
                    <td>' . $bank['qty'] . ' bags</td>
                    <td>' . $bank['ppb'] . ' Taka</td>
                    <td><a href="' . htmlspecialchars($url_map) . '" target="_blank">' . $bank['loc'] . '</a></td>
                    <td>' . $distance_km . '</td>
                </tr>';
        }

        echo '</table>
              </div>
              <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
              <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
              <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
              </body>
              </html>';
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Error: Missing required POST data.";
}

// Close the database connection
$conn->close();
?>
