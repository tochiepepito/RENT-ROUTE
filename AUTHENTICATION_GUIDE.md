# RENT&ROUTE Authentication & Role-Based Redirect Guide

## Updated Authentication System (April 27, 2026)

### Overview
The RENT&ROUTE platform now features a complete role-based authentication system that properly authenticates users based on their assigned role (Renter or Car Owner) and redirects them to the appropriate dashboard.

---

## 🔐 How It Works

### Registration Process
1. User visits `register.html`
2. User selects their role:
   - **Renter**: Users who want to rent vehicles
   - **Car Owner**: Users who want to list vehicles for rent
3. User fills in username, email, and password
4. Registration form submits via AJAX to `backend/register.php`
5. Backend validates and creates account with selected role
6. User receives success message and is redirected to login page

### Login Process
1. User visits `login.html`
2. User enters email and password
3. Login form submits via AJAX to `backend/login.php`
4. Backend validates credentials and authenticates user
5. Backend returns JSON response with:
   - User ID, email, username, role
   - Role-based redirect URL
6. JavaScript processes response and redirects based on role:
   - **Both Renter & Car Owner** → `user-dashboard-dynamic.php`
   - This dashboard detects user role and displays role-specific content

---

## 📊 Role-Based Dashboards

### Renter Dashboard Features
- **My Bookings**: View and manage active bookings
- **Wishlist**: Save favorite vehicles
- **Wallet**: Manage payment methods and balance
- **Browse Listings**: Search and filter available vehicles
- Links to user bookings, wishlist, wallet, and payment management

### Car Owner Dashboard Features
- **My Listings**: Manage vehicles available for rent
- **Rental Requests**: View and manage booking requests from renters
- **Earnings**: Track rental income and payments
- **Add New Car**: List a new vehicle for rent
- Links to listing management, rental requests, and earnings tracking

---

## 🔄 Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                      START                                  │
└──────────────────────┬──────────────────────────────────────┘
                       │
        ┌──────────────┴──────────────┐
        │                             │
    ┌───▼──────┐            ┌────────▼────┐
    │ REGISTER │            │   LOGIN     │
    └───┬──────┘            └────────┬────┘
        │                           │
   Select Role              Enter Credentials
   (Renter/Owner)                  │
        │                    ┌──────▼──────────┐
        │                    │ Authenticate    │
        │                    │ (backend check) │
        │                    └────────┬────────┘
        │                            │
        └──────────────┬─────────────┘
                       │
                ┌──────▼──────────────┐
                │ Set User Session    │
                │ Store User Role     │
                └──────┬──────────────┘
                       │
            ┌──────────▼──────────────┐
            │ Return JSON Response    │
            │ + Redirect URL          │
            └──────┬──────────────────┘
                   │
        ┌──────────▼──────────────┐
        │ JavaScript Processes    │
        │ Redirect URL            │
        └──────┬──────────────────┘
               │
    ┌──────────▼──────────────────┐
    │ Redirect to                 │
    │ user-dashboard-dynamic.php  │
    └──────┬───────────────────────┘
           │
    ┌──────▼────────────────┐
    │ Dashboard Detects     │
    │ User Role             │
    ├──────────────────────┤
    │ ┌──────────────────┐ │
    │ │ RENTER DASHBOARD │ │
    │ │ - My Bookings    │ │
    │ │ - Wishlist       │ │
    │ │ - Wallet         │ │
    │ │ - Browse Listings│ │
    │ └──────────────────┘ │
    │ OR                   │
    │ ┌──────────────────┐ │
    │ │ OWNER DASHBOARD  │ │
    │ │ - My Listings    │ │
    │ │ - Rental Requests│ │
    │ │ - Earnings       │ │
    │ │ - Add New Car    │ │
    │ └──────────────────┘ │
    └──────────────────────┘
```

---

## 📁 File Changes Summary

### Backend Files Modified
1. **`backend/config.php`**
   - Added currency configuration (PHP, Philippine Peso)
   - Added location configuration (Philippines, supported cities)
   - Added role definitions (ROLE_RENTER, ROLE_CAR_OWNER)

2. **`backend/login.php`**
   - Updated to return JSON response with user role
   - Returns role-aware redirect URL
   - Properly validates credentials and sets session

3. **`backend/register.php`**
   - Updated to return JSON response instead of redirect
   - Captures user-selected role
   - Creates account with specified role

### Frontend Files Modified
1. **`html/template/login.html`**
   - Added AJAX form submission handler
   - Intercepts login response and processes role
   - Displays error/success notifications
   - Redirects based on returned URL

2. **`html/template/register.html`**
   - Added role selection dropdown
   - Added AJAX form submission handler
   - Displays validation and success messages
   - Redirects to login after successful registration

3. **`html/template/user-dashboard-dynamic.php`**
   - Already has role detection and display logic
   - Shows renter-specific content for renters
   - Shows owner-specific content for car owners

---

## ✅ Testing Checklist

### Registration Test
- [ ] Navigate to `html/template/register.html`
- [ ] Select "Renter" role
- [ ] Fill in test data (username, email, password)
- [ ] Click "Sign Up"
- [ ] Verify success message appears
- [ ] Verify redirected to login page

### Login Test (Renter)
- [ ] Navigate to `html/template/login.html`
- [ ] Enter renter account credentials
- [ ] Click "Sign In"
- [ ] Verify redirected to dashboard
- [ ] Verify renter dashboard content is displayed
- [ ] Verify role shows as "Renter"

### Login Test (Car Owner)
- [ ] Navigate to `html/template/login.html`
- [ ] Enter car owner account credentials
- [ ] Click "Sign In"
- [ ] Verify redirected to dashboard
- [ ] Verify car owner dashboard content is displayed
- [ ] Verify role shows as "Car Owner"

### Session Test
- [ ] Log in as user
- [ ] Navigate to `user-dashboard-dynamic.php` directly
- [ ] Verify you're still authenticated and see correct role content
- [ ] Verify logout functionality works

---

## 🛡️ Security Features

1. **Password Verification**: Backend verifies password using hashing
2. **Email Validation**: Checks email format during registration
3. **Session Management**: Sets secure session variables after login
4. **Role-Based Access**: Dashboard checks user role and displays appropriate content
5. **CSRF Protection**: Form submission is POST-based (can add CSRF tokens if needed)

---

## 🔗 Important URLs

- **Register**: `/html/template/register.html`
- **Login**: `/html/template/login.html`
- **Dashboard**: `/html/template/user-dashboard-dynamic.php`
- **Add Listing (Owner)**: `/html/template/add-listing.html`
- **Browse Listings (Renter)**: `/html/template/listing-grid.html`

---

## 💡 Notes

- User sessions are stored using PHP `$_SESSION` variables
- Role is included in session for access control
- Dashboard uses role check to render appropriate content
- All authentication responses are JSON for AJAX handling
- Error messages are user-friendly and descriptive

---

## 🚀 Next Steps

1. Test registration and login thoroughly
2. Verify role-based dashboard content displays correctly
3. Test logout functionality
4. Implement password reset if needed
5. Add email verification for registration
6. Implement remember-me functionality (already on form)
7. Add reCAPTCHA to prevent automated registration
8. Set up email notifications for login/registration

---

**Last Updated**: April 27, 2026
**Version**: 1.0
**Status**: ✅ Ready for Testing
