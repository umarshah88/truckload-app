<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'POST') {
    switch($action) {
        case 'create_intent':
            create_payment_intent();
            break;
        case 'confirm':
            confirm_payment();
            break;
        default:
            json_response(['error' => 'Invalid action'], 400);
    }
} else if ($method === 'GET' && $action === 'history') {
    get_payment_history();
} else {
    json_response(['error' => 'Method not allowed'], 405);
}

function create_payment_intent() {
    $user = require_role('shipper');
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['ride_id'], $data['amount'])) {
        json_response(['error' => 'Missing required fields'], 400);
    }
    
    $ride_id = $data['ride_id'];
    $amount = intval($data['amount']);
    $total_amount = $amount + PLATFORM_FEE;
    
    $payment_id = generate_uuid();
    
    $result = execute_query(
        'INSERT INTO payments (id, ride_id, user_id, amount, platform_fee, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())',
        [$payment_id, $ride_id, $user['id'], $amount, PLATFORM_FEE, 'pending']
    );
    
    if (!$result) {
        json_response(['error' => 'Failed to create payment'], 500);
    }
    
    json_response([
        'payment_id' => $payment_id,
        'amount' => $total_amount,
        'currency' => CURRENCY,
        'message' => 'Payment setup successful'
    ], 201);
}

function confirm_payment() {
    $user = require_auth();
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['payment_id'])) {
        json_response(['error' => 'Missing payment_id'], 400);
    }
    
    $result = execute_query(
        'UPDATE payments SET status = ? WHERE id = ? AND user_id = ?',
        ['completed', $data['payment_id'], $user['id']]
    );
    
    if (!$result) {
        json_response(['error' => 'Failed to confirm payment'], 500);
    }
    
    json_response(['message' => 'Payment confirmed successfully']);
}

function get_payment_history() {
    $user = require_auth();
    
    $payments = fetch_all_query(
        'SELECT * FROM payments WHERE user_id = ? ORDER BY created_at DESC LIMIT 50',
        [$user['id']]
    );
    
    json_response($payments);
}
?>
