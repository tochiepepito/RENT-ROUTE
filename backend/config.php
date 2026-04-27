<?php
/**
 * Configuration File
 * Sets up paths and database connections
 */

// Define base paths
define('BASE_PATH', dirname(dirname(__FILE__)));
define('DATABASE_PATH', BASE_PATH . '/database');
define('ACCOUNTS_FILE', DATABASE_PATH . '/accounts.json');

// Session configuration
define('SESSION_TIMEOUT', 3600); // 1 hour
define('SESSION_NAME', 'rentroute_session');

// Response codes
define('SUCCESS', 200);
define('CREATED', 201);
define('BAD_REQUEST', 400);
define('UNAUTHORIZED', 401);
define('FORBIDDEN', 403);
define('CONFLICT', 409);
define('SERVER_ERROR', 500);

// Available roles
define('ROLE_RENTER', 'renter');
define('ROLE_CAR_OWNER', 'car_owner');
define('VALID_ROLES', [ROLE_RENTER, ROLE_CAR_OWNER]);

// Currency Configuration
define('CURRENCY', 'PHP');
define('CURRENCY_SYMBOL', '₱');
define('CURRENCY_NAME', 'Philippine Peso');

// Location Configuration
define('DEFAULT_COUNTRY', 'Philippines');
define('DEFAULT_REGION', 'Metro Manila');
define('SUPPORTED_CITIES', ['Manila', 'Makati', 'Quezon City', 'Cebu', 'Davao', 'Iloilo', 'Bacolod', 'Cagayan de Oro']);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
