<?php
/**
 * Helper Functions
 * Utility functions for common operations
 */

require_once __DIR__ . '/config.php';

/**
 * Send JSON response
 */
function sendJsonResponse($data, $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Sanitize input
 */
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate password strength
 */
function validatePassword($password) {
    $errors = [];
    
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password must contain at least one uppercase letter';
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password must contain at least one lowercase letter';
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password must contain at least one number';
    }
    
    return $errors;
}

/**
 * Generate random token
 */
function generateToken($length = 32) {
    return bin2hex(random_bytes($length / 2));
}

/**
 * Get client IP address
 */
function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

/**
 * Format date
 */
function formatDate($date, $format = 'Y-m-d H:i:s') {
    $timestamp = strtotime($date);
    return date($format, $timestamp);
}

/**
 * Redirect with message
 */
function redirectWithMessage($url, $message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
    header('Location: ' . $url);
    exit;
}

/**
 * Get and clear message
 */
function getAndClearMessage() {
    $message = $_SESSION['message'] ?? null;
    $type = $_SESSION['message_type'] ?? 'info';
    
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
    
    return ['message' => $message, 'type' => $type];
}

/**
 * Log event
 */
function logEvent($event_type, $user_id = null, $details = '') {
    $log_file = dirname(__DIR__) . '/logs/events.log';
    
    if (!is_dir(dirname($log_file))) {
        mkdir(dirname($log_file), 0755, true);
    }
    
    $log_entry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'event_type' => $event_type,
        'user_id' => $user_id,
        'ip_address' => getClientIP(),
        'details' => $details
    ];
    
    $log_line = json_encode($log_entry) . PHP_EOL;
    file_put_contents($log_file, $log_line, FILE_APPEND);
}

/**
 * Rate limit check
 */
function isRateLimited($identifier, $max_attempts = 5, $time_window = 300) {
    $cache_file = sys_get_temp_dir() . '/ratelimit_' . md5($identifier) . '.json';
    
    if (file_exists($cache_file)) {
        $data = json_decode(file_get_contents($cache_file), true);
        $now = time();
        
        // Reset if time window has passed
        if ($now - $data['first_attempt'] > $time_window) {
            unlink($cache_file);
            return false;
        }
        
        if ($data['attempts'] >= $max_attempts) {
            return true;
        }
        
        // Increment attempts
        $data['attempts']++;
        file_put_contents($cache_file, json_encode($data));
    } else {
        // First attempt
        file_put_contents($cache_file, json_encode([
            'first_attempt' => time(),
            'attempts' => 1
        ]));
    }
    
    return false;
}

/**
 * Check if request is AJAX
 */
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Get time ago string
 */
function getTimeAgo($date) {
    $timestamp = strtotime($date);
    $seconds = time() - $timestamp;
    
    $intervals = [
        'year' => 31536000,
        'month' => 2592000,
        'week' => 604800,
        'day' => 86400,
        'hour' => 3600,
        'minute' => 60,
        'second' => 1
    ];
    
    foreach ($intervals as $name => $value) {
        if ($seconds >= $value) {
            $num = floor($seconds / $value);
            return $num . ' ' . $name . ($num > 1 ? 's' : '') . ' ago';
        }
    }
    
    return 'just now';
}
?>
