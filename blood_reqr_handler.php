<?php
require 'config.php'; // Include configuration file

// Get data from the form
$bloodGroup = $_POST['bloodGroup'] ?? '';
$mobileNumber = $_POST['mobileNumber'] ?? '';
$lat = $_POST['lat'] ?? '';
$lon = $_POST['lon'] ?? '';

// Validate and sanitize inputs
if (empty($bloodGroup) || empty($mobileNumber) || empty($lat) || empty($lon)) {
    echo "All fields are required.";
    exit;
}

// Prepare the SQL statement
$sql = "INSERT INTO blood_reqr (bloodGroup, mobile_number, requ_lat, requ_lon, created_at, updated_at) 
        VALUES (:bloodGroup, :mobileNumber, :lat, :lon, NOW(), NOW())";
$stmt = $pdo->prepare($sql);

// Bind parameters and execute the statement
try {
    $stmt->execute([
        ':bloodGroup' => $bloodGroup,
        ':mobileNumber' => $mobileNumber,
        ':lat' => $lat,
        ':lon' => $lon
    ]);
    echo "New record created successfully";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
