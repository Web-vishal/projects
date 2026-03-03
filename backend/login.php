<?php
// Include configuration files
require_once 'config/cors.php';
require_once 'config/database.php';

// Get database connection
$database = new Database();
$db = $database->getConnection();

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

// Check if data is provided
if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'Username and password are required'
    ]);
    exit();
}

try {
    // Prepare query to find user by username or email
    $query = "SELECT id, username, email, password, full_name, type, status FROM users WHERE username = :username OR email = :username LIMIT 1";
    $stmt = $db->prepare($query);

    // Bind parameters
    $stmt->bindParam(":username", $username);

    // Execute query
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password (plain text as requested)
        if ($password === $user['password']) {

            // Check if user is active
            if ($user['status'] !== 'active') {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Account is inactive'
                ]);
                exit();
            }

            // Remove password from response
            unset($user['password']);

            // Return success response with user data including type
            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user
            ]);
        } else {
            // Invalid password
            http_response_code(401);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid username or password'
            ]);
        }
    } else {
        // User not found
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid username or password'
        ]);
    }
} catch (PDOException $e) {
    // Log error and return generic message
    error_log("Login error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred during login'
    ]);
}
?>