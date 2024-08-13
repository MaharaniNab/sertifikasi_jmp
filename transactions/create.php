<?php
header("Content-Type: application/json");

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sertifikasi_jmp";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Get the JSON input from the request body
$data = json_decode(file_get_contents('php://input'), true);

// Extract data from JSON
$name = $data['name'] ?? '';
$address = $data['address'] ?? '';
$phoneNumber = $data['phoneNumber'] ?? '';
$latitude = $data['latitude'] ?? null;
$longitude = $data['longitude'] ?? null;
$product = $data['product'] ?? '';
$quantity = $data['quantity'] ?? 0;
$price = $data['price'] ?? 0;

// Prepare and bind for inserting transaction
$stmt = $conn->prepare("INSERT INTO transactions (name, address, phoneNumber, latitude, longitude, product, quantity, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssdsss", $name, $address, $phoneNumber, $latitude, $longitude, $product, $quantity, $price);

// Execute the transaction insert
if ($stmt->execute()) {
    // Prepare and bind for updating product stock and sold quantity
    $stmt = $conn->prepare("UPDATE products SET stock = stock - ?, sold = sold + ? WHERE name = ?");
    $stmt->bind_param("iis", $quantity, $quantity, $product);

    // Execute the product update
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Transaction data saved and product stock updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update product stock"]);
    }
    
    // Close the statement for product update
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Failed to save transaction data"]);
}

// Close connections
$conn->close();
?>
