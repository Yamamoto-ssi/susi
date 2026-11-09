<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'Administrator') {
    header("Location: ../login.php");
    exit;
}

$dashboard_error = null;
$stats = null;
$stats_query = "
    SELECT
        (SELECT COUNT(*) FROM users) AS total_users,
        (SELECT COUNT(*) FROM properties) AS total_properties,
        (SELECT COUNT(*) FROM properties WHERE is_featured = 1) AS featured_properties,
        (SELECT COUNT(*) FROM auctions WHERE status = 'active') AS active_auctions,
        (SELECT COUNT(*) FROM bookings WHERE status = 'pending') AS pending_bookings
";
$stats_result = mysqli_query($conn, $stats_query);

if ($stats_result === false) {
    error_log('Admin dashboard statistics query failed: ' . mysqli_error($conn));
    $dashboard_error = 'Dashboard statistics are temporarily unavailable.';
} else {
    $stats = mysqli_fetch_assoc($stats_result);
}

$username = htmlspecialchars((string) ($_SESSION['username'] ?? 'Administrator'), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Mendoza Real Estate</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f4f6f9; }
        .sidebar { background: #17212b; }
        .sidebar .nav-link { border-radius: .65rem; }
        .sidebar .nav-link:hover { background: rgba(255,255,255,.1); }
        .dashboard-card { border: 0; border-radius: 1rem; transition: transform .18s ease, box-shadow .18s ease; }
        .dashboard-card:hover { transform: translateY(-3px); box-shadow: 0 1rem 2rem rgba(23,33,43,.12) !important; }
        .stat-icon { width: 3rem; height: 3rem; display: grid; place-items: center; border-radius: .9rem; }
    </style>
</head>
<body class="bg-light">

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse text-white min-vh-100 p-3">
                <div class="position-sticky pt-3">
                    <h5 class="sidebar-heading px-3 mb-3 text-warning fw-bold">
                        <i class="fa-solid fa-house-chimney me-2"></i>Mendoza Admin
                    </h5>
                    <ul class="nav flex-column nav-pills">
                        <li class="nav-item mb-2">
                            <a href="admin_dashboard.php" class="nav-link active bg-warning text-dark fw-bold" aria-current="page">
                                <i class="fa-solid fa-chart-line me-2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="../properties.php" class="nav-link text-white">
                                <i class="fa-solid fa-building me-2"></i> Manage Properties
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="../add_property.php" class="nav-link text-white">
                                <i class="fa-solid fa-circle-plus me-2"></i> Add Property
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="../add_auction.php" class="nav-link text-white">
                                <i class="fa-solid fa-gavel me-2"></i> Create Auction
                            </a>
                        </li>
                        <li class="nav-item mb-2">
                            <a href="../staff/index.php" class="nav-link text-white">
                                <i class="fa-solid fa-clipboard-check me-2"></i> Review Approvals
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a href="../home.php" class="nav-link text-light border border-secondary">
                                <i class="fa-solid fa-globe me-2"></i> View Website
                            </a>
                        </li>
                        <li class="nav-item mt-2">
                            <a href="../logout.php" class="nav-link text-danger">
                                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <!-- Top Header Bar -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <button class="btn btn-outline-secondary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                            <i class="fa-solid fa-bars"></i>
                        </button>
                        <div>
                            <h1 class="h2 fw-bold mb-1">Dashboard Overview</h1>
                            <p class="text-muted mb-0">Your property platform at a glance.</p>
                        </div>
                    </div>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="text-muted me-3 align-self-center">Welcome, <strong><?php echo $username; ?></strong></span>
                        <a href="../logout.php" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-right-from-bracket me-1"></i> Logout</a>
                    </div>
                </div>

                <!-- Statistics Cards Row -->
                <?php if ($dashboard_error !== null): ?>
                    <div class="alert alert-warning shadow-sm" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i><?php echo htmlspecialchars($dashboard_error, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php elseif ($stats !== null): ?>
                <div class="row g-4 mb-4">
                    <div class="col-xl-4 col-md-6">
                        <div class="card dashboard-card shadow-sm h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs fw-bold text-muted text-uppercase mb-1">Total Users</div>
                                        <div class="h3 mb-0 fw-bold text-dark"><?php echo number_format((int) $stats['total_users']); ?></div>
                                    </div>
                                    <div class="col-auto stat-icon bg-warning-subtle text-warning fs-4"><i class="fa-solid fa-users"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card dashboard-card shadow-sm h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs fw-bold text-muted text-uppercase mb-1">Total Properties</div>
                                        <div class="h3 mb-0 fw-bold text-dark"><?php echo number_format((int) $stats['total_properties']); ?></div>
                                    </div>
                                    <div class="col-auto stat-icon bg-success-subtle text-success fs-4"><i class="fa-solid fa-building"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card dashboard-card shadow-sm h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs fw-bold text-muted text-uppercase mb-1">Featured Properties</div>
                                        <div class="h3 mb-0 fw-bold text-dark"><?php echo number_format((int) $stats['featured_properties']); ?></div>
                                    </div>
                                    <div class="col-auto stat-icon bg-primary-subtle text-primary fs-4"><i class="fa-solid fa-star"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card dashboard-card shadow-sm h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs fw-bold text-muted text-uppercase mb-1">Active Auctions</div>
                                        <div class="h3 mb-0 fw-bold text-dark"><?php echo number_format((int) $stats['active_auctions']); ?></div>
                                    </div>
                                    <div class="col-auto stat-icon bg-danger-subtle text-danger fs-4"><i class="fa-solid fa-gavel"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-md-6">
                        <div class="card dashboard-card shadow-sm h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <div class="text-xs fw-bold text-muted text-uppercase mb-1">Pending Site Visits</div>
                                        <div class="h3 mb-0 fw-bold text-dark"><?php echo number_format((int) $stats['pending_bookings']); ?></div>
                                    </div>
                                    <div class="col-auto stat-icon bg-info-subtle text-info fs-4"><i class="fa-solid fa-calendar-check"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Quick Actions -->
                <section>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="h4 fw-bold mb-0">Quick actions</h2>
                        <span class="text-muted small">Go directly to a working section</span>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6 col-xl-3">
                            <div class="card dashboard-card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="stat-icon bg-warning-subtle text-warning fs-4 mb-3"><i class="fa-solid fa-building"></i></div>
                                    <h3 class="h5 fw-bold">Properties</h3>
                                    <p class="text-muted">Browse and manage the property listings.</p>
                                    <a href="../properties.php" class="btn btn-dark btn-sm fw-bold">View Properties</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card dashboard-card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="stat-icon bg-success-subtle text-success fs-4 mb-3"><i class="fa-solid fa-circle-plus"></i></div>
                                    <h3 class="h5 fw-bold">New listing</h3>
                                    <p class="text-muted">Add a property to the platform.</p>
                                    <a href="../add_property.php" class="btn btn-dark btn-sm fw-bold">Add Property</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card dashboard-card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="stat-icon bg-danger-subtle text-danger fs-4 mb-3"><i class="fa-solid fa-gavel"></i></div>
                                    <h3 class="h5 fw-bold">Auctions</h3>
                                    <p class="text-muted">Set up an auction for a property.</p>
                                    <a href="../add_auction.php" class="btn btn-dark btn-sm fw-bold">Create Auction</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card dashboard-card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="stat-icon bg-info-subtle text-info fs-4 mb-3"><i class="fa-solid fa-clipboard-check"></i></div>
                                    <h3 class="h5 fw-bold">Approvals</h3>
                                    <p class="text-muted">Review pending listings and site visits.</p>
                                    <a href="../staff/index.php" class="btn btn-dark btn-sm fw-bold">Review Items</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </main>
        </div>
    </div>

    <!-- Bootstrap JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>