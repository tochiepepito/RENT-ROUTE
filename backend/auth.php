<?php
/**
 * Authentication & Authorization Handler
 * Manages user sessions, role verification, and access control
 */

require_once __DIR__ . '/config.php';

class Auth {
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && isset($_SESSION['role']) && isset($_SESSION['email']);
    }
    
    /**
     * Check if user has a specific role
     */
    public static function hasRole($role) {
        if (!self::isLoggedIn()) {
            return false;
        }
        return $_SESSION['role'] === $role;
    }
    
    /**
     * Check if user has any of the specified roles
     */
    public static function hasAnyRole($roles = []) {
        if (!self::isLoggedIn()) {
            return false;
        }
        return in_array($_SESSION['role'], $roles);
    }
    
    /**
     * Get current user ID
     */
    public static function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Get current user role
     */
    public static function getUserRole() {
        return $_SESSION['role'] ?? null;
    }
    
    /**
     * Get current user email
     */
    public static function getUserEmail() {
        return $_SESSION['email'] ?? null;
    }
    
    /**
     * Get current user data
     */
    public static function getCurrentUser() {
        if (!self::isLoggedIn()) {
            return null;
        }
        return [
            'user_id' => $_SESSION['user_id'],
            'email' => $_SESSION['email'],
            'username' => $_SESSION['username'] ?? null,
            'role' => $_SESSION['role']
        ];
    }
    
    /**
     * Set user session
     */
    public static function setUserSession($user_id, $email, $username, $role) {
        $_SESSION['user_id'] = $user_id;
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        $_SESSION['login_time'] = time();
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        session_destroy();
        $_SESSION = [];
    }
    
    /**
     * Require authentication
     */
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: RENT&ROUTE/html/template/login.html');
            exit;
        }
    }
    
    /**
     * Require specific role
     */
    public static function requireRole($role) {
        self::requireLogin();
        if (!self::hasRole($role)) {
            http_response_code(FORBIDDEN);
            die(json_encode(['error' => 'Access denied. Required role: ' . $role]));
        }
    }
    
    /**
     * Require any of the specified roles
     */
    public static function requireAnyRole($roles = []) {
        self::requireLogin();
        if (!self::hasAnyRole($roles)) {
            http_response_code(FORBIDDEN);
            die(json_encode(['error' => 'Access denied. Required roles: ' . implode(', ', $roles)]));
        }
    }
    
    /**
     * Check session timeout
     */
    public static function checkSessionTimeout() {
        if (self::isLoggedIn()) {
            $login_time = $_SESSION['login_time'] ?? time();
            if (time() - $login_time > SESSION_TIMEOUT) {
                self::logout();
                return false;
            }
        }
        return true;
    }
}
?>
