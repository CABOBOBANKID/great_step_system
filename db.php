<?php
/**
 * Database Connection File
 * Great Step Academy School Portal
 */

$host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "great_step_academy";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Set charset to utf8mb4 (best practice)
$conn->set_charset("utf8mb4");

// Optional: Enable error reporting for development (remove or comment in production)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

?>