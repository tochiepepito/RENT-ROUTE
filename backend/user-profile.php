<?php
/**
 * User Profile API
 * Handles profile retrieval and updates
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/auth.php';

// Define HTTP status codes
define('NOT_FOUND', 404);
define('SUCCESS', 200);
define('SERVER_ERROR', 500);
define('BAD_REQUEST', 400);

// Set response header
header('Content-Type: application/json');

// Check if user is logged in
Auth::requireLogin();

$user_id = Auth::getUserId();
$user_role = Auth::getUserRole();

// Handle different request methods
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get user profile
    $account = Database::findById($user_id);
    
    if (!$account) {
        http_response_code(NOT_FOUND);
        die(json_encode(['error' => 'User not found']));
    }
    
    // Remove sensitive data
    unset($account['password']);
    
    http_response_code(SUCCESS);
    echo json_encode([
        'success' => true,
        'user' => $account
    ]);
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Update user profile
    $input = json_decode(file_get_contents('php://input'), true);
    
    $result = Database::updateAccount($user_id, $input);
    
    if ($result['success']) {
        http_response_code(SUCCESS);
        echo json_encode($result);
    } else {
        http_response_code(SERVER_ERROR);
        echo json_encode($result);
    }
    
} else {
    http_response_code(BAD_REQUEST);
    die(json_encode(['error' => 'Invalid request method']));
}
?>
