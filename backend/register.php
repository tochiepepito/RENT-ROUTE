<?php
/**
 * User Registration Handler
 * Handles account creation for both renters and car owners
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/auth.php';

// Set response header
header('Content-Type: application/json');

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(BAD_REQUEST);
    die(json_encode(['error' => 'Invalid request method']));
}

// Get form data
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$role = trim($_POST['role'] ?? '');

// Validate inputs
if (empty($username) || empty($email) || empty($password) || empty($role)) {
    http_response_code(BAD_REQUEST);
    die(json_encode(['error' => 'All fields are required']));
}

// Attempt to create account
$result = Database::createAccount($username, $email, $password, $role);

if ($result['success']) {
    // Set user session
    Auth::setUserSession(
        $result['user_id'],
        $email,
        $username,
        $role
    );
    
    // Return success response
    http_response_code(CREATED);
    echo json_encode([
        'success' => true,
        'message' => 'Account created successfully. Please log in.',
        'user' => [
            'user_id' => $result['user_id'],
            'email' => $email,
            'username' => $username,
            'role' => $role
        ]
    ]);
} else {
    // Return error
    http_response_code(CONFLICT);
    die(json_encode(['error' => $result['error']]));
}
?>
