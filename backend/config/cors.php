<?php
/**
 * CORS Headers Configuration
 * Allows cross-origin requests from the frontend
 */
require_once __DIR__ . '/env.php';
loadBackendEnv();

// Use ALLOWED_ORIGIN from backend/.env when provided, otherwise allow all.
$allowedOrigin = getenv('ALLOWED_ORIGIN') ?: '*';
header("Access-Control-Allow-Origin: " . $allowedOrigin);

// Allow specific HTTP methods
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

// Allow specific headers
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Set content type to JSON
header("Content-Type: application/json; charset=UTF-8");

// Handle preflight OPTIONS request
// Browsers send this before actual request to check permissions
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
?>
