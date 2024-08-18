<?php
session_start();
require_once("conn.php");

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the request is for login or registration
    if (isset($_POST['login'])) {
        // Login Process
        $email = sanitize_input($_POST['email']);
        $password = sanitize_input($_POST['password']);

        // Prepare SQL statement to check user
        $query = "SELECT pass FROM bloodbank_login WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Check if email exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Password is correct
                $_SESSION["email"] = $email;
                header("Location: bloodBankDataentry.php");
                exit();
            } else {
                echo "Invalid password. <a href='index.html'>Try again</a>";
            }
        } else {
            echo "No account found with this email. <a href='index.html'>Try again</a>";
        }

        $stmt->close();
    } else if (isset($_POST['register'])) {
        // Registration Process
        $username = sanitize_input($_POST['username']);
        $email = sanitize_input($_POST['email']);
        $password = sanitize_input($_POST['password']);

        // Encrypt the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement to insert user data
        $query = "INSERT INTO bloodbank_login (username, email, pass, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Registration successful! <a href='index.html'>Return to homepage</a>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>
