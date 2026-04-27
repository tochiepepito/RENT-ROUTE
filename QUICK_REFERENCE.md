# RENT&ROUTE Auth System - Quick Reference

## 🚀 Quickstart

### Registration Form
```html
<form action="../../backend/register.php" method="POST">
    <select name="role" required>
        <option value="renter">Renter</option>
        <option value="car_owner">Car Owner</option>
    </select>
    <input type="text" name="username" required>
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button type="submit">Register</button>
</form>
```

### Login Form
```html
<form action="../../backend/login.php" method="POST">
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button type="submit">Login</button>
</form>
```

## 🔐 PHP Authentication

### Check if Logged In
```php
require_once '../../backend/auth.php';
if (Auth::isLoggedIn()) {
    echo "User is logged in";
}
```

### Get User Info
```php
$user_id = Auth::getUserId();
$email = Auth::getUserEmail();
$role = Auth::getUserRole();
$user = Auth::getCurrentUser();
```

### Check User Role
```php
if (Auth::hasRole('renter')) {
    // Renter-specific code
}

if (Auth::hasAnyRole(['renter', 'car_owner'])) {
    // Both roles
}
```

### Require Authentication
```php
Auth::requireLogin();                    // Redirect if not logged in
Auth::requireRole('car_owner');          // Require specific role
Auth::requireAnyRole(['admin', 'owner']); // Require one of roles
```

### Logout
```php
Auth::logout();
```

## 📊 Database Operations

### Create Account
```php
require_once '../../backend/database.php';

$result = Database::createAccount(
    'username',
    'email@example.com',
    'password123',
    'renter'
);
```

### Find User
```php
$user = Database::findByEmail('email@example.com');
$user = Database::findByUsername('username');
$user = Database::findById('user_123abc_1234567890');
```

### Get Statistics
```php
$total = Database::getTotalAccounts();
$renters = Database::getAccountsCountByRole('renter');
$owners = Database::getAccountsCountByRole('car_owner');
```

### Update Profile
```php
Database::updateAccount('user_id', [
    'first_name' => 'John',
    'last_name' => 'Doe',
    'phone' => '+1234567890'
]);
```

### Get by Role
```php
$renters = Database::getAccountsByRole('renter');
$owners = Database::getAccountsByRole('car_owner');
```

## 🌐 API Responses

### Register Success
```json
{
  "user_id": "user_123abc_1234567890",
  "message": "Account created successfully"
}
```

### Register Error
```json
{
  "error": "Email already registered"
}
```

### Login Success
```json
{
  "success": true,
  "message": "Login successful",
  "user": {
    "user_id": "...",
    "email": "...",
    "username": "...",
    "role": "renter"
  },
  "redirect": "user-dashboard.html"
}
```

### Login Error
```json
{
  "error": "Invalid email or password"
}
```

## 📋 Page Protection Examples

### Protected Page (Require Login)
```php
<?php
require_once '../../backend/auth.php';
Auth::requireLogin();
// Page content here
?>
```

### Role-Specific Content
```php
<?php
require_once '../../backend/auth.php';
Auth::requireLogin();

if (Auth::hasRole('renter')) {
    echo "Renter Dashboard";
} else {
    echo "Car Owner Dashboard";
}
?>
```

### Restrict to Car Owner Only
```php
<?php
require_once '../../backend/auth.php';
Auth::requireRole('car_owner');
// Only car owners can see this
?>
```

## 🧪 Testing

### Check All Accounts
```
GET backend/test.php?action=get_all_accounts
```

### Get Statistics
```
GET backend/test.php?action=count_by_role
```

### Check Current User
```
GET backend/test.php?action=current_user
```

### Reset Database
```
POST backend/test.php?action=reset_database
```

## 🔒 Security Checklist

- [ ] Remove test.php from production
- [ ] Set database folder permissions to 755
- [ ] Enable HTTPS
- [ ] Add CSRF tokens to forms
- [ ] Implement rate limiting
- [ ] Add login attempt logging
- [ ] Regular database backups
- [ ] Use strong session cookies
- [ ] Validate all inputs
- [ ] Use parameterized queries (when using SQL)

## 📞 Roles

### Renter
```
role = 'renter'
```
- Browse cars
- Make bookings
- View history
- Rate owners

### Car Owner
```
role = 'car_owner'
```
- Add listings
- Manage rentals
- Accept/decline requests
- View earnings

## 🐛 Common Issues

### "Call to undefined function"
→ Check file paths in require_once

### "Permission denied"
→ Make database folder writable: `chmod 755 database/`

### Session not working
→ Verify session.save_path in php.ini

### Password verification fails
→ Ensure BCrypt is available (PHP 5.5+)

## 📝 Configuration

Edit `backend/config.php`:

```php
// Session timeout (seconds)
define('SESSION_TIMEOUT', 3600);

// Session name
define('SESSION_NAME', 'rentroute_session');

// Valid roles
define('ROLE_RENTER', 'renter');
define('ROLE_CAR_OWNER', 'car_owner');
```

## 🎓 Examples

### Check and Display Role-Based Content
```php
<?php
require_once '../../backend/auth.php';
require_once '../../backend/database.php';

Auth::requireLogin();

$user = Auth::getCurrentUser();
$account = Database::findById($user['user_id']);
?>

<h1>Welcome, <?php echo htmlspecialchars($account['username']); ?></h1>

<?php if ($user['role'] === 'renter'): ?>
    <p>You are browsing as a Renter</p>
    <a href="listing-grid.html">Browse Cars</a>
<?php else: ?>
    <p>You are browsing as a Car Owner</p>
    <a href="add-listing.html">Add Your Car</a>
<?php endif; ?>

<a href="../../backend/logout.php">Logout</a>
```

### Login Status Bar
```php
<?php require_once '../../backend/auth.php'; ?>

<div class="header">
    <?php if (Auth::isLoggedIn()): ?>
        <span>Hello, <?php echo Auth::getUserEmail(); ?></span>
        <a href="../../backend/logout.php">Logout</a>
    <?php else: ?>
        <a href="login.html">Login</a>
        <a href="register.html">Register</a>
    <?php endif; ?>
</div>
```

## 📚 Files Overview

| File | Purpose |
|------|---------|
| config.php | Constants & configuration |
| auth.php | Authentication class |
| database.php | Database operations |
| register.php | Registration handler |
| login.php | Login handler |
| logout.php | Logout handler |
| user-profile.php | Profile API |
| test.php | Development testing |
| helpers.php | Utility functions |
| accounts.json | User database |

---

**Last Updated:** April 26, 2024  
**Version:** 1.0  
**Created for:** RENT&ROUTE Project
