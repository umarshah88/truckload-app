<?php
/**
 * Database Configuration
 * Edit these settings with your Hostinger database credentials
 */

// Database credentials - CHANGE THESE
define('DB_HOST', 'localhost'); // Usually localhost on Hostinger
define('DB_USER', 'your_db_user'); // Your database username
define('DB_PASS', 'your_db_password'); // Your database password
define('DB_NAME', 'your_db_name'); // Your database name

// Create connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception('Connection failed: ' . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset('utf8mb4');
    
} catch (Exception $e) {
    error_log('Database connection error: ' . $e->getMessage());
    die(json_encode(['error' => 'Database connection failed']));
}

// MySQLi prepared statements function
function prepare_query($query, $params = []) {
    global $conn;
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        error_log('Prepare error: ' . $conn->error);
        throw new Exception('Database error');
    }
    
    if (!empty($params)) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) $types .= 'i';
            elseif (is_float($param)) $types .= 'd';
            else $types .= 's';
        }
        $stmt->bind_param($types, ...$params);
    }
    
    return $stmt;
}

function execute_query($query, $params = []) {
    $stmt = prepare_query($query, $params);
    $stmt->execute();
    return $stmt;
}

function fetch_query($query, $params = []) {
    $stmt = execute_query($query, $params);
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function fetch_all_query($query, $params = []) {
    $stmt = execute_query($query, $params);
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>
