# RENT&ROUTE - Role-Based Authentication System

## Overview

This is a complete role-based authentication system with JSON database support. It allows users to register as either a **Renter** or **Car Owner** and login with role-based access control.

## Features

✅ Role-based registration (Renter / Car Owner)  
✅ JSON database for storing user accounts  
✅ Password hashing with BCrypt  
✅ Session-based authentication  
✅ Role-based access control  
✅ User profile management  
✅ Admin dashboard statistics  

## Directory Structure

```
RENT&ROUTE/
├── backend/
│   ├── config.php           # Configuration & constants
│   ├── auth.php             # Authentication class
│   ├── database.php         # Database operations class
│   ├── register.php         # Registration handler
│   ├── login.php            # Login handler
│   ├── logout.php           # Logout handler
│   ├── user-profile.php     # User profile API
│   ├── admin-dashboard.php  # Admin statistics API
│   └── helpers.php          # Utility functions (optional)
├── database/
│   └── accounts.json        # User accounts database
└── html/template/
    ├── register.html        # Registration page (with role selection)
    ├── login.html           # Login page
    ├── user-dashboard.html  # User dashboard
    └── ...other pages
```

## Database Structure

### accounts.json

```json
{
  "accounts": [
    {
      "user_id": "user_123abc_1234567890",
      "username": "john_doe",
      "email": "john@example.com",
      "password": "$2y$10$hashed_password_here",
      "role": "renter",
      "created_at": "2024-04-26 10:30:00",
      "updated_at": "2024-04-26 10:30:00",
      "is_active": true,
      "profile": {
        "first_name": "John",
        "last_name": "Doe",
        "phone": "+1234567890",
        "profile_picture": "",
        "bio": ""
      }
    }
  ]
}
```

## User Roles

### 1. Renter (`renter`)
- Can browse and book available cars
- Manage their bookings
- View payment history
- Rate car owners

### 2. Car Owner (`car_owner`)
- Can list their cars
- Manage rental availability
- View booking requests
- Manage payments

## API Endpoints

### Registration
**URL:** `backend/register.php`  
**Method:** `POST`  

**Form Data:**
```
role=renter|car_owner
username=<username>
email=<email>
password=<password>
```

**Response:**
```json
{
  "user_id": "user_123abc_1234567890",
  "message": "Account created successfully"
}
```

### Login
**URL:** `backend/login.php`  
**Method:** `POST`  

**Form Data:**
```
email=<email>
password=<password>
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "user": {
    "user_id": "user_123abc_1234567890",
    "email": "user@example.com",
    "username": "john_doe",
    "role": "renter"
  },
  "redirect": "user-dashboard.html"
}
```

### Logout
**URL:** `backend/logout.php`  
**Method:** `GET/POST`  

Destroys session and redirects to login page.

### User Profile (GET)
**URL:** `backend/user-profile.php`  
**Method:** `GET`  
**Requires:** Logged-in user

**Response:**
```json
{
  "success": true,
  "user": {
    "user_id": "...",
    "username": "...",
    "email": "...",
    "role": "...",
    "profile": {...}
  }
}
```

### User Profile (UPDATE)
**URL:** `backend/user-profile.php`  
**Method:** `PUT`  
**Requires:** Logged-in user

**Request Body:**
```json
{
  "first_name": "John",
  "last_name": "Doe",
  "phone": "+1234567890",
  "bio": "Car enthusiast"
}
```

### Admin Dashboard
**URL:** `backend/admin-dashboard.php`  
**Method:** `GET`  
**Requires:** Logged-in user

**Response:**
```json
{
  "success": true,
  "statistics": {
    "total_accounts": 5,
    "renters": 3,
    "car_owners": 2
  }
}
```

## Using the Auth Class

### Check if user is logged in
```php
require_once 'backend/auth.php';

if (Auth::isLoggedIn()) {
    // User is logged in
}
```

### Check user role
```php
if (Auth::hasRole('renter')) {
    // User is a renter
}

if (Auth::hasAnyRole(['renter', 'car_owner'])) {
    // User is either renter or car owner
}
```

### Require authentication
```php
Auth::requireLogin(); // Redirects to login if not authenticated
Auth::requireRole('car_owner'); // Requires car_owner role
Auth::requireAnyRole(['admin', 'car_owner']); // Requires one of these roles
```

### Get current user info
```php
$user_id = Auth::getUserId();
$email = Auth::getUserEmail();
$role = Auth::getUserRole();
$user = Auth::getCurrentUser();
```

### Logout user
```php
Auth::logout();
```

## Using the Database Class

