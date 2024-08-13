<?php
header('Content-Type: application/json');

// Database configuration
$servername = "localhost";
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$dbname = "sertifikasi_jmp"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if POST data is set
if (isset($_POST['username']) && isset($_POST['password'])) {
    // Get POST data
    $inputUsername = $_POST['username'];
    $inputPassword = $_POST['password'];

    // Prepare and execute the query
    $sql = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
         // Compare passwords (no hashing)
         if ($inputPassword === $row['password']) {
            // Success response
            echo json_encode(['success' => true]);
        } else {
            // Invalid password
            echo json_encode(['success' => false]);
        }
    } else {
        // User not found
        echo json_encode(['success' => false]);
    }

    // Close statement
    $stmt->close();
} else {
    // POST data not set
    echo json_encode(['success' => false, 'error' => 'Missing username or password']);
}

// Close connection
$conn->close();
?>
