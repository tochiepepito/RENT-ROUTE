<?php
header('Content-Type: application/json');
require_once 'config.php';

// Destroy session
session_destroy();

// Delete remember token cookie
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

echo json_encode([
    'success' => true,
    'message' => 'Logged out successfully',
    'redirect' => '../html/template/index.html'
]);
?>
