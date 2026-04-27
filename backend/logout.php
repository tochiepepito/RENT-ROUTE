<?php
/**
 * User Logout Handler
 * Handles user session termination
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

// Logout user
Auth::logout();

// Redirect to login page
header('Location: ../html/template/login.html');
exit;
?>
