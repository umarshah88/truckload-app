<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'POST' && $action === 'send') {
    send_message();
} else if ($method === 'GET' && $action === 'history') {
    get_chat_history();
} else {
    json_response(['error' => 'Invalid request'], 400);
}

function send_message() {
    $user = require_auth();
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['ride_id'], $data['recipient_id'], $data['message'])) {
        json_response(['error' => 'Missing required fields'], 400);
    }
    
    $message_id = generate_uuid();
    
    $result = execute_query(
        'INSERT INTO chat_messages (id, ride_id, sender_id, recipient_id, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())',
        [$message_id, $data['ride_id'], $user['id'], $data['recipient_id'], sanitize($data['message'])]
    );
    
    if (!$result) {
        json_response(['error' => 'Failed to send message'], 500);
    }
    
    json_response(['message_id' => $message_id], 201);
}

function get_chat_history() {
    $user = require_auth();
    $ride_id = $_GET['ride_id'] ?? '';
    
    if (!$ride_id) {
        json_response(['error' => 'Missing ride_id'], 400);
    }
    
    $messages = fetch_all_query(
        'SELECT * FROM chat_messages WHERE ride_id = ? ORDER BY created_at ASC LIMIT 100',
        [$ride_id]
    );
    
    json_response($messages);
}
?>
