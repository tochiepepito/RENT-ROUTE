<?php
header('Content-Type: application/json');

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'rent_route';

$database_exists = false;
$tables_exist = false;

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass);

if (!$conn->connect_error) {
    // Check if database exists
    $result = $conn->query("SHOW DATABASES LIKE '$db_name'");
    $database_exists = ($result && $result->num_rows > 0);
    
    if ($database_exists) {
        // Select database
        $conn->select_db($db_name);
        
        // Check if required tables exist
        $tables = array('renters', 'car_owners', 'cars', 'bookings', 'payments', 'reviews');
        $all_tables_exist = true;
        
        foreach ($tables as $table) {
            $result = $conn->query("SHOW TABLES LIKE '$table'");
            if (!$result || $result->num_rows === 0) {
                $all_tables_exist = false;
                break;
            }
        }
        
        $tables_exist = $all_tables_exist;
    }
    
    $conn->close();
}

echo json_encode([
    'database_exists' => $database_exists,
    'tables_exist' => $tables_exist,
    'setup_complete' => ($database_exists && $tables_exist)
]);
?>
