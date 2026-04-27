<?php
/**
 * Test Helper Script
 * Use this to test the authentication system
 * WARNING: Only for development/testing. Remove in production.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/auth.php';

// Set response header
header('Content-Type: application/json');

// Get action from query parameter
$action = $_GET['action'] ?? '';

if (empty($action)) {
    echo json_encode([
        'available_actions' => [
            'get_all_accounts' => 'Get all registered accounts',
            'get_account_by_email' => 'Get account by email (pass ?email=value)',
            'count_by_role' => 'Get count of accounts by role',
            'reset_database' => 'Reset database (WARNING: Deletes all data)',
            'test_password' => 'Test password hashing (pass ?email=value&password=value)',
            'current_user' => 'Get current logged in user'
        ]
    ]);
    exit;
}

switch ($action) {
    case 'get_all_accounts':
        $accounts = Database::getAllAccounts();
        // Remove passwords for security
        array_walk($accounts, function(&$account) {
            unset($account['password']);
        });
        echo json_encode($accounts);
        break;
        
    case 'get_account_by_email':
        if (empty($_GET['email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Email parameter required']);
            break;
        }
        $account = Database::findByEmail($_GET['email']);
        if ($account) {
            unset($account['password']);
            echo json_encode($account);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Account not found']);
        }
        break;
        
    case 'count_by_role':
        echo json_encode([
            'total' => Database::getTotalAccounts(),
            'renters' => Database::getAccountsCountByRole(ROLE_RENTER),
            'car_owners' => Database::getAccountsCountByRole(ROLE_CAR_OWNER)
        ]);
        break;
        
    case 'reset_database':
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            echo json_encode(['error' => 'POST request required']);
            break;
        }
        
        $empty_data = json_encode(['accounts' => []], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if (file_put_contents(ACCOUNTS_FILE, $empty_data)) {
            echo json_encode(['success' => true, 'message' => 'Database reset successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to reset database']);
        }
        break;
        
    case 'test_password':
        if (empty($_GET['email']) || empty($_GET['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Email and password parameters required']);
            break;
        }
        
        $account = Database::findByEmail($_GET['email']);
        if (!$account) {
            http_response_code(404);
            echo json_encode(['error' => 'Account not found']);
            break;
        }
        
        $verified = Database::verifyPassword($account, $_GET['password']);
        echo json_encode([
            'email' => $_GET['email'],
            'password_verified' => $verified,
            'account' => [
                'user_id' => $account['user_id'],
                'username' => $account['username'],
                'role' => $account['role']
            ]
        ]);
        break;
        
    case 'current_user':
        if (Auth::isLoggedIn()) {
            echo json_encode([
                'logged_in' => true,
                'user' => Auth::getCurrentUser()
            ]);
        } else {
            echo json_encode([
                'logged_in' => false,
                'message' => 'No user logged in'
            ]);
        }
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Unknown action: ' . htmlspecialchars($action)]);
}
?>
