<?php
/**
 * Helper Functions
 */

require_once __DIR__ . '/../config/db.php';

// Generate JWT Token
function generate_token($user_id, $email, $role) {
    $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
    $payload = base64_encode(json_encode([
        'id' => $user_id,
        'email' => $email,
        'role' => $role,
        'iat' => time(),
        'exp' => time() + JWT_EXPIRY
    ]));
    
    $signature = base64_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
    return "$header.$payload.$signature";
}

// Verify JWT Token
function verify_token($token) {
    $parts = explode('.', $token);
    if (count($parts) !== 3) return null;
    
    list($header, $payload, $signature) = $parts;
    
    $valid_signature = base64_encode(hash_hmac('sha256', "$header.$payload", JWT_SECRET, true));
    if ($signature !== $valid_signature) return null;
    
    $decoded = json_decode(base64_decode($payload), true);
    if ($decoded['exp'] < time()) return null;
    
    return $decoded;
}

// Get current user
function get_current_user() {
    $headers = getallheaders();
    $auth_header = $headers['Authorization'] ?? '';
    
    if (preg_match('/Bearer\s+(.+)/', $auth_header, $matches)) {
        $token = $matches[1];
        return verify_token($token);
    }
    
    return null;
}

// Require authentication
function require_auth() {
    $user = get_current_user();
    if (!$user) {
        header('HTTP/1.1 401 Unauthorized');
        die(json_encode(['error' => 'Unauthorized']));
    }
    return $user;
}

// Require specific role
function require_role($role) {
    $user = require_auth();
    if ($user['role'] !== $role && $user['role'] !== 'admin') {
        header('HTTP/1.1 403 Forbidden');
        die(json_encode(['error' => 'Forbidden']));
    }
    return $user;
}

// Generate UUID
function generate_uuid() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

// Hash password
function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

// Verify password
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// Send JSON response
function json_response($data, $status_code = 200) {
    header('Content-Type: application/json');
    header('HTTP/1.1 ' . $status_code);
    die(json_encode($data));
}

// Sanitize input
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Validate email
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Send email
function send_email($to, $subject, $body, $html = true) {
    $headers = "From: " . SMTP_FROM_NAME . " <" . SMTP_FROM . ">\r\n";
    $headers .= "Reply-To: " . SMTP_FROM . "\r\n";
    $headers .= $html ? "Content-Type: text/html; charset=UTF-8\r\n" : "";
    
    return mail($to, $subject, $body, $headers);
}

// Format currency
function format_currency($amount) {
    return '$' . number_format($amount / 100, 2);
}

// Get distance between two coordinates
function calculate_distance($lat1, $lon1, $lat2, $lon2) {
    $earth_radius = 6371; // km
    
    $lat1_rad = deg2rad($lat1);
    $lat2_rad = deg2rad($lat2);
    $delta_lat = deg2rad($lat2 - $lat1);
    $delta_lon = deg2rad($lon2 - $lon1);
    
    $a = sin($delta_lat / 2) * sin($delta_lat / 2) +
         cos($lat1_rad) * cos($lat2_rad) *
         sin($delta_lon / 2) * sin($delta_lon / 2);
    
    $c = 2 * asin(sqrt($a));
    
    return $earth_radius * $c;
}
?>
