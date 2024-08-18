<?php
// Include the database connection file
require '../connection.php';

// Check if the request is for blood request or donation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $lat = $_POST['lat'] ?? null;
    $lng = $_POST['lng'] ?? null;

    if (isset($_POST['bags'])) {
        // Blood request form submission
        $bags = $_POST['bags'];
        $bloodGroup = $_POST['bloodGroup'];
        $mobile = $_POST['mobile'];

        // Insert into blood_reqr table
        $sql = "INSERT INTO blood_reqr (bags, blood_group, mobile, requ_lat, requ_lon, created_at) 
                VALUES (:bags, :bloodGroup, :mobile, :lat, :lng, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':bags' => $bags,
            ':bloodGroup' => $bloodGroup,
            ':mobile' => $mobile,
            ':lat' => $lat,
            ':lng' => $lng
        ]);

        echo 'Blood request submitted successfully.';
    } elseif (isset($_POST['donateBags'])) {
        // Blood donation form submission
        $donateBags = $_POST['donateBags'];
        $donateBloodGroup = $_POST['donateBloodGroup'];
        $donateMobile = $_POST['donateMobile'];

        // Insert into donor table
        $sql = "INSERT INTO donor (bags, blood_group, donor_mobile, donor_lat, donor_long, created_at) 
                VALUES (:bags, :bloodGroup, :mobile, :lat, :lng, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':bags' => $donateBags,
            ':bloodGroup' => $donateBloodGroup,
            ':mobile' => $donateMobile,
            ':lat' => $lat,
            ':lng' => $lng
        ]);

        echo 'Blood donation submitted successfully.';
        header("Location: /easy_blood_v2/map_wk/");
    } else {
        echo 'Invalid request.';
    }
} else {
    echo 'Invalid request method.';
}
?>
