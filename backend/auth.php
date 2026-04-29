<?php
require_once 'config.php';

class Auth {
    
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    public static function getCurrentUser() {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        return [
            'user_id' => $_SESSION['user_id'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role'],
            'username' => $_SESSION['username']
        ];
    }
    
    public static function getUserData() {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        global $conn;
        
        $user_id = $_SESSION['user_id'];
        $role = $_SESSION['user_role'];
        $table = ($role === 'car_owner') ? 'car_owners' : 'renters';
        
        $sql = "SELECT * FROM $table WHERE id = $user_id LIMIT 1";
        $result = $conn->query($sql);
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            header('Location: login.html');
            exit;
        }
    }
    
    public static function requireRole($required_role) {
        if (!self::isLoggedIn()) {
            header('Location: login.html');
            exit;
        }
        
        if ($_SESSION['user_role'] !== $required_role) {
            http_response_code(403);
            header('Location: index.html');
            exit;
        }
    }
    
    public static function logout() {
        session_destroy();
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }
        header('Location: ../html/template/index.html');
        exit;
    }
}
?>
