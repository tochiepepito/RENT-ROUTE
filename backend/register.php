<?php
header('Content-Type: application/json');
require_once 'config.php';

// Only process POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['success' => false, 'error' => 'Method not allowed']));
}

// Get input data
$role = isset($_POST['role']) ? trim($_POST['role']) : '';
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Validate inputs
if (empty($role)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Please select a role']));
}

if (empty($username) || strlen($username) < 3) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Username must be at least 3 characters long']));
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Please enter a valid email']));
}

if (empty($password) || strlen($password) < 6) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Password must be at least 6 characters long']));
}

// Sanitize inputs
$role = $conn->real_escape_string($role);
$username = $conn->real_escape_string($username);
$email = $conn->real_escape_string($email);

// Determine table based on role
$table = ($role === 'car_owner') ? 'car_owners' : 'renters';

// Check if email already exists
$check_sql = "SELECT id FROM $table WHERE email = '$email' LIMIT 1";
$check_result = $conn->query($check_sql);

if ($check_result && $check_result->num_rows > 0) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Email already registered']));
}

// Check if username already exists
$check_username_sql = "SELECT id FROM $table WHERE username = '$username' LIMIT 1";
$check_username_result = $conn->query($check_username_sql);

if ($check_username_result && $check_username_result->num_rows > 0) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Username already taken']));
}

// Hash password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Prepare insert query based on role
if ($role === 'car_owner') {
    $insert_sql = "INSERT INTO car_owners (username, email, password, created_at) VALUES ('$username', '$email', '$password_hash', NOW())";
} else {
    $insert_sql = "INSERT INTO renters (username, email, password, created_at) VALUES ('$username', '$email', '$password_hash', NOW())";
}

// Execute insert
if ($conn->query($insert_sql) === TRUE) {
    $user_id = $conn->insert_id;
    
    echo json_encode([
        'success' => true,
        'message' => 'Registration successful. Please login with your credentials.',
        'user_id' => $user_id,
        'role' => $role,
        'redirect' => 'login.html'
    ]);
} else {
    http_response_code(500);
    die(json_encode(['success' => false, 'error' => 'Registration failed. Please try again later.']));
}

$conn->close();
?>
