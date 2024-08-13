<?php
header('Content-Type: application/json');

// Database configuration
$servername = "localhost";
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "sertifikasi_jmp"; // Ganti dengan nama database Anda

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the query
$sql = "SELECT * FROM transactions"; // Ganti dengan nama tabel transaksi Anda
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch all rows
    $transactions = [];
    while ($row = $result->fetch_assoc()) {
        $transactions[] = $row;
    }

    // Success response
    echo json_encode($transactions);
} else {
    // No transactions found
    echo json_encode([]);
}

// Close connection
$conn->close();
?>
