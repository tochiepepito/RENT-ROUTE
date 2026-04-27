<?php
/**
 * User Dashboard - Role-Based View
 * This page checks user role and displays appropriate content
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/database.php';

// Require user to be logged in
Auth::requireLogin();

// Get current user
$current_user = Auth::getCurrentUser();
$user_id = Auth::getUserId();
$user_role = Auth::getUserRole();

// Get user full data
$user_account = Database::findById($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Dashboard | <?php echo ucfirst($user_role); ?> - RENT&ROUTE</title>
    
    <!-- CSS -->
    <link rel="stylesheet" href="../html/assets/css/bootstrap.min.css"">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="main-wrapper">
        <!-- Header -->
        <header class="main-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <h1>Dashboard</h1>
                        <p>Welcome, <?php echo htmlspecialchars($user_account['username']); ?></p>
                    </div>
                    <div class="col-lg-6 text-right">
                        <div class="user-info">
                            <span>Role: <strong><?php echo ucfirst(str_replace('_', ' ', $user_role)); ?></strong></span>
                            <a href="/RENT&ROUTE/backend/logout.php" class="btn btn-danger">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="main-content">
            <div class="container-fluid">
                
                <?php if ($user_role === 'renter'): ?>
                <!-- RENTER DASHBOARD -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">My Bookings</h5>
                                <p class="card-text text-muted">You have <strong>0</strong> active bookings</p>
                                <a href="user-bookings.html" class="btn btn-primary">View Bookings</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Wishlist</h5>
                                <p class="card-text text-muted">You have <strong>0</strong> saved cars</p>
                                <a href="user-wishlist.html" class="btn btn-primary">View Wishlist</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Wallet</h5>
                                <p class="card-text text-muted">Balance: <strong>$0.00</strong></p>
                                <a href="user-wallet.html" class="btn btn-primary">Manage Wallet</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Browse Listings</h5>
                            </div>
                            <div class="card-body">
                                <p>Check out available cars in your area.</p>
                                <a href="listing-grid.html" class="btn btn-primary">Browse Cars</a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php elseif ($user_role === 'car_owner'): ?>
                <!-- CAR OWNER DASHBOARD -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">My Listings</h5>
                                <p class="card-text text-muted">You have <strong>0</strong> cars listed</p>
                                <a href="user-listings.html" class="btn btn-primary">View Listings</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Rental Requests</h5>
                                <p class="card-text text-muted">You have <strong>0</strong> pending requests</p>
                                <a href="user-requests.html" class="btn btn-primary">View Requests</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Earnings</h5>
                                <p class="card-text text-muted">Total: <strong>$0.00</strong></p>
                                <a href="user-earnings.html" class="btn btn-primary">View Earnings</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Add New Listing</h5>
                            </div>
                            <div class="card-body">
                                <p>List your car on RENT&ROUTE and start earning.</p>
                                <a href="add-listing.html" class="btn btn-success">Add New Car</a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php endif; ?>

                <!-- User Profile Section -->
                <div class="row mt-4">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Profile Information</h5>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <tr>
                                        <td><strong>Username:</strong></td>
                                        <td><?php echo htmlspecialchars($user_account['username']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td><?php echo htmlspecialchars($user_account['email']); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Role:</strong></td>
                                        <td><?php echo ucfirst(str_replace('_', ' ', $user_account['role'])); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Member Since:</strong></td>
                                        <td><?php echo date('F j, Y', strtotime($user_account['created_at'])); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status:</strong></td>
                                        <td>
                                            <?php if ($user_account['is_active']): ?>
                                                <span class="badge badge-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                                <a href="user-settings.html" class="btn btn-secondary">Edit Profile</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <p>&copy; 2024 RENT&ROUTE. All Rights Reserved.</p>
        </footer>
    </div>

    <!-- Scripts -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
