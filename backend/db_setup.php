<?php
header('Content-Type: application/json');

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'rent_route';

// Create connection without selecting database first
$conn = new mysqli($db_host, $db_user, $db_pass);

if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]));
}

// Read and execute setup SQL
$sql_file = dirname(__FILE__) . '/setup.sql';

if (!file_exists($sql_file)) {
    http_response_code(500);
    die(json_encode(['success' => false, 'error' => 'setup.sql file not found']));
}

$sql_content = file_get_contents($sql_file);

// Split SQL statements by semicolon and execute each one
$statements = array_filter(array_map('trim', explode(';', $sql_content)));

$conn->set_charset("utf8");

foreach ($statements as $statement) {
    if (empty($statement)) continue;
    
    if (!$conn->query($statement)) {
        // Continue on some errors, but track them
        // Some statements might fail if they already exist
        if (strpos($conn->error, 'already exists') === false) {
            error_log('SQL Error: ' . $conn->error . ' | Statement: ' . $statement);
        }
    }
}

// Verify tables were created
$tables = array('renters', 'car_owners', 'cars', 'bookings', 'payments', 'reviews');
$missing_tables = array();

foreach ($tables as $table) {
    $result = $conn->query("SHOW TABLES LIKE '$table'");
    if ($result->num_rows === 0) {
        $missing_tables[] = $table;
    }
}

$conn->close();

if (empty($missing_tables)) {
    echo json_encode([
        'success' => true,
        'message' => 'Database setup completed successfully',
        'tables_created' => count($tables)
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Some tables were not created: ' . implode(', ', $missing_tables)
    ]);
}
?>
