<?php
// Determine the current page from the query string, default to 'overview'
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'overview';

// Helper function to add active class to sidebar navigation links
function isActive($pageName, $current) {
    return $pageName === $current ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AuctionHaus - Seller Portal</title>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-bg: #0b1736;
            --sidebar-hover: #15244b;
            --sidebar-active: #1e305e;
            --accent-orange: #d97706;
            --accent-orange-light: #fef3c7;
            --main-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --btn-primary: #0b1736;
            --btn-primary-hover: #1d305e;
            --status-active-text: #16a34a;
            --status-pending-text: #d97706;
            --status-active-bg: #dcfce7;
            --status-pending-bg: #fef3c7;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', -apple-system, sans-serif; }
        body { background-color: var(--main-bg); color: var(--text-main); display: flex; height: 100vh; overflow: hidden; }

        /* Sidebar Styling */
        aside.side { width: 260px; background-color: var(--sidebar-bg); color: #94a3b8; display: flex; flex-direction: column; justify-content: space-between; border-right: 1px solid #1e293b; flex-shrink: 0; padding: 20px; overflow-y: auto; }
        
        .logo { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; color: white; }
        .logo .mk { background: var(--accent-orange); color: white; padding: 6px; border-radius: 6px; display: flex; align-items: center; justify-content: center; }
        .logo svg.i { width: 18px; height: 18px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .logo b { font-size: 15px; font-weight: 700; display: block; letter-spacing: 0.05em; color: white; }
        .logo b span { color: var(--accent-orange); }
        .logo small { font-size: 10px; color: #64748b; letter-spacing: 0.05em; display: block; }

        .cap { font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; margin: 16px 0 8px 4px; font-weight: 600; }
        
        .ws { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 10px 12px; display: flex; align-items: center; gap: 10px; margin-bottom: 16px; color: white; cursor: pointer; transition: background 0.2s; }
        .ws:hover { background: rgba(255, 255, 255, 0.08); }
        .ws .av { width: 32px; height: 32px; background: var(--accent-orange); color: white; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 12px; flex-shrink: 0; }
        .ws b { font-size: 13px; font-weight: 500; display: block; color: white; }
        .ws small { font-size: 11px; color: #94a3b8; display: block; }
        .ws svg.i.chev { width: 14px; height: 14px; margin-left: auto; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

        .nav { display: flex; flex-direction: column; gap: 4px; margin-bottom: 16px; }
        .nav a { display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: #94a3b8; text-decoration: none; border-radius: 6px; font-size: 13px; transition: all 0.2s; }
        .nav a:hover { background-color: var(--sidebar-hover); color: white; }
        .nav a.active { background-color: var(--sidebar-active); color: white; font-weight: 500; }
        .nav a em { display: inline-flex; align-items: center; justify-content: center; width: 18px; height: 18px; }
        .nav a svg.i { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
        .nav a span { background: var(--accent-orange); color: white; font-size: 10px; padding: 2px 6px; border-radius: 10px; font-weight: 600; margin-left: auto; }

        .help { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 8px; padding: 12px; color: white; font-size: 12px; margin-top: auto; margin-bottom: 16px; }
        .help svg.i { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; display: block; margin-bottom: 6px; color: var(--accent-orange); }
        .help b { display: block; margin-bottom: 2px; }
        .help small, .help span { color: #94a3b8; display: block; margin-bottom: 8px; font-size: 11px; }
        .btn-text { background: none; border: none; color: var(--accent-orange); font-size: 12px; font-weight: 600; cursor: pointer; padding: 0; text-decoration: underline; }

        .me { display: flex; align-items: center; gap: 10px; padding-top: 12px; border-top: 1px solid rgba(255, 255, 255, 0.08); cursor: pointer; }
        .me .av { width: 32px; height: 32px; background: #334155; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 11px; flex-shrink: 0; }
        .me b { font-size: 13px; color: white; display: block; font-weight: 500; }
        .me small { font-size: 11px; color: #94a3b8; display: block; }

        /* Main Content Layout Wrapper */
        .main-wrapper { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        header.top-header { background: var(--card-bg); border-bottom: 1px solid var(--border-color); padding: 12px 28px; display: flex; justify-content: space-between; align-items: center; }
        .breadcrumb { font-size: 13px; color: var(--text-muted); }
        .breadcrumb span { color: var(--text-main); font-weight: 500; }
        .header-right { display: flex; align-items: center; gap: 16px; }
        .search-box-top { position: relative; }
        .search-box-top input { padding: 6px 12px 6px 32px; border-radius: 6px; border: 1px solid var(--border-color); font-size: 13px; width: 220px; outline: none; background: #f8fafc; }
        .search-box-top i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 12px; }
        .icon-btn { background: none; border: 1px solid var(--border-color); border-radius: 6px; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; color: var(--text-muted); cursor: pointer; }
        .user-avatar { width: 32px; height: 32px; background: #cbd5e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 12px; color: #334155; }

        /* Content Body & Common Components */
        .content-body { padding: 28px; max-width: 1400px; margin: 0 auto; width: 100%; }
        .pre-heading { font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 600; margin-bottom: 4px; }
        .page-header-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
        .page-title { font-size: 24px; font-weight: 700; color: var(--text-main); margin-bottom: 4px; }
        .page-subtitle { font-size: 13px; color: var(--text-muted); }
        .btn-action-primary { background: var(--btn-primary); color: white; border: none; padding: 10px 16px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 8px; }
        .btn-action-primary:hover { background: var(--btn-primary-hover); }
        .btn-action-secondary { background: white; border: 1px solid var(--border-color); padding: 8px 14px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 6px; color: var(--text-main); }

        /* Metrics Grid */
        .metrics-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .metrics-grid-4 { grid-template-columns: repeat(4, 1fr); }
        .metric-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; }
        .metric-card-flex { display: flex; justify-content: space-between; align-items: flex-start; }
        .metric-title { font-size: 12px; color: var(--text-muted); font-weight: 500; margin-bottom: 8px; }
        .metric-value { font-size: 28px; font-weight: 700; color: var(--text-main); margin-bottom: 6px; }
        .metric-footer { font-size: 11px; color: var(--text-muted); }
        .metric-icon { width: 36px; height: 36px; border-radius: 6px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: var(--text-muted); }
        .metric-icon.orange { background: var(--accent-orange-light); color: var(--accent-orange); }
        .metric-icon.purple { background: #f3e8ff; color: #9333ea; }
        .metric-icon.green { background: #dcfce7; color: #16a34a; }

        /* Containers & Tables */
        .dashboard-grid-2col { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 24px; }
        .card-container { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; }
        .card-header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .card-title { font-size: 15px; font-weight: 600; }
        .card-subtitle { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
        .select-sm { padding: 4px 8px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 12px; outline: none; color: var(--text-muted); }

        .table-filters { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; }
        .filter-tabs { display: flex; gap: 8px; }
        .filter-tab { padding: 6px 12px; font-size: 12px; font-weight: 500; border-radius: 6px; cursor: pointer; background: none; border: none; color: var(--text-muted); }
        .filter-tab.active { background: #f1f5f9; color: var(--text-main); font-weight: 600; }
        .filter-tab .badge { background: var(--accent-orange-light); color: #b45309; padding: 1px 5px; border-radius: 10px; font-size: 10px; margin-left: 4px; }

        .table-search { position: relative; }
        .table-search input { padding: 6px 12px 6px 30px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 12px; width: 200px; outline: none; }
        .table-search i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 11px; }

        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); padding: 10px 12px; border-bottom: 1px solid var(--border-color); font-weight: 600; }
        td { padding: 14px 12px; border-bottom: 1px solid var(--border-color); font-size: 13px; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        .text-muted { color: var(--text-muted); }

        .property-cell { display: flex; align-items: center; gap: 12px; }
        .property-thumb { width: 48px; height: 36px; border-radius: 4px; object-fit: cover; background: #cbd5e1; }
        .property-info .prop-name { font-weight: 600; color: var(--text-main); font-size: 13px; }
        .property-info .prop-addr { font-size: 11px; color: var(--text-muted); }
        .price-main { font-weight: 600; color: var(--text-main); }
        .price-sub { font-size: 11px; color: var(--text-muted); }

        .status-tag, .status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 500; }
        .status-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
        .status-tag.ongoing, .status-tag.pending, .status-badge { background: var(--accent-orange-light); color: #b45309; }
        .status-tag.approved, .status-tag.active { background: var(--status-active-bg); color: var(--status-active-text); }
        .status-tag.admin-review { background: #e0f2fe; color: #0369a1; }
        .status-tag.revision { background: #f1f5f9; color: #475569; }
        .action-dots { color: var(--text-muted); cursor: pointer; border: none; background: none; font-size: 14px; }

        /* Activity List */
        .activity-list { list-style: none; }
        .activity-item { display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border-color); }
        .activity-item:last-child { border-bottom: none; }
        .avatar-sm { width: 32px; height: 32px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; color: #334155; flex-shrink: 0; }
        .activity-details { font-size: 12px; }
        .activity-text strong { color: var(--text-main); }
        .activity-time { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <aside class="side">
        <div>
            <div class="logo">
                <span class="mk"><i></i><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 21V7l8-4 8 4v14M8 10h2M14 10h2M8 14h2M14 14h2M9 21v-3h6v3"/></svg></span>
                <div><b>AUCTION<span>HAUS</span></b><small>SELLER PORTAL</small></div>
            </div>
            
            <div class="cap">WORKSPACE</div>
            <div class="ws" onclick="alert('Workspace Switcher Clicked')">
                <div class="av">MH</div>
                <div><b>Meridian Holdings</b><small>Verified seller</small></div>
                <svg class="i chev" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
            </div>

            <div class="cap">MAIN MENU</div>
            <nav class="nav">
                <a href="dashboard.php?page=overview" class="<?php echo ($currentPage === 'overview') ? 'active' : ''; ?>">
                    <em><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg></em>Overview
                </a>
                <a href="dashboard.php?page=props" class="<?php echo ($currentPage === 'props') ? 'active' : ''; ?>">
                    <em><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></em>My Properties<span>12</span>
                </a>
                <a href="dashboard.php?page=approvals" class="<?php echo ($currentPage === 'approvals') ? 'active' : ''; ?>">
                    <em><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg></em>Approvals<span>3</span>
                </a>
                <a href="dashboard.php?page=manage" class="<?php echo ($currentPage === 'manage') ? 'active' : ''; ?>">
                    <em><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="m14 13-8.381 8.38a1 1 0 0 1-3.001-3L11 9.999"/><path d="M15.973 4.027A13 13 0 0 0 5.902 2.373c-1.398.342-1.092 2.158.277 2.601a19.9 19.9 0 0 1 5.822 3.024"/><path d="M16.001 11.999a19.9 19.9 0 0 1 3.024 5.824c.444 1.369 2.26 1.676 2.603.278A13 13 0 0 0 20 8.069"/><path d="M18.352 3.352a1.205 1.205 0 0 0-1.704 0l-5.296 5.296a1.205 1.205 0 0 0 0 1.704l2.296 2.296a1.205 1.205 0 0 0 1.704 0l5.296-5.296a1.205 1.205 0 0 0 0-1.704z"/></svg></em>Manage Auctions
                </a>
                <a href="dashboard.php?page=live" class="<?php echo ($currentPage === 'live') ? 'active' : ''; ?>">
                    <em><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 12h-2.48a2 2 0 0 0-1.93 1.46l-2.35 8.36a.25.25 0 0 1-.48 0L9.24 2.18a.25.25 0 0 0-.48 0l-2.35 8.36A2 2 0 0 1 4.49 12H2"/></svg></em>Live Bidding<span>2</span>
                </a>
                <a href="dashboard.php?page=results" class="<?php echo ($currentPage === 'results') ? 'active' : ''; ?>">
                    <em><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/><path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/><path d="M18 2H6v7a6 6 0 0 0 12 0V2Z"/></svg></em>Auction Results
                </a>
                <a href="dashboard.php?page=notifs" class="<?php echo ($currentPage === 'notifs') ? 'active' : ''; ?>">
                    <em><svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M10.268 21a2 2 0 0 0 3.464 0"/><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"/></svg></em>Notifications<span>5</span>
                </a>
            </nav>
        </div>

        <div>
            <div class="help">
                <svg class="i" viewBox="0 0 24 24" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                <b>Need assistance?</b>
                <small>Our auction support team is ready to help.</small>
                <button type="button" class="btn-text" onclick="alert('Opening support chat...')">Contact support</button>
            </div>
            <div class="me" onclick="alert('Profile settings clicked')">
                <div class="av">AM</div>
                <div><b>Alex Morgan</b><small>Senior Auctioneer</small></div>
            </div>
        </div>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Header -->
        <header class="top-header">
            <div class="breadcrumb">
                AuctionHaus &gt; <span><?php echo ucfirst($currentPage); ?></span>
            </div>
            <div class="header-right">
                <div class="search-box-top">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" placeholder="Search anything">
                </div>
                <button class="icon-btn"><i class="fa-solid fa-keyboard"></i></button>
                <button class="icon-btn"><i class="fa-regular fa-bell"></i></button>
                <div class="user-avatar">AM</div>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <div class="content-body">
            <?php switch ($currentPage): 
                case 'props': ?>
                    <div class="page-header-row">
                        <div>
                            <h1 class="page-title">My Properties</h1>
                            <p class="page-subtitle">Manage the complete registered property portfolio.</p>
                        </div>
                        <button class="btn-action-primary"><i class="fa-solid fa-plus"></i> Add property</button>
                    </div>

                    <div class="metrics-grid">
                        <div class="metric-card"><div><div class="metric-title">All properties</div><div class="metric-value">12</div><div class="metric-footer">Updated in real time</div></div></div>
                        <div class="metric-card"><div><div class="metric-title">Publicly listed</div><div class="metric-value">8</div><div class="metric-footer">Updated in real time</div></div></div>
                        <div class="metric-card"><div><div class="metric-title">Archived</div><div class="metric-value">4</div><div class="metric-footer">Updated in real time</div></div></div>
                    </div>

                    <div class="card-container">
                        <div class="card-header-flex">
                            <div><div class="card-title">Properties overview</div><div class="card-subtitle">Recent records and activity</div></div>
                            <div class="table-search"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search properties"></div>
                        </div>

                        <table>
                            <thead><tr><th>Property</th><th>Location</th><th>Owner</th><th>Value</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                                <tr><td><strong>Cedar Grove Residence</strong></td><td class="text-muted">84 Cedar Lane, Austin, TX</td><td>Olivia Bennett</td><td>$1,280,000</td><td><span class="status-tag pending"><span class="status-dot"></span>Pending</span></td><td><button class="action-dots"><i class="fa-solid fa-ellipsis"></i></button></td></tr>
                                <tr><td><strong>Horizon Pool Villa</strong></td><td class="text-muted">12 Ocean Ridge, Malibu, CA</td><td>Daniel Carter</td><td>$2,450,000</td><td><span class="status-tag admin-review"><span class="status-dot"></span>Admin Review</span></td><td><button class="action-dots"><i class="fa-solid fa-ellipsis"></i></button></td></tr>
                            </tbody>
                        </table>
                    </div>
                <?php break; case 'approvals': ?>
                    <div class="page-header-row">
                        <div>
                            <h1 class="page-title">Approvals</h1>
                            <p class="page-subtitle">Review submissions and make informed, auditable approval decisions.</p>
                        </div>
                        <button class="btn-action-secondary"><i class="fa-solid fa-arrow-up-right-from-square"></i> Export decisions</button>
                    </div>

                    <div class="metrics-grid">
                        <div class="metric-card"><div class="metric-title">Awaiting staff review</div><div class="metric-value">3</div><div class="metric-footer">1 marked urgent</div></div>
                        <div class="metric-card"><div class="metric-title">Awaiting admin confirmation</div><div class="metric-value">2</div><div class="metric-footer">Median wait: 6 hours</div></div>
                        <div class="metric-card"><div class="metric-title">Approved this month</div><div class="metric-value">18</div><div class="metric-footer">92% approval rate</div></div>
                    </div>

                    <div class="card-container">
                        <div class="card-header-flex">
                            <div><div class="card-title">Auction approvals</div><div class="card-subtitle">Review owner submissions, documents, pricing, and legal requirements.</div></div>
                            <div class="table-search"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search approvals"></div>
                        </div>

                        <table>
                            <thead><tr><th>Property</th><th>Owner</th><th>Asking Price</th><th>Submitted</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="property-cell">
                                            <img src="https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?w=100&auto=format&fit=crop&q=60" alt="" class="property-thumb">
                                            <div class="property-info"><div class="prop-name">Cedar Grove Residence</div><div class="prop-addr">Unit 204B, 184 Cedar Lane, Austin, TX</div></div>
                                        </div>
                                    </td>
                                    <td>Olivia Bennett</td>
                                    <td><div class="price-main">$1,280,000</div><div class="price-sub">Mkt: $1,250,000</div></td>
                                    <td>May 24, 2026</td>
                                    <td><span class="status-badge"><span class="status-dot"></span>Pending</span></td>
                                    <td><button class="action-dots"><i class="fa-solid fa-ellipsis"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php break; case 'manage': ?>
                    <div class="pre-heading">MANAGEMENT</div>
                    <div class="page-header-row">
                        <div>
                            <h1 class="page-title">Manage Auctions</h1>
                            <p class="page-subtitle">Configure auction parameters, schedules, and asset details.</p>
                        </div>
                        <button class="btn-action-primary"><i class="fa-solid fa-plus"></i> Create auction</button>
                    </div>

                    <div class="metrics-grid">
                        <div class="metric-card"><div><div class="metric-title">Active listings</div><div class="metric-value">5</div><div class="metric-footer">Updated in real time</div></div></div>
                        <div class="metric-card"><div><div class="metric-title">Drafts</div><div class="metric-value">2</div><div class="metric-footer">Updated in real time</div></div></div>
                        <div class="metric-card"><div><div class="metric-title">Scheduled</div><div class="metric-value">4</div><div class="metric-footer">Updated in real time</div></div></div>
                    </div>

                    <div class="card-container">
                        <div class="card-header-flex">
                            <div><div class="card-title">Managed auctions</div><div class="card-subtitle">Overview of current items ready for auction</div></div>
                            <div class="table-search"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search auctions"></div>
                        </div>

                        <table>
                            <thead><tr><th>Auction Title</th><th>Reserve Price</th><th>Start Date</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                                <tr><td><strong>Silver Oak Estate</strong></td><td>$2,800,000</td><td>May 30, 2026</td><td><span class="status-tag active"><span class="status-dot"></span>Scheduled</span></td><td><button class="action-dots"><i class="fa-solid fa-ellipsis"></i></button></td></tr>
                            </tbody>
                        </table>
                    </div>
                <?php break; case 'live': ?>
                    <div class="pre-heading">MANAGEMENT</div>
                    <div class="page-header-row">
                        <div>
                            <h1 class="page-title">Live Bidding</h1>
                            <p class="page-subtitle">Monitor active sessions, bid progress, and real-time transaction records.</p>
                        </div>
                        <button class="btn-action-primary"><i class="fa-solid fa-plus"></i> Open session</button>
                    </div>

                    <div class="metrics-grid">
                        <div class="metric-card"><div><div class="metric-title">Live sessions</div><div class="metric-value">2</div><div class="metric-footer">Updated in real time</div></div></div>
                        <div class="metric-card"><div><div class="metric-title">Closing today</div><div class="metric-value">1</div><div class="metric-footer">Updated in real time</div></div></div>
                        <div class="metric-card"><div><div class="metric-title">Total bid value</div><div class="metric-value">$5.56M</div><div class="metric-footer">Updated in real time</div></div></div>
                    </div>

                    <div class="card-container">
                        <div class="card-header-flex">
                            <div><div class="card-title">Live bidding overview</div><div class="card-subtitle">Active rooms and current leading bids</div></div>
                            <div class="table-search"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search sessions"></div>
                        </div>

                        <table>
                            <thead><tr><th>Session</th><th>Current Bidder</th><th>Highest Bid</th><th>Ends</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                                <tr><td><strong>Silver Oak Estate</strong></td><td>A. Richards</td><td>$3,240,000</td><td>2h 18m</td><td><span class="status-tag ongoing"><span class="status-dot"></span>Ongoing</span></td><td><button class="action-dots"><i class="fa-solid fa-ellipsis"></i></button></td></tr>
                                <tr><td><strong>Horizon Pool Villa</strong></td><td>S. Wilson</td><td>$2,320,000</td><td>5h 42m</td><td><span class="status-tag ongoing"><span class="status-dot"></span>Ongoing</span></td><td><button class="action-dots"><i class="fa-solid fa-ellipsis"></i></button></td></tr>
                            </tbody>
                        </table>
                    </div>
                <?php break; case 'results': ?>
                    <div class="pre-heading">MANAGEMENT</div>
                    <div class="page-header-row">
                        <div>
                            <h1 class="page-title">Auction Results</h1>
                            <p class="page-subtitle">Review closed auction outcomes, final winning bids, and settlement details.</p>
                        </div>
                        <button class="btn-action-primary"><i class="fa-solid fa-download"></i> Export results</button>
                    </div>

                    <div class="metrics-grid">
                        <div class="metric-card"><div class="metric-title">Total closed value</div><div class="metric-value">$18.4M</div><div class="metric-footer">Updated in real time</div></div>
                        <div class="metric-card"><div class="metric-title">Completed auctions</div><div class="metric-value">24</div><div class="metric-footer">Updated in real time</div></div>
                        <div class="metric-card"><div class="metric-title">Success rate</div><div class="metric-value">95.8%</div><div class="metric-footer">Updated in real time</div></div>
                    </div>

                    <div class="card-container">
                        <div class="card-header-flex">
                            <div><div class="card-title">Closed results overview</div><div class="card-subtitle">Recent records and outcome histories</div></div>
                            <div class="table-search"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search results"></div>
                        </div>

                        <table>
                            <thead><tr><th>Property</th><th>Winner</th><th>Final Bid</th><th>Closed Date</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                                <tr><td><strong>Maple Court House</strong></td><td>M. Thomas</td><td>$884,000</td><td>May 20, 2026</td><td><span class="status-tag active"><span class="status-dot"></span>Settled</span></td><td><button class="action-dots"><i class="fa-solid fa-ellipsis"></i></button></td></tr>
                            </tbody>
                        </table>
                    </div>
                <?php break; case 'notifs': ?>
                    <div class="pre-heading">MANAGEMENT</div>
                    <div class="page-header-row">
                        <div>
                            <h1 class="page-title">Notifications</h1>
                            <p class="page-subtitle">Stay on top of submissions, approvals, and live bidding alerts.</p>
                        </div>
                        <button class="btn-action-primary"><i class="fa-solid fa-check"></i> Mark all read</button>
                    </div>

                    <div class="metrics-grid">
                        <div class="metric-card"><div class="metric-title">Unread</div><div class="metric-value">5</div><div class="metric-footer">Updated in real time</div></div>
                        <div class="metric-card"><div class="metric-title">Urgent</div><div class="metric-value">2</div><div class="metric-footer">Updated in real time</div></div>
                        <div class="metric-card"><div class="metric-title">This week</div><div class="metric-value">32</div><div class="metric-footer">Updated in real time</div></div>
                    </div>

                    <div class="card-container">
                        <div class="card-header-flex">
                            <div><div class="card-title">Notifications overview</div><div class="card-subtitle">Recent records and alert logs</div></div>
                            <div class="table-search"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search notifications"></div>
                        </div>

                        <table>
                            <thead><tr><th>Notification</th><th>Category</th><th>Source</th><th>Received</th><th>Status</th><th></th></tr></thead>
                            <tbody>
                                <tr><td><strong>New property submitted</strong></td><td class="text-muted">Approvals</td><td>Olivia Bennett</td><td>12 min ago</td><td><span class="status-tag pending"><span class="status-dot"></span>Unread</span></td><td><button class="action-dots"><i class="fa-solid fa-ellipsis"></i></button></td></tr>
                                <tr><td><strong>Bidding session complete</strong></td><td class="text-muted">Bidding</td><td>Silver Oak Estate</td><td>38 min ago</td><td><span class="status-tag pending"><span class="status-dot"></span>Unread</span></td><td><button class="action-dots"><i class="fa-solid fa-ellipsis"></i></button></td></tr>
                            </tbody>
                        </table>
                    </div>
                <?php break; default: // Overview / Dashboard ?>
                    <div class="pre-heading">MONDAY, JUNE 8</div>
                    <div class="page-header-row">
                        <div>
                            <h1 class="page-title">Good morning, Alex</h1>
                            <p class="page-subtitle">Here's what needs your attention across your seller portfolio today.</p>
                        </div>
                        <button class="btn-action-primary">
                            <i class="fa-solid fa-plus"></i> Create new listing
                        </button>
                    </div>

                    <!-- 4 Column Metrics Grid -->
                    <div class="metrics-grid metrics-grid-4">
                        <div class="metric-card metric-card-flex">
                            <div>
                                <div class="metric-title">Registered properties</div>
                                <div class="metric-value">12</div>
                                <div class="metric-footer" style="color: var(--status-active-text);">+2 this month</div>
                            </div>
                            <div class="metric-icon"><i class="fa-solid fa-house"></i></div>
                        </div>
                        <div class="metric-card metric-card-flex">
                            <div>
                                <div class="metric-title">Pending approvals</div>
                                <div class="metric-value">3</div>
                                <div class="metric-footer" style="color: var(--status-pending-text);">1 urgent review</div>
                            </div>
                            <div class="metric-icon orange"><i class="fa-regular fa-clock"></i></div>
                        </div>
                        <div class="metric-card metric-card-flex">
                            <div>
                                <div class="metric-title">Live bidding</div>
                                <div class="metric-value">2</div>
                                <div class="metric-footer" style="color: var(--status-active-text);">Active now</div>
                            </div>
                            <div class="metric-icon purple"><i class="fa-solid fa-gavel"></i></div>
                        </div>
                        <div class="metric-card metric-card-flex">
                            <div>
                                <div class="metric-title">Completed sales</div>
                                <div class="metric-value">$18.4M</div>
                                <div class="metric-footer" style="color: var(--status-active-text);">+12.5%</div>
                            </div>
                            <div class="metric-icon green"><i class="fa-solid fa-chart-column"></i></div>
                        </div>
                    </div>

                    <!-- Performance Chart and Activity -->
                    <div class="dashboard-grid-2col">
                        <div class="card-container">
                            <div class="card-header-flex">
                                <div>
                                    <div class="card-title">Portfolio performance</div>
                                    <div class="card-subtitle">Auction and bidding activity over the last 6 months</div>
                                </div>
                                <select class="select-sm">
                                    <option>Last 6 months</option>
                                </select>
                            </div>
                            <div style="height: 220px; display: flex; align-items: center; justify-content: center; background: #fafafa; border: 1px dashed var(--border-color); border-radius: 6px; color: #94a3b8; font-size: 13px;">
                                [Chart Visualization Container]
                            </div>
                        </div>

                        <div class="card-container">
                            <div class="card-header-flex">
                                <div class="card-title">Recent activity</div>
                                <i class="fa-solid fa-ellipsis" style="color: var(--text-muted); cursor: pointer;"></i>
                            </div>
                            <ul class="activity-list">
                                <li class="activity-item">
                                    <div class="avatar-sm">AM</div>
                                    <div class="activity-details">
                                        <div class="activity-text"><strong>Alex Morgan</strong> approved Horizon Pool Villa auction</div>
                                        <div class="activity-time">10 mins ago</div>
                                    </div>
                                </li>
                                <li class="activity-item">
                                    <div class="avatar-sm">JL</div>
                                    <div class="activity-details">
                                        <div class="activity-text"><strong>James Lee</strong> requested asset documents</div>
                                        <div class="activity-time">38 mins ago</div>
                                    </div>
                                </li>
                                <li class="activity-item">
                                    <div class="avatar-sm">NS</div>
                                    <div class="activity-details">
                                        <div class="activity-text"><strong>Nora Scott</strong> closed bidding for Oakridge Estate</div>
                                        <div class="activity-time">1 hr ago</div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
            <?php endswitch; ?>
        </div>
    </div>

</body>
</html>