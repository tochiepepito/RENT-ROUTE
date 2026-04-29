# RENT&ROUTE Backend Setup Guide

## Initial Setup Instructions

### 1. Database Setup

1. Open phpMyAdmin (usually at http://localhost/phpmyadmin)
2. Click "Import" tab
3. Select the `setup.sql` file from this backend folder
4. Click "Go" to import and create the database and tables

**Alternative Method (Direct SQL):**
- Copy the contents of `setup.sql`
- Paste into the SQL tab in phpMyAdmin
- Execute

### 2. Database Configuration

The database credentials are configured in `config.php`:
- **Database Host:** localhost
- **Database User:** root
- **Database Password:** (empty by default)
- **Database Name:** rent_route

If your XAMPP configuration is different, update the constants in `config.php`:

```php
define('DB_HOST', 'your_host');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'your_database_name');
```

### 3. Login & Registration

#### Login Process:
1. User selects role (Renter or Car Owner)
2. Enters email and password
3. System validates credentials from the appropriate table
4. On success, redirects to:
   - Car Owner → `/html/template/admin/index.html`
   - Renter → `/html/template/user-dashboard.html`

#### Registration Process:
1. User selects role (Renter or Car Owner)
2. Enters username, email, and password
3. System validates and creates account in appropriate table
4. User is redirected to login page

### 4. API Endpoints

#### Login
- **URL:** `/backend/login.php`
- **Method:** POST
- **Parameters:**
  - `role`: 'renter' or 'car_owner'
  - `email`: user email
  - `password`: user password
  - `remember`: (optional) checkbox for remember me

#### Register
- **URL:** `/backend/register.php`
- **Method:** POST
- **Parameters:**
  - `role`: 'renter' or 'car_owner'
  - `username`: unique username
  - `email`: unique email
  - `password`: min 6 characters

#### Logout
- **URL:** `/backend/logout.php`
- **Method:** GET

### 5. Database Tables Created

1. **renters** - For customer accounts
2. **car_owners** - For vehicle owner accounts
3. **cars** - Vehicle listings
4. **bookings** - Reservation records
5. **payments** - Payment transactions
6. **reviews** - User reviews and ratings

### 6. User Roles

#### Renter Role:
- Can browse available cars
- Make reservations/bookings
- View booking history
- Leave reviews
- Manage profile and preferences

#### Car Owner Role:
- Can list and manage vehicles
- View rental requests
- Manage bookings and payments
- View rental income reports
- Access admin dashboard

### 7. Session Management

Sessions are started automatically when:
- User logs in successfully
- Session variables stored:
  - `user_id` - User's database ID
  - `user_email` - User's email
  - `user_role` - Either 'renter' or 'car_owner'
  - `username` - User's username

### 8. Security Features Implemented

- Password hashing using PHP's `password_hash()` function
- Email validation
- SQL injection prevention using `mysqli::real_escape_string()`
- Remember me functionality with token-based authentication
- Session-based access control

### 9. Troubleshooting

**Database Connection Error:**
- Ensure XAMPP MySQL is running
- Check database credentials in `config.php`
- Verify database and tables were created successfully

**Login/Register Not Working:**
- Check browser console for JavaScript errors
- Verify backend PHP files are in correct location
- Check Apache error log

**Redirect Issues:**
- Ensure user dashboards exist at specified paths
- Check file paths in login.php redirect logic
- Verify Apache can access template files

### 10. Testing the System

**Test Registration:**
1. Go to `http://localhost/RENT&ROUTE/html/template/register.html`
2. Select role (try Renter)
3. Fill in username, email, password
4. Click Sign Up

**Test Login:**
1. Go to `http://localhost/RENT&ROUTE/html/template/login.html`
2. Select same role as registration
3. Enter email and password from registration
4. Click Sign In

Should redirect to appropriate dashboard based on role.

## File Structure

```
backend/
├── config.php          - Database configuration
├── login.php          - Login endpoint
├── register.php       - Registration endpoint
├── logout.php         - Logout endpoint
├── auth.php           - Authentication helper class
└── setup.sql          - Database schema
```
