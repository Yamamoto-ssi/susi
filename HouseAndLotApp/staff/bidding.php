<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bidding - Ardor Property Group</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --sidebar-bg: #0b1736; --sidebar-hover: #15244b; --sidebar-active: #1e305e; --accent-orange: #d97706; --accent-orange-light: #fef3c7; --main-bg: #f8fafc; --card-bg: #ffffff; --text-main: #0f172a; --text-muted: #64748b; --border-color: #e2e8f0; --btn-primary: #0b1736; }
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background-color: var(--main-bg); color: var(--text-main); display: flex; height: 100vh; overflow: hidden; }
        aside { width: 260px; background-color: var(--sidebar-bg); color: #94a3b8; display: flex; flex-direction: column; justify-content: space-between; border-right: 1px solid #1e293b; flex-shrink: 0; }
        .sidebar-top { padding: 20px; }
        .brand-container { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; }
        .brand-logo { background: var(--accent-orange); color: white; font-weight: bold; padding: 6px 10px; border-radius: 6px; font-size: 14px; }
        .brand-name { color: white; font-weight: 600; font-size: 15px; }
        .brand-sub { font-size: 10px; color: #64748b; letter-spacing: 0.05em; }
        .workspace-box { background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 8px; padding: 10px 12px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .workspace-title { color: white; font-size: 13px; font-weight: 500; }
        .workspace-desc { font-size: 11px; color: #64748b; }
        .nav-section-title { font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; margin-bottom: 10px; padding-left: 10px; font-weight: 600; }
        .nav-list { list-style: none; margin-bottom: 24px; }
        .nav-item { margin-bottom: 4px; }
        .nav-link { display: flex; align-items: center; justify-content: space-between; padding: 10px 12px; color: #94a3b8; text-decoration: none; border-radius: 6px; font-size: 13px; transition: all 0.2s; }
        .nav-link:hover { background-color: var(--sidebar-hover); color: white; }
        .nav-link.active { background-color: var(--sidebar-active); color: white; font-weight: 500; }
        .nav-link span { display: flex; align-items: center; gap: 10px; }
        .sidebar-bottom { padding: 20px; }
        .help-box { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 8px; padding: 12px; display: flex; align-items: center; gap: 10px; color: white; font-size: 12px; }
        .main-wrapper { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        header.top-header { background: var(--card-bg); border-bottom: 1px solid var(--border-color); padding: 12px 28px; display: flex; justify-content: space-between; align-items: center; }
        .breadcrumb { font-size: 13px; color: var(--text-muted); } .breadcrumb span { color: var(--text-main); font-weight: 500; }
        .header-right { display: flex; align-items: center; gap: 16px; }
        .search-box-top { position: relative; } .search-box-top input { padding: 6px 12px 6px 32px; border-radius: 6px; border: 1px solid var(--border-color); font-size: 13px; width: 220px; outline: none; background: #f8fafc; }
        .search-box-top i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 12px; }
        .icon-btn { background: none; border: 1px solid var(--border-color); border-radius: 6px; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; color: var(--text-muted); cursor: pointer; }
        .user-avatar { width: 32px; height: 32px; background: #cbd5e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 12px; color: #334155; }
        .content-body { padding: 28px; max-width: 1400px; margin: 0 auto; width: 100%; }
        .pre-heading { font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); font-weight: 600; margin-bottom: 4px; }
        .page-header-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; }
        .page-title { font-size: 24px; font-weight: 700; color: var(--text-main); margin-bottom: 4px; }
        .page-subtitle { font-size: 13px; color: var(--text-muted); }
        .btn-action-primary { background: var(--btn-primary); color: white; border: none; padding: 10px 16px; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 8px; }
        .metrics-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px; }
        .metric-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; }
        .metric-title { font-size: 12px; color: var(--text-muted); font-weight: 500; margin-bottom: 8px; }
        .metric-value { font-size: 28px; font-weight: 700; color: var(--text-main); margin-bottom: 6px; }
        .metric-footer { font-size: 11px; color: var(--text-muted); }
        .card-container { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; }
        .card-header-flex { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        .card-title { font-size: 15px; font-weight: 600; } .card-subtitle { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
        .table-search { position: relative; } .table-search input { padding: 6px 12px 6px 30px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 12px; width: 220px; outline: none; }
        .table-search i { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 11px; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th { font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted); padding: 10px 12px; border-bottom: 1px solid var(--border-color); font-weight: 600; }
        td { padding: 14px 12px; border-bottom: 1px solid var(--border-color); font-size: 13px; vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        .status-tag { display: inline-flex; align-items: center; gap: 6px; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 500; }
        .status-tag.ongoing { background: #fef3c7; color: #b45309; }
        .status-tag.approved { background: #dcfce7; color: #15803d; }
        .status-dot { width: 5px; height: 5px; border-radius: 50%; background: currentColor; }
        .action-dots { color: var(--text-muted); cursor: pointer; border: none; background: none; font-size: 14px; }
    </style>
</head>
<body>
    <aside>
        <div class="sidebar-top">
            <div class="brand-container"><div class="brand-logo">AP</div><div><div class="brand-name">Ardor</div><div class="brand-sub">PROPERTY GROUP</div></div></div>
            <div class="workspace-box"><div><div class="workspace-title">Ardor Properties</div><div class="workspace-desc">Enterprise workspace</div></div><i class="fa-solid fa-chevron-down" style="font-size: 11px; color: #94a3b8;"></i></div>
            <div class="nav-section-title">Workspace</div>
            <ul class="nav-list">
                <li class="nav-item"><a href="dashboard.php" class="nav-link"><span><i class="fa-solid fa-chart-pie"></i> Dashboard</span></a></li>
                <li class="nav-item"><a href="approvals.php" class="nav-link"><span><i class="fa-solid fa-circle-check"></i> Approvals</span></a></li>
                <li class="nav-item"><a href="properties.php" class="nav-link"><span><i class="fa-solid fa-building"></i> Properties</span></a></li>
                <li class="nav-item"><a href="bidding.php" class="nav-link active"><span><i class="fa-solid fa-gavel"></i> Bidding</span></a></li>
                <li class="nav-item"><a href="staff.php" class="nav-link"><span><i class="fa-solid fa-user-tie"></i> Staff</span></a></li>
                <li class="nav-item"><a href="buyers.php" class="nav-link"><span><i class="fa-solid fa-users"></i> Buyers</span></a></li>
                <li class="nav-item"><a href="reports.php" class="nav-link"><span><i class="fa-solid fa-chart-line"></i> Reports</span></a></li>
            </ul>
            <div class="nav-section-title">System</div>
            <ul class="nav-list">
                <li class="nav-item"><a href="notification.php" class="nav-link"><span><i class="fa-solid fa-bell"></i> Notifications</span></a></li>
                <li class="nav-item"><a href="roles_sec.php" class="nav-link"><span><i class="fa-solid fa-shield-halved"></i> Roles & security</span></a></li>
            </ul>
        </div>
        <div class="sidebar-bottom"><div class="help-box"><i class="fa-regular fa-file-lines"></i><span>Need assistance?</span></div></div>
    </aside>

    <div class="main-wrapper">
        <header class="top-header">
            <div class="breadcrumb">Ardor Properties &gt; <span>Bidding</span></div>
            <div class="header-right">
                <div class="search-box-top"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search anything"></div>
                <button class="icon-btn"><i class="fa-solid fa-keyboard"></i></button>
                <button class="icon-btn"><i class="fa-regular fa-bell"></i></button>
                <div class="user-avatar">EM</div>
            </div>
        </header>

        <div class="content-body">
            <div class="pre-heading">MANAGEMENT</div>
            <div class="page-header-row">
                <div>
                    <h1 class="page-title">Bidding</h1>
                    <p class="page-subtitle">Monitor active sessions, bid progress, and transaction records.</p>
                </div>
                <button class="btn-action-primary"><i class="fa-solid fa-plus"></i> Schedule bidding</button>
            </div>

            <div class="metrics-grid">
                <div class="metric-card"><div><div class="metric-title">Live sessions</div><div class="metric-value">28</div><div class="metric-footer">Updated in real time</div></div></div>
                <div class="metric-card"><div><div class="metric-title">Closing today</div><div class="metric-value">6</div><div class="metric-footer">Updated in real time</div></div></div>
                <div class="metric-card"><div><div class="metric-title">Total bid value</div><div class="metric-value">$18.4M</div><div class="metric-footer">Updated in real time</div></div></div>
            </div>

            <div class="card-container">
                <div class="card-header-flex">
                    <div><div class="card-title">Bidding overview</div><div class="card-subtitle">Recent records and activity</div></div>
                    <div class="table-search"><i class="fa-solid fa-magnifying-glass"></i><input type="text" placeholder="Search bidding"></div>
                </div>

                <table>
                    <thead><tr><th>Session</th><th>Current Bidder</th><th>Highest Bid</th><th>Ends</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        <tr><td><strong>Silver Oak Estate</strong></td><td>A. Richards</td><td>$3,240,000</td><td>2h 18m</td><td><span class="status-tag ongoing"><span class="status-dot"></span>Ongoing</span></td><td><button class="action-dots"><i class="fa-solid fa-ellipsis"></i></button></td></tr>
                        <tr><td><strong>Horizon Pool Villa</strong></td><td>S. Wilson</td><td>$2,320,000</td><td>5h 42m</td><td><span class="status-tag ongoing"><span class="status-dot"></span>Ongoing</span></td><td><button class="action-dots"><i class="fa-solid fa-ellipsis"></i></button></td></tr>
                        <tr><td><strong>Maple Court House</strong></td><td>M. Thomas</td><td>$884,000</td><td>Completed</td><td><span class="status-tag approved"><span class="status-dot"></span>Approved</span></td><td><button class="action-dots"><i class="fa-solid fa-ellipsis"></i></button></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>