### Find user by email
```php
require_once 'backend/database.php';

$user = Database::findByEmail('user@example.com');
```

### Find user by username
```php
$user = Database::findByUsername('john_doe');
```

### Get all renters
```php
$renters = Database::getAccountsByRole('renter');
```

### Get statistics
```php
$total = Database::getTotalAccounts();
$renters_count = Database::getAccountsCountByRole('renter');
$car_owners_count = Database::getAccountsCountByRole('car_owner');
```

### Update user profile
```php
$result = Database::updateAccount('user_123abc_1234567890', [
    'first_name' => 'John',
    'last_name' => 'Doe',
    'phone' => '+1234567890'
]);
```

## Security Features

✅ Passwords are hashed using BCrypt  
✅ Email validation before registration  
✅ Username uniqueness check  
✅ Email uniqueness check  
✅ Session-based authentication  
✅ Role-based access control  
✅ CSRF protection (recommended to add)  
✅ Input sanitization with htmlspecialchars()  

## Session Configuration

- **Session Timeout:** 1 hour (3600 seconds)
- **Session Name:** `rentroute_session`

To change timeout, edit `config.php`:
```php
define('SESSION_TIMEOUT', 7200); // 2 hours
```

## Installation Steps

1. **Ensure PHP is installed** and running on your server
2. **Database folder permissions:** Make sure `/database/` folder is writable
3. **Update form actions:** Ensure HTML forms point to correct backend paths
4. **Update redirects:** Verify redirect paths work with your server setup
5. **Test registration:** Create a test account to verify JSON database creation

## Testing

### Manual Testing

1. **Register a new user:**
   - Go to `html/template/register.html`
   - Select role (Renter or Car Owner)
   - Fill in username, email, password
   - Click "Sign Up"
   - Check `database/accounts.json` to verify account was created

2. **Login:**
   - Go to `html/template/login.html`
   - Enter email and password
   - Click "Sign In"
   - Verify session is set and user is redirected

3. **Check account data:**
   - Open `database/accounts.json`
   - Verify password is hashed
   - Verify role is stored correctly

### Using PHP Functions

```php
<?php
// Test from any PHP file
require_once 'backend/config.php';
require_once 'backend/database.php';
require_once 'backend/auth.php';

// Create test account
$result = Database::createAccount('testuser', 'test@example.com', 'password123', 'renter');
echo json_encode($result);

// Find account
$user = Database::findByEmail('test@example.com');
echo json_encode($user);
?>
```

## Error Handling

The system returns appropriate HTTP status codes:

- `200` - Success
- `201` - Created
- `400` - Bad Request (missing fields)
- `401` - Unauthorized (invalid credentials)
- `403` - Forbidden (access denied)
- `409` - Conflict (duplicate email/username)
- `500` - Server Error

## Next Steps

1. **Add password reset functionality** - Create `backend/reset-password.php`
2. **Add email verification** - Send confirmation emails to new users
3. **Add admin panel** - Create admin interface for user management
4. **Add CSRF protection** - Implement token-based CSRF prevention
5. **Add logging** - Log all authentication attempts
6. **Add rate limiting** - Prevent brute force attacks
7. **Migrate to database** - Consider upgrading to MySQL/PostgreSQL

## Troubleshooting

### Issue: "Call to undefined function" errors
**Solution:** Ensure all `require_once` statements are pointing to correct paths

### Issue: "Permission denied" when creating accounts
**Solution:** Make sure `/database/` folder has write permissions (chmod 755 or 777)

### Issue: Sessions not persisting
**Solution:** Verify `php.ini` has session settings configured and cookie settings are correct

### Issue: Password verification failing
**Solution:** Ensure password hashing uses consistent algorithm (BCrypt)

## Security Recommendations

⚠️ **Important Security Notes:**

1. **Enable HTTPS** - Always use HTTPS in production
2. **Set secure cookie flags** - Add to config.php:
   ```php
   session_set_cookie_params(['secure' => true, 'httponly' => true, 'samesite' => 'Strict']);
   ```
3. **Add rate limiting** - Prevent brute force login attempts
4. **Implement CSRF tokens** - Add token validation to forms
5. **Use environment variables** - Don't hardcode sensitive data
6. **Regular backups** - Backup `database/accounts.json` regularly
7. **Monitor failed login attempts** - Log and track suspicious activities

## Support

For issues or questions, please check the comments in the PHP files for detailed documentation of each function.

---

**Last Updated:** April 26, 2024  
**System:** Native PHP with JSON Database  
**PHP Version:** 7.4+ recommended
