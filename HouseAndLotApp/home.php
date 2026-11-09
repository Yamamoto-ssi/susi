<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ardor Property Group - Dashboard</title>
  <!-- Feather Icons CDN -->
  <script src="https://unpkg.com/feather-icons"></script>
  <!-- Link to the separate CSS file -->
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <div class="app-container">
    <!-- Sidebar Navigation -->
    <aside class="sidebar">
      <div class="brand">
        <div class="logo">
          <i data-feather="home"></i>
        </div>
        <div class="brand-text">
          <h2>Ardor</h2>
          <span>PROPERTY GROUP</span>
        </div>
      </div>

      <div class="workspace-selector">
        <div class="avatar-sq">AP</div>
        <div class="workspace-info">
          <span class="title">Ardor Properties</span>
          <span class="subtitle">Enterprise workspace</span>
        </div>
        <i data-feather="chevron-down" class="dropdown-icon"></i>
      </div>

      <div class="nav-section">
        <div class="section-title">WORKSPACE</div>
        <ul class="nav-list">
          <li class="nav-item active">
            <a href="#"><i data-feather="grid"></i> Dashboard</a>
          </li>
          <li class="nav-item">
            <a href="#">
              <i data-feather="check-square"></i> Approvals
              <span class="badge">12</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#"><i data-feather="home"></i> Properties</a>
          </li>
          <li class="nav-item">
            <a href="#"><i data-feather="percent"></i> Bidding</a>
          </li>
          <li class="nav-item">
            <a href="#"><i data-feather="users"></i> Staff</a>
          </li>
          <li class="nav-item">
            <a href="#"><i data-feather="user-check"></i> Buyers</a>
          </li>
          <li class="nav-item">
            <a href="#"><i data-feather="bar-chart-2"></i> Reports</a>
          </li>
        </ul>
      </div>

      <div class="nav-section">
        <div class="section-title">SYSTEM</div>
        <ul class="nav-list">
          <li class="nav-item">
            <a href="#">
              <i data-feather="bell"></i> Notifications
              <span class="dot"></span>
            </a>
          </li>
          <li class="nav-item">
            <a href="#"><i data-feather="shield"></i> Roles & security</a>
          </li>
        </ul>
      </div>

      <div class="sidebar-card">
        <div class="card-icon"><i data-feather="file-text"></i></div>
        <h4>Need assistance?</h4>
        <p>Browse guides or contact support.</p>
        <a href="#" class="help-link">Open help center &rarr;</a>
      </div>
    </aside>

    <!-- Main Content Area -->
    <main class="main-content">
      <!-- Top Header Bar -->
      <header class="top-header">
        <div class="breadcrumb">
          <span>Ardor Properties</span>
          <i data-feather="chevron-right"></i>
          <span class="current">Dashboard</span>
        </div>
        <div class="header-actions">
          <div class="search-bar">
            <i data-feather="search"></i>
            <input type="text" placeholder="Search anything">
            <span class="shortcut">⌘ K</span>
          </div>
          <div class="notification-btn">
            <i data-feather="bell"></i>
            <span class="badge-count">8</span>
          </div>
          <div class="user-avatar">EM</div>
        </div>
      </header>

      <!-- Welcome Banner -->
      <div class="dashboard-banner">
        <div class="banner-text">
          <div class="date">MONDAY, MAY 26</div>
          <h1>Good morning, Elena</h1>
          <p>Here's what needs your attention across the portfolio today.</p>
        </div>
        <button class="btn-primary">
          <i data-feather="plus"></i> Review new submission
        </button>
      </div>

      <!-- Stat Cards Grid -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-content">
            <span class="stat-title">Registered properties</span>
            <div class="stat-value">
              1,248 <span class="trend positive">+8.2%</span>
            </div>
          </div>
          <div class="stat-icon blue-bg">
            <i data-feather="home"></i>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-content">
            <span class="stat-title">Pending approvals</span>
            <div class="stat-value">
              12 <span class="badge-text warning">4 urgent</span>
            </div>
          </div>
          <div class="stat-icon yellow-bg">
            <i data-feather="clock"></i>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-content">
            <span class="stat-title">Active bidding</span>
            <div class="stat-value">
              28 <span class="trend positive">+3 today</span>
            </div>
          </div>
          <div class="stat-icon purple-bg">
            <i data-feather="award"></i>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-content">
            <span class="stat-title">Completed sales</span>
            <div class="stat-value">
              $8.42M <span class="trend positive">+12.6%</span>
            </div>
          </div>
          <div class="stat-icon green-bg">
            <i data-feather="trending-up"></i>
          </div>
        </div>
      </div>

      <!-- Lower Content Grid -->
      <div class="content-grid">
        <!-- Chart Section -->
        <div class="card performance-card">
          <div class="card-header">
            <div>
              <h3>Portfolio performance</h3>
              <p>Property activity over the last 6 months</p>
            </div>
            <select class="time-select">
              <option>Last 6 months</option>
              <option>Last year</option>
            </select>
          </div>
          <div class="chart-legend">
            <span class="legend-item"><span class="legend-color dark-blue"></span> Approved</span>
            <span class="legend-item"><span class="legend-color gold"></span> Sold</span>
          </div>
          <div class="chart-container">
            <svg viewBox="0 0 700 200" class="chart-svg">
              <line x1="0" y1="40" x2="700" y2="40" stroke="#f0f0f0" stroke-dasharray="3 3"/>
              <line x1="0" y1="90" x2="700" y2="90" stroke="#f0f0f0" stroke-dasharray="3 3"/>
              <line x1="0" y1="140" x2="700" y2="140" stroke="#f0f0f0" stroke-dasharray="3 3"/>
              <line x1="0" y1="190" x2="700" y2="190" stroke="#f0f0f0" />
              
              <!-- Approved line (Dark blue) -->
              <path d="M0,170 C100,150 150,130 250,110 C350,120 400,90 500,80 C600,90 650,50 700,45" fill="none" stroke="#0F2844" stroke-width="3"/>
              <!-- Sold line (Gold) -->
              <path d="M0,185 C100,165 150,155 250,140 C350,145 400,125 500,110 C600,115 650,95 700,90" fill="none" stroke="#D1A153" stroke-width="3"/>
            </svg>
            <div class="chart-months">
              <span>Dec</span>
              <span>Jan</span>
              <span>Feb</span>
              <span>Mar</span>
              <span>Apr</span>
              <span>May</span>
            </div>
          </div>
        </div>

        <!-- Activity Section -->
        <div class="card activity-card">
          <div class="card-header">
            <h3>Recent activity</h3>
            <i data-feather="more-horizontal"></i>
          </div>
          <p class="subtitle">Latest team updates</p>
          <div class="activity-list">
            <div class="activity-item">
              <div class="avatar avatar-blue">AM</div>
              <div class="activity-details">
                <p><strong>Ava Mitchell</strong> approved Horizon Pool Villa</p>
                <span class="time">12 min ago</span>
              </div>
            </div>
            <div class="activity-item">
              <div class="avatar avatar-yellow">JL</div>
              <div class="activity-details">
                <p><strong>James Lee</strong> requested ownership documents</p>
                <span class="time">38 min ago</span>
              </div>
            </div>
            <div class="activity-item">
              <div class="avatar avatar-green">NS</div>
              <div class="activity-details">
                <p><strong>Nora Scott</strong> closed bidding for Oakridge Estate</p>
                <span class="time">1 hr ago</span>
              </div>
            </div>
            <div class="activity-item">
              <div class="avatar avatar-purple">RK</div>
              <div class="activity-details">
                <p><strong>Ryan Kim</strong> verified a new buyer profile</p>
                <span class="time">2 hrs ago</span>
              </div>
            </div>
          </div>
          <button class="btn-outline">
            View activity log &rarr;
          </button>
        </div>
      </div>
    </main>
  </div>

  <script>
    // Initialize Feather Icons
    feather.replace();
  </script>
</body>
</html>