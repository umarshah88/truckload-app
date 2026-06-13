<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'POST') {
    submit_rating();
} else if ($method === 'GET' && $action === 'user') {
    get_user_ratings();
} else {
    json_response(['error' => 'Invalid request'], 400);
}

function submit_rating() {
    $user = require_auth();
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['ride_id'], $data['rated_user_id'], $data['rating'])) {
        json_response(['error' => 'Missing required fields'], 400);
    }
    
    $rating = intval($data['rating']);
    if ($rating < 1 || $rating > 5) {
        json_response(['error' => 'Rating must be between 1 and 5'], 400);
    }
    
    $rating_id = generate_uuid();
    
    $result = execute_query(
        'INSERT INTO ratings (id, ride_id, rater_id, rated_user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())',
        [$rating_id, $data['ride_id'], $user['id'], $data['rated_user_id'], $rating, $data['comment'] ?? '']
    );
    
    if (!$result) {
        json_response(['error' => 'Failed to submit rating'], 500);
    }
    
    json_response(['message' => 'Rating submitted'], 201);
}

function get_user_ratings() {
    $user_id = $_GET['user_id'] ?? '';
    
    if (!$user_id) {
        json_response(['error' => 'Missing user_id'], 400);
    }
    
    $ratings = fetch_all_query(
        'SELECT * FROM ratings WHERE rated_user_id = ? ORDER BY created_at DESC LIMIT 50',
        [$user_id]
    );
    
    json_response($ratings);
}
?>
