<?php
/**
 * JSON Database Handler
 * Manages all database operations for accounts
 */

require_once __DIR__ . '/config.php';

class Database {
    
    /**
     * Read all accounts from JSON file
     */
    public static function getAllAccounts() {
        if (!file_exists(ACCOUNTS_FILE)) {
            return [];
        }
        $json = file_get_contents(ACCOUNTS_FILE);
        $data = json_decode($json, true);
        return isset($data['accounts']) ? $data['accounts'] : [];
    }
    
    /**
     * Save accounts to JSON file
     */
    private static function saveAccounts($accounts) {
        $data = ['accounts' => $accounts];
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return file_put_contents(ACCOUNTS_FILE, $json) !== false;
    }
    
    /**
     * Find account by email
     */
    public static function findByEmail($email) {
        $accounts = self::getAllAccounts();
        foreach ($accounts as $account) {
            if ($account['email'] === $email) {
                return $account;
            }
        }
        return null;
    }
    
    /**
     * Find account by username
     */
    public static function findByUsername($username) {
        $accounts = self::getAllAccounts();
        foreach ($accounts as $account) {
            if ($account['username'] === $username) {
                return $account;
            }
        }
        return null;
    }
    
    /**
     * Find account by ID
     */
    public static function findById($user_id) {
        $accounts = self::getAllAccounts();
        foreach ($accounts as $account) {
            if ($account['user_id'] === $user_id) {
                return $account;
            }
        }
        return null;
    }
    
    /**
     * Create new account
     */
    public static function createAccount($username, $email, $password, $role) {
        // Validate inputs
        if (empty($username) || empty($email) || empty($password) || empty($role)) {
            return ['success' => false, 'error' => 'All fields are required'];
        }
        
        if (!in_array($role, VALID_ROLES)) {
            return ['success' => false, 'error' => 'Invalid role selected'];
        }
        
        // Check if email already exists
        if (self::findByEmail($email)) {
            return ['success' => false, 'error' => 'Email already registered'];
        }
        
        // Check if username already exists
        if (self::findByUsername($username)) {
            return ['success' => false, 'error' => 'Username already taken'];
        }
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Invalid email format'];
        }
        
        // Validate password strength
        if (strlen($password) < 6) {
            return ['success' => false, 'error' => 'Password must be at least 6 characters'];
        }
        
        // Get existing accounts
        $accounts = self::getAllAccounts();
        
        // Generate unique ID
        $user_id = 'user_' . uniqid() . '_' . time();
        
        // Create new account
        $new_account = [
            'user_id' => $user_id,
            'username' => htmlspecialchars($username),
            'email' => strtolower($email),
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'role' => $role,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'is_active' => true,
            'profile' => [
                'first_name' => '',
                'last_name' => '',
                'phone' => '',
                'profile_picture' => '',
                'bio' => ''
            ]
        ];
        
        // Add to accounts array
        $accounts[] = $new_account;
        
        // Save to file
        if (self::saveAccounts($accounts)) {
            return ['success' => true, 'user_id' => $user_id, 'message' => 'Account created successfully'];
        } else {
            return ['success' => false, 'error' => 'Failed to create account'];
        }
    }
    
    /**
     * Verify password
     */
    public static function verifyPassword($account, $password) {
        return password_verify($password, $account['password']);
    }
    
    /**
     * Update account
     */
    public static function updateAccount($user_id, $data) {
        $accounts = self::getAllAccounts();
        $found = false;
        
        foreach ($accounts as &$account) {
            if ($account['user_id'] === $user_id) {
                // Update allowed fields
                if (isset($data['first_name'])) $account['profile']['first_name'] = $data['first_name'];
                if (isset($data['last_name'])) $account['profile']['last_name'] = $data['last_name'];
                if (isset($data['phone'])) $account['profile']['phone'] = $data['phone'];
                if (isset($data['bio'])) $account['profile']['bio'] = $data['bio'];
                
                $account['updated_at'] = date('Y-m-d H:i:s');
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            return ['success' => false, 'error' => 'Account not found'];
        }
        
        if (self::saveAccounts($accounts)) {
            return ['success' => true, 'message' => 'Account updated successfully'];
        } else {
            return ['success' => false, 'error' => 'Failed to update account'];
        }
    }
    
    /**
     * Get accounts by role
     */
    public static function getAccountsByRole($role) {
        $accounts = self::getAllAccounts();
        return array_filter($accounts, function($account) use ($role) {
            return $account['role'] === $role;
        });
    }
    
    /**
     * Get total accounts
     */
    public static function getTotalAccounts() {
        return count(self::getAllAccounts());
    }
    
    /**
     * Get accounts count by role
     */
    public static function getAccountsCountByRole($role) {
        $accounts = self::getAccountsByRole($role);
        return count($accounts);
    }
}
?>
