<?php
/**
 * Database Configuration
 * This file contains database connection settings
 */
require_once __DIR__ . '/env.php';
loadBackendEnv();

class Database
{
    // Database credentials using environment variables with defaults
    private $host;
    private $db_name;
    private $username;
    private $password;
    public $conn;

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: "localhost";
        $this->db_name = getenv('DB_NAME') ?: "software";
        $this->username = getenv('DB_USER') ?: "root";
        $this->password = getenv('DB_PASS') ?: "";
    }

    /**
     * Get database connection
     * @return PDO|null Database connection object
     */
    public function getConnection()
    {
        $this->conn = null;

        try {
            // Create PDO connection with MySQL
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );

            // Set character set to UTF-8
            $this->conn->exec("set names utf8");

            // Set error mode to exception for better error handling
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
        }

        return $this->conn;
    }
}
?>
