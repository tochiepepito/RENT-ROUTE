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
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$remember = isset($_POST['remember']) ? true : false;

// Validate inputs
if (empty($role)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Please select a role']));
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Please enter a valid email']));
}

if (empty($password)) {
    http_response_code(400);
    die(json_encode(['success' => false, 'error' => 'Please enter a password']));
}

// Sanitize inputs
$role = $conn->real_escape_string($role);
$email = $conn->real_escape_string($email);

// Determine table based on role
$table = ($role === 'car_owner') ? 'car_owners' : 'renters';

// Query to find user
$sql = "SELECT * FROM $table WHERE email = '$email' LIMIT 1";
$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    die(json_encode(['success' => false, 'error' => 'Database query error']));
}

if ($result->num_rows === 0) {
    http_response_code(401);
    die(json_encode(['success' => false, 'error' => 'Email or password is incorrect']));
}

$user = $result->fetch_assoc();

// Verify password
if (!password_verify($password, $user['password'])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'error' => 'Email or password is incorrect']));
}

// Set session variables
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role'] = $role;
$_SESSION['username'] = isset($user['username']) ? $user['username'] : $user['first_name'];

// Handle remember me
if ($remember) {
    $token = bin2hex(random_bytes(32));
    $token_hash = hash('sha256', $token);
    $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
    
    $update_sql = "UPDATE $table SET remember_token = '$token_hash', remember_expires = '$expires' WHERE id = {$user['id']}";
    $conn->query($update_sql);
    
    setcookie('remember_token', $token, strtotime('+30 days'), '/', '', false, true);
}

// Determine redirect based on role
$redirect = ($role === 'car_owner') ? '../html/template/admin/index.html' : 'user-dashboard.html';
$redirect = ($role === 'renter') ? '../html/template/index.html' : 'user-dashboard.html';

echo json_encode([
    'success' => true,
    'message' => 'Login successful',
    'user' => [
        'user_id' => $user['id'],
        'email' => $user['email'],
        'role' => $role,
        'username' => $_SESSION['username']
    ],
    'redirect' => $redirect
]);
?>
