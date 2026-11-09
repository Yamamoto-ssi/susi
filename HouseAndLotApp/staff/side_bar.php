<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <aside>
    <div class="sidebar-top">
        <div class="brand-container">
            <div class="brand-logo">AP</div>
            <div>
                <div class="brand-name">Ardor</div>
                <div class="brand-sub">PROPERTY GROUP</div>
            </div>
        </div>

        <div class="workspace-box">
            <div>
                <div class="workspace-title">Ardor Properties</div>
                <div class="workspace-desc">Enterprise workspace</div>
            </div>
            <i class="fa-solid fa-chevron-down" style="font-size: 11px; color: #94a3b8;"></i>
        </div>

        <div class="nav-section-title">Workspace</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link <?php echo isActive('dashboard', $currentPage ?? ''); ?>">
                    <span><i class="fa-solid fa-chart-pie"></i> Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="approvals.php" class="nav-link <?php echo isActive('approvals', $currentPage ?? ''); ?>">
                    <span><i class="fa-solid fa-circle-check"></i> Approvals</span>
                    <span class="badge-count">12</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="properties.php" class="nav-link <?php echo isActive('properties', $currentPage ?? ''); ?>">
                    <span><i class="fa-solid fa-building"></i> Properties</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="bidding.php" class="nav-link <?php echo isActive('bidding', $currentPage ?? ''); ?>">
                    <span><i class="fa-solid fa-gavel"></i> Bidding</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="staff.php" class="nav-link <?php echo isActive('staff', $currentPage ?? ''); ?>">
                    <span><i class="fa-solid fa-user-tie"></i> Staff</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="buyers.php" class="nav-link <?php echo isActive('buyers', $currentPage ?? ''); ?>">
                    <span><i class="fa-solid fa-users"></i> Buyers</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="reports.php" class="nav-link <?php echo isActive('reports', $currentPage ?? ''); ?>">
                    <span><i class="fa-solid fa-chart-line"></i> Reports</span>
                </a>
            </li>
        </ul>

        <div class="nav-section-title">System</div>
        <ul class="nav-list">
            <li class="nav-item">
                <a href="notifications.php" class="nav-link <?php echo isActive('notifications', $currentPage ?? ''); ?>">
                    <span><i class="fa-solid fa-bell"></i> Notifications</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="roles.php" class="nav-link <?php echo isActive('roles', $currentPage ?? ''); ?>">
                    <span><i class="fa-solid fa-shield-halved"></i> Roles & security</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-bottom">
        <div class="help-box">
            <i class="fa-regular fa-file-lines"></i>
            <span>Need assistance?</span>
        </div>
    </div>
</aside>
</body>
</html>
