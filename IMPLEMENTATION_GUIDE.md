# Implementation Guide - Role-Based Authentication

## Quick Start

### 1. Test the System

Open your browser and test the authentication:

1. **Registration Test:**
   - Navigate to `html/template/register.html`
   - Fill in the form with test data
   - Select role (Renter or Car Owner)
   - Click "Sign Up"

2. **Login Test:**
   - Navigate to `html/template/login.html`
   - Enter the credentials from step 1
   - Click "Sign In"

3. **Check Database:**
   - Open `database/accounts.json`
   - You should see your test account

### 2. Use Authentication in Your Pages

#### Example 1: Protect a Page (Require Login)

```php
<?php
require_once '../../backend/auth.php';

// This will redirect to login if user not authenticated
Auth::requireLogin();

// Safe to access $_SESSION now
echo "Welcome " . Auth::getUserEmail();
?>
```

#### Example 2: Role-Based Content

```php
<?php
require_once '../../backend/auth.php';

Auth::requireLogin();

if (Auth::hasRole('car_owner')) {
    echo "You are a car owner!";
} else {
    echo "You are a renter!";
}
?>
```

#### Example 3: Restrict Page to Specific Role

```php
<?php
require_once '../../backend/auth.php';

// Only car owners can access this page
Auth::requireRole('car_owner');

// Rest of page...
?>
```

#### Example 4: Show Different Content

```php
<?php
require_once '../../backend/auth.php';

Auth::requireLogin();

if (Auth::hasRole('renter')) {
    // Show renter-specific UI
    include 'views/renter-dashboard.php';
} else if (Auth::hasRole('car_owner')) {
    // Show car owner-specific UI
    include 'views/car-owner-dashboard.php';
}
?>
```

### 3. JavaScript Integration

#### Fetch User Data via AJAX

```javascript
// Get current user info
fetch('../../backend/user-profile.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Current user:', data.user);
            console.log('Role:', data.user.role);
        }
    });
```

#### Handle Login via AJAX

```javascript
// Manual login (if you want to override form submission)
const email = document.querySelector('input[name="email"]').value;
const password = document.querySelector('input[name="password"]').value;

fetch('../../backend/login.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `email=${email}&password=${password}`
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        window.location.href = data.redirect;
    } else {
        alert('Login failed: ' + data.error);
    }
});
```

### 4. Using Test Endpoint

The `backend/test.php` endpoint helps during development:

```
http://localhost/RENT&ROUTE/backend/test.php

Get all accounts:
http://localhost/RENT&ROUTE/backend/test.php?action=get_all_accounts

Get account by email:
http://localhost/RENT&ROUTE/backend/test.php?action=get_account_by_email&email=user@example.com

Get stats:
http://localhost/RENT&ROUTE/backend/test.php?action=count_by_role

Check current user:
http://localhost/RENT&ROUTE/backend/test.php?action=current_user

Verify password:
http://localhost/RENT&ROUTE/backend/test.php?action=test_password&email=user@example.com&password=testpass
```

## Database Operations

### Create Account Programmatically

```php
<?php
require_once 'backend/database.php';

$result = Database::createAccount(
    'john_doe',
    'john@example.com',
    'SecurePassword123',
    'renter'
);

if ($result['success']) {
    echo "Account created: " . $result['user_id'];
} else {
    echo "Error: " . $result['error'];
}
?>
```

### Query Accounts

```php
<?php
require_once 'backend/database.php';

// Get all renters
$renters = Database::getAccountsByRole('renter');

// Get statistics
$total = Database::getTotalAccounts();
$renters_count = Database::getAccountsCountByRole('renter');
$car_owners_count = Database::getAccountsCountByRole('car_owner');

echo "Total accounts: " . $total;
echo "Renters: " . $renters_count;
echo "Car owners: " . $car_owners_count;
?>
```

### Update User Profile

```php
<?php
require_once 'backend/database.php';

$result = Database::updateAccount('user_123abc_1234567890', [
    'first_name' => 'John',
    'last_name' => 'Doe',
    'phone' => '+1234567890',
    'bio' => 'I love cars!'
]);

if ($result['success']) {
    echo "Profile updated!";
}
?>
```

## Password Security

### Password Requirements

- Minimum 6 characters
- Should contain uppercase and lowercase (recommended)
- Should contain numbers (recommended)
- Should contain special characters (recommended)

### Verify Password

```php
<?php
require_once 'backend/database.php';

$account = Database::findByEmail('user@example.com');
if ($account && Database::verifyPassword($account, 'testpassword')) {
    echo "Password is correct!";
}
?>
```

## Session Management

### Check Session Timeout

```php
<?php
require_once 'backend/auth.php';

if (!Auth::checkSessionTimeout()) {
    echo "Your session has expired. Please login again.";
    header('Location: login.html');
}
?>
```

### Update Session Timeout

Edit `backend/config.php`:

```php
define('SESSION_TIMEOUT', 7200); // 2 hours
```

## Error Handling

The system uses HTTP status codes:

```
200 - Success
201 - Created
400 - Bad Request
401 - Unauthorized
403 - Forbidden
409 - Conflict (duplicate)
500 - Server Error
```

### Handle Errors in JavaScript

```javascript
fetch('../../backend/login.php', {
    method: 'POST',
    body: new FormData(form)
})
.then(response => {
    if (response.status === 401) {
        alert('Invalid credentials');
    } else if (response.status === 409) {
        alert('Email already registered');
    } else if (response.status === 400) {
        alert('Missing required fields');
    }
    return response.json();
})
.then(data => console.log(data));
```

## File Permissions

Make sure your `/database/` folder has write permissions:

**On Linux/Mac:**
```bash
chmod 755 database/
chmod 644 database/accounts.json
```

**On Windows:**
- Right-click folder → Properties → Security → Edit Permissions

## Deployment Checklist

- [ ] Remove `test.php` from production (or restrict access)
- [ ] Set appropriate file permissions on database folder
- [ ] Enable HTTPS on your server
- [ ] Verify database/accounts.json is not publicly accessible
- [ ] Set secure session cookie flags in config.php
- [ ] Add rate limiting for login attempts
- [ ] Add CSRF token validation to forms
- [ ] Test all authentication flows
- [ ] Backup database regularly
- [ ] Monitor error logs

## Troubleshooting

### Issue: Can't access test.php
**Solution:** Verify file permissions and path is correct

### Issue: Password hashing not working
**Solution:** Ensure PHP version 7.2+ (BCrypt is built-in)

### Issue: JSON database not updating
**Solution:** Check database folder write permissions

### Issue: Session not persisting across pages
**Solution:** Ensure `session_start()` is called before output

## Next Steps

1. Convert static HTML pages to PHP with role checks
2. Add CSS styling for different roles
3. Implement email verification
4. Add password reset functionality
5. Create admin panel for user management
6. Add payment integration
7. Implement notification system

---

For more details, see `backend/README.md`
