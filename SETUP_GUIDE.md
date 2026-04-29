# RENT&ROUTE - Unified Login & Registration System Setup

## Overview

The RENT&ROUTE application now has a complete, unified login and registration system that works for both **Renters** and **Car Owners** using a single set of pages.

### Key Features:
✅ One login page for both user types
✅ One registration page for both user types
✅ Separate user tables by role (renters vs car_owners)
✅ Automatic redirection based on user role
✅ Session-based authentication
✅ Remember me functionality
✅ Secure password hashing

---

## Quick Setup (5 Steps)

### Step 1: Create Database

1. Open **phpMyAdmin** (http://localhost/phpmyadmin)
2. Click **Import** tab
3. Click **Choose File** and select: `backend/setup.sql`
4. Click **Go**
5. You should see: "✓ Database created successfully"

### Step 2: Verify Database Connection

1. Navigate to: `http://localhost/RENT&ROUTE/backend/config.php` (browser will show connection status)
2. If you see PHP code, the file is accessible ✓

### Step 3: Test Registration

1. Go to: `http://localhost/RENT&ROUTE/html/template/register.html`
2. Create an account:
   - **Role:** Select "Renter" or "Car Owner"
   - **Username:** myusername
   - **Email:** myemail@example.com
   - **Password:** password123 (min 6 chars)
3. Click **Sign Up**
4. Should see success message and redirect to login

### Step 4: Test Login

1. Go to: `http://localhost/RENT&ROUTE/html/template/login.html`
2. Login with account created in Step 3:
   - **Role:** Select same role as registration
   - **Email:** myemail@example.com
   - **Password:** password123
3. Click **Sign In**
4. Should redirect to appropriate dashboard:
   - **Renter** → `user-dashboard.html`
   - **Car Owner** → `admin/index.html`

### Step 5: Verify Session

After login, check that you can:
- See user menu/profile
- Access protected pages
- Logout successfully

---

## System Architecture

### Authentication Flow

```
User Visits login.html
         ↓
Selects Role (Renter/Car Owner)
         ↓
Enters Email & Password
         ↓
Submits Form to login.php
         ↓
login.php validates credentials:
- Checks appropriate table (renters/car_owners)
- Verifies password hash
- Creates session
         ↓
Based on Role:
- Renter → Redirects to user-dashboard.html
- Car Owner → Redirects to admin/index.html
```

### Database Structure

**Two separate user tables:**

1. **renters table**
   - Contains customer accounts
   - Fields: id, username, email, password, phone, profile_image, etc.

2. **car_owners table**
   - Contains vehicle owner accounts
   - Fields: id, username, email, password, company_name, tax_id, etc.

3. **Supporting tables:**
   - cars, bookings, payments, reviews

---

## Files Created

### Backend Files (in `/backend/`)

| File | Purpose |
|------|---------|
| `config.php` | Database connection configuration |
| `login.php` | Login authentication endpoint |
| `register.php` | Registration endpoint |
| `logout.php` | Logout handler |
| `auth.php` | Authentication helper class |
| `setup.sql` | Database schema (tables) |
| `README.md` | Backend documentation |

### Frontend Updates

| File | Changes |
|------|---------|
| `register.html` | Already had role selection ✓ |
| `login.html` | **UPDATED** - Added role selection dropdown |

---

## How to Use

### For Users - Registering

1. Visit: `/html/template/register.html`
2. Select role:
   - **Renter** - If you want to rent cars
   - **Car Owner** - If you want to list cars for rent
3. Fill in your details
4. Click Sign Up
5. Login page opens automatically
6. Login with your credentials

### For Users - Logging In

1. Visit: `/html/template/login.html`
2. Select same role as during registration
3. Enter your email and password
4. Click Sign In
5. Automatically redirected to your dashboard

### For Users - Logging Out

- Click logout from user menu (to be implemented in dashboard pages)
- Redirects to home page
- Session is destroyed

---

## Configuration

### Update Database Credentials

If your database setup is different, edit `/backend/config.php`:

```php
define('DB_HOST', 'localhost');      // Your host
define('DB_USER', 'root');           // Your username
define('DB_PASS', '');               // Your password
define('DB_NAME', 'rent_route');     // Your database name
```

---

## Troubleshooting

### Problem: "Database connection failed"
**Solution:**
- Ensure MySQL is running in XAMPP
- Check credentials in config.php
- Verify database exists (check phpMyAdmin)

### Problem: Login page shows "Method not allowed"
**Solution:**
- Ensure form method is POST in login.html
- Check backend/login.php file exists
- Verify Apache can access backend folder

### Problem: After login, page doesn't redirect
**Solution:**
- Check browser console for JavaScript errors
- Ensure target pages (user-dashboard.html, admin/index.html) exist
- Clear browser cache (Ctrl+Shift+Delete)

### Problem: "Email already registered"
**Solution:**
- Use a different email
- Or login if account already exists
- Check phpMyAdmin to view existing accounts

### Problem: "Password must be at least 6 characters"
**Solution:**
- Use password with 6+ characters
- Allowed characters: letters, numbers, symbols

---

## Security Features

1. **Password Hashing** - Uses PHP's bcrypt algorithm
2. **SQL Injection Prevention** - Uses mysqli prepared statements
3. **Email Validation** - Checks valid email format
4. **Session Management** - Server-side session storage
5. **Remember Me** - Token-based persistent login
6. **HTTPS Ready** - Can be enabled in Apache

---

## Next Steps

After setup, you can:

1. **Customize Pages** - Edit user-dashboard.html and admin pages
2. **Add More Fields** - Extend renters/car_owners tables in setup.sql
3. **Implement Social Login** - Add Google/Facebook OAuth
4. **Add Email Verification** - Send verification emails on signup
5. **Add Password Reset** - Implement forgot password functionality

---

## Support

### Files for Reference:
- `backend/README.md` - Detailed backend documentation
- `backend/setup.sql` - Database schema
- Login flow: `html/template/login.html`
- Register flow: `html/template/register.html`

### Testing Accounts (After Setup):

You can manually insert test accounts into phpMyAdmin:

**Test Renter Account:**
- Email: renter@test.com
- Password: password123 (hashed in database)
- Role: Renter

**Test Car Owner Account:**
- Email: owner@test.com
- Password: password123 (hashed in database)
- Role: Car Owner

---

## System Ready! ✓

Your unified login and registration system is now:
- ✓ Configured
- ✓ Database ready
- ✓ Secure and functional
- ✓ Ready for testing

**Start testing at:** `http://localhost/RENT&ROUTE/html/template/register.html`
