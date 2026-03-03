<?php
/**
 * Change Password API
 * Allows authenticated users to change their password
 */

include_once '../../config/cors.php';
include_once '../../config/database.php';

// Get JSON input
$data = json_decode(file_get_contents("php://input"));

// Validate input
if (!isset($data->user_id) || !isset($data->current_password) || !isset($data->new_password)) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "User ID, current password, and new password are required"
    ]);
    exit();
}

// Validate new password strength
if (strlen($data->new_password) < 6) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "New password must be at least 6 characters long"
    ]);
    exit();
}

// Create database connection
$database = new Database();
$db = $database->getConnection();

// Get current user data
$query = "SELECT password FROM users WHERE id = :user_id LIMIT 1";
$stmt = $db->prepare($query);
$stmt->bindParam(":user_id", $data->user_id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify current password (plain text check)
    if ($data->current_password === $row['password']) {
        // Use plain text for new password
        $new_password = $data->new_password;

        // Update password
        $update_query = "UPDATE users SET password = :password, updated_at = NOW() WHERE id = :user_id";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bindParam(":password", $new_password);
        $update_stmt->bindParam(":user_id", $data->user_id);

        if ($update_stmt->execute()) {
            http_response_code(200);
            echo json_encode([
                "success" => true,
                "message" => "Password changed successfully"
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Failed to update password"
            ]);
        }
    } else {
        http_response_code(401);
        echo json_encode([
            "success" => false,
            "message" => "Current password is incorrect"
        ]);
    }
} else {
    http_response_code(404);
    echo json_encode([
        "success" => false,
        "message" => "User not found"
    ]);
}
?>