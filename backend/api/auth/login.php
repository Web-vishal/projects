<?php
/**
 * Authentication API
 * Handles user login and authentication
 */

// Include CORS headers
include_once '../../config/cors.php';

// Include database connection
include_once '../../config/database.php';

/**
 * Get JSON input from request body
 * file_get_contents('php://input') reads raw POST data
 */
$data = json_decode(file_get_contents("php://input"));

// Validate input data
if (!isset($data->username) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "Username and password are required"
    ]);
    exit();
}

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Prepare SQL query to prevent SQL injection
// Prepare SQL query to prevent SQL injection
$query = "SELECT id, username, email, mobile_no, password, full_name, type, status 
          FROM users 
          WHERE username = :username AND status = 'active' 
          LIMIT 1";

$stmt = $db->prepare($query);

// Bind parameters
$stmt->bindParam(":username", $data->username);

// Execute query
$stmt->execute();

// Get number of rows
$num = $stmt->rowCount();

if ($num > 0) {
    // User found, verify password
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify password using plain text comparison (as requested)
    if ($data->password === $row['password']) {
        // Password is correct
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Login successful",
            "user" => [
                "id" => $row['id'],
                "username" => $row['username'],
                "email" => $row['email'],
                "mobile_no" => $row['mobile_no'],
                "full_name" => $row['full_name'],
                "type" => $row['type']
            ],
            // In production, use JWT tokens for authentication
            "token" => base64_encode($row['id'] . ":" . $row['username'])
        ]);
    } else {
        // Invalid password
        http_response_code(401);
        echo json_encode([
            "success" => false,
            "message" => "Invalid username or password"

        ]);
    }
} else {
    // User not found
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Invalid username or password"
    ]);
}
?>