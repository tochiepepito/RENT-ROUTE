<?php
/**
 * Admin Dashboard API
 * Returns statistics and account management data
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/auth.php';

// Set response header
header('Content-Type: application/json');

// Check if user is logged in
Auth::requireLogin();

// Note: This is a basic implementation
// You may want to add an admin role verification

$total_accounts = Database::getTotalAccounts();
$renters_count = Database::getAccountsCountByRole(ROLE_RENTER);
$car_owners_count = Database::getAccountsCountByRole(ROLE_CAR_OWNER);

http_response_code(SUCCESS);
echo json_encode([
    'success' => true,
    'statistics' => [
        'total_accounts' => $total_accounts,
        'renters' => $renters_count,
        'car_owners' => $car_owners_count
    ]
]);
?>
