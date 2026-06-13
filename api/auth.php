<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch($action) {
    case 'register':
        register();
        break;
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;
    case 'refresh':
        refresh_token();
        break;
    default:
        json_response(['error' => 'Invalid action'], 400);
}

function register() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['email'], $data['password'], $data['name'], $data['phone'], $data['role'])) {
        json_response(['error' => 'Missing required fields'], 400);
    }
    
    $email = sanitize($data['email']);
    $password = $data['password'];
    $name = sanitize($data['name']);
    $phone = sanitize($data['phone']);
    $role = in_array($data['role'], ['shipper', 'driver']) ? $data['role'] : 'shipper';
    
    if (!validate_email($email)) {
        json_response(['error' => 'Invalid email'], 400);
    }
    
    $existing = fetch_query('SELECT id FROM users WHERE email = ?', [$email]);
    if ($existing) {
        json_response(['error' => 'User already exists'], 400);
    }
    
    $password_hash = hash_password($password);
    $user_id = generate_uuid();
    $result = execute_query(
        'INSERT INTO users (id, email, password, name, phone, role, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())',
        [$user_id, $email, $password_hash, $name, $phone, $role]
    );
    
    if ($result === false) {
        json_response(['error' => 'Registration failed'], 500);
    }
    
    $token = generate_token($user_id, $email, $role);
    
    json_response([
        'message' => 'User registered successfully',
        'token' => $token,
        'user' => [
            'id' => $user_id,
            'email' => $email,
            'name' => $name,
            'role' => $role
        ]
    ], 201);
}

function login() {
    global $conn;
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['email'], $data['password'])) {
        json_response(['error' => 'Email and password required'], 400);
    }
    
    $email = sanitize($data['email']);
    $password = $data['password'];
    
    $user = fetch_query('SELECT * FROM users WHERE email = ?', [$email]);
    
    if (!$user || !verify_password($password, $user['password'])) {
        json_response(['error' => 'Invalid credentials'], 401);
    }
    
    $token = generate_token($user['id'], $user['email'], $user['role']);
    
    json_response([
        'token' => $token,
        'user' => [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'role' => $user['role']
        ]
    ]);
}

function logout() {
    json_response(['message' => 'Logged out successfully']);
}

function refresh_token() {
    $user = require_auth();
    $new_token = generate_token($user['id'], $user['email'], $user['role']);
    json_response(['token' => $new_token]);
}
?>
