<?php
$server = 'localhost';
$user = 'root';
$password = '';
$db = 'sertifikasi_jmp';

$connection = new mysqli($server, $user, $password, $db);

if ($connection->connect_error) {
    die('Connection failed: ' . $connection->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name']) && isset($data['price']) && isset($data['sold']) && isset($data['stock'])) {
    $name = $connection->real_escape_string($data['name']);
    $price = $connection->real_escape_string($data['price']);
    $sold = $connection->real_escape_string($data['sold']);
    $stock = $connection->real_escape_string($data['stock']);

    $query = "UPDATE products SET price='$price', sold='$sold', stock='$stock' WHERE name='$name'";
    
    if ($connection->query($query) === TRUE) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $connection->error]);
    }
}

$connection->close();
?>
