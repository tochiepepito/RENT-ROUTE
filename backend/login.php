<?php
/**
 * User Login Handler
 * Handles authentication for both renters and car owners
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
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// Validate inputs
if (empty($email) || empty($password)) {
    http_response_code(BAD_REQUEST);
    die(json_encode(['error' => 'Email and password are required']));
}

// Find account by email
$account = Database::findByEmail($email);

if (!$account) {
    http_response_code(UNAUTHORIZED);
    die(json_encode(['error' => 'Invalid email or password']));
}

// Check if account is active
if (!$account['is_active']) {
    http_response_code(FORBIDDEN);
    die(json_encode(['error' => 'Account is inactive. Please contact support.']));
}

// Verify password
if (!Database::verifyPassword($account, $password)) {
    http_response_code(UNAUTHORIZED);
    die(json_encode(['error' => 'Invalid email or password']));
}

// Set user session
Auth::setUserSession(
    $account['user_id'],
    $account['email'],
    $account['username'],
    $account['role']
);

// Determine redirect URL based on user role
// Both roles redirect to the dynamic dashboard which handles role-based content
$redirect_url = 'RENT&ROUTE/backend/user-dashboard-dynamic.php';

// Return success response with user data and role-specific redirect
http_response_code(SUCCESS);
echo json_encode([
    'success' => true,
    'message' => 'Login successful',
    'user' => [
        'user_id' => $account['user_id'],
        'email' => $account['email'],
        'username' => $account['username'],
        'role' => $account['role']
    ],
    'redirect' => $redirect_url
]);
?>
