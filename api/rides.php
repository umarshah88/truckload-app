<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

switch($method) {
    case 'GET':
        get_rides();
        break;
    case 'POST':
        create_ride();
        break;
    case 'PUT':
        update_ride();
        break;
    default:
        json_response(['error' => 'Method not allowed'], 405);
}

function get_rides() {
    $user = require_auth();
    $action = $_GET['action'] ?? 'available';
    
    switch($action) {
        case 'available':
            $rides = fetch_all_query(
                'SELECT * FROM rides WHERE status = ? AND shipper_id != ? ORDER BY created_at DESC LIMIT 50',
                ['pending', $user['id']]
            );
            break;
        case 'my_rides':
            $rides = fetch_all_query(
                'SELECT * FROM rides WHERE shipper_id = ? OR driver_id = ? ORDER BY created_at DESC',
                [$user['id'], $user['id']]
            );
            break;
        case 'details':
            $id = $_GET['id'] ?? '';
            $rides = fetch_query('SELECT * FROM rides WHERE id = ?', [$id]);
            break;
        default:
            json_response(['error' => 'Invalid action'], 400);
    }
    
    json_response($rides);
}

function create_ride() {
    $user = require_role('shipper');
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['pickup_location'], $data['dropoff_location'], $data['truck_type'])) {
        json_response(['error' => 'Missing required fields'], 400);
    }
    
    $ride_id = generate_uuid();
    $platform_fee = PLATFORM_FEE;
    
    $result = execute_query(
        'INSERT INTO rides (id, shipper_id, pickup_location, dropoff_location, truck_type, load_description, weight, dimensions, pickup_time, estimated_fare, platform_fee, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())',
        [
            $ride_id,
            $user['id'],
            sanitize($data['pickup_location']),
            sanitize($data['dropoff_location']),
            sanitize($data['truck_type']),
            $data['load_description'] ?? '',
            $data['weight'] ?? 0,
            $data['dimensions'] ?? '',
            $data['pickup_time'] ?? null,
            $data['estimated_fare'] ?? 0,
            $platform_fee,
            'pending'
        ]
    );
    
    if (!$result) {
        json_response(['error' => 'Failed to create ride'], 500);
    }
    
    json_response([
        'message' => 'Ride created successfully',
        'ride_id' => $ride_id
    ], 201);
}

function update_ride() {
    $user = require_auth();
    $data = json_decode(file_get_contents('php://input'), true);
    
    $ride_id = $data['id'] ?? '';
    $action = $data['action'] ?? '';
    
    switch($action) {
        case 'accept':
            $result = execute_query(
                'UPDATE rides SET driver_id = ?, status = ? WHERE id = ?',
                [$user['id'], 'accepted', $ride_id]
            );
            break;
        case 'complete':
            $result = execute_query(
                'UPDATE rides SET status = ?, completed_at = NOW() WHERE id = ?',
                ['completed', $ride_id]
            );
            break;
        case 'cancel':
            $result = execute_query(
                'UPDATE rides SET status = ? WHERE id = ?',
                ['cancelled', $ride_id]
            );
            break;
        default:
            json_response(['error' => 'Invalid action'], 400);
    }
    
    if (!$result) {
        json_response(['error' => 'Failed to update ride'], 500);
    }
    
    json_response(['message' => 'Ride updated successfully']);
}
?>
