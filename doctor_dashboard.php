<?php
require_once 'db.php';

// Ensure minimal bookings schema exists
function ensure_bookings_schema(mysqli $conn): void {
    $conn->query("CREATE TABLE IF NOT EXISTS bookings (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(150) NOT NULL,
        email VARCHAR(200) NOT NULL,
        phone VARCHAR(30) DEFAULT NULL,
        type ENUM('chat','video') NOT NULL,
        notes TEXT,
        status ENUM('pending','completed') NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

ensure_bookings_schema($conn);

// Analytics Queries
$totalBookingsResult = $conn->query("SELECT COUNT(*) as count FROM bookings");
$totalBookings = ($totalBookingsResult && $totalBookingsResult->num_rows > 0) ? $totalBookingsResult->fetch_assoc()['count'] : 0;

$pendingBookingsResult = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'");
$pendingBookings = ($pendingBookingsResult && $pendingBookingsResult->num_rows > 0) ? $pendingBookingsResult->fetch_assoc()['count'] : 0;

$totalRevenueResult = $conn->query("SELECT SUM(CASE WHEN type = 'chat' THEN 100 WHEN type = 'video' THEN 150 ELSE 0 END) as total FROM bookings WHERE status = 'completed'");
$totalRevenue = ($totalRevenueResult && $totalRevenueResult->num_rows > 0) ? $totalRevenueResult->fetch_assoc()['total'] : 0;

$todayRevenueResult = $conn->query("SELECT SUM(CASE WHEN type = 'chat' THEN 100 WHEN type = 'video' THEN 150 ELSE 0 END) as total FROM bookings WHERE status = 'completed' AND DATE(created_at) = CURDATE()");
$todayRevenue = ($todayRevenueResult && $todayRevenueResult->num_rows > 0) ? $todayRevenueResult->fetch_assoc()['total'] : 0;

$result = $conn->query('SELECT id, name, email, phone, type, status, notes, created_at FROM bookings ORDER BY id DESC');
if (!$result) {
    die('Query failed: ' . $conn->error);
}

// Get the ID of the most recent booking to use as a baseline for notifications
$latestBookingId = 0;
if ($result && $result->num_rows > 0) {
    $firstRow = $result->fetch_assoc();
    $latestBookingId = (int)$firstRow['id'];
    $result->data_seek(0); // Reset pointer to the beginning for the loop
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - DocAtHome</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            --warning-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .dashboard-header {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            margin-bottom: 2rem;
        }
        .stat-card {
            border: none;
            border-radius: 15px;
            transition: all 0.3s ease;
            overflow: hidden;
            position: relative;
            margin-bottom: 1.5rem;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: inherit;
            opacity: 0.9;
        }
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        }
        .stat-card .card-body {
            position: relative;
            z-index: 1;
        }
        .stat-card.primary { background: var(--primary-gradient); }
        .stat-card.success { background: var(--success-gradient); }
        .stat-card.warning { background: var(--warning-gradient); }
        .stat-card.info { background: var(--info-gradient); }
        
        .stat-icon {
            font-size: 3rem;
            opacity: 0.3;
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
        }
        .stat-label {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
        }
        .bookings-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.07);
            border: none;
            overflow: hidden;
        }
        .bookings-card .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1.5rem;
        }
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            color: #495057;
        }
        .table tbody tr {
            transition: all 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }
        .badge-type {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
        }
        .badge-chat {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .badge-video {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }
        .badge-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
        }
        .badge-pending {
            background: #ffc107;
            color: #000;
        }
        .badge-completed {
            background: #28a745;
            color: white;
        }
        .btn-action {
            border-radius: 20px;
            padding: 0.5rem 1.2rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-action:hover {
            transform: scale(1.05);
        }
        .refresh-indicator {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            animation: slideIn 0.3s ease;
        }
        .refresh-indicator.show {
            display: block;
        }
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .checking {
            animation: pulse 1.5s infinite;
        }
        /* Responsive Table */
        @media (max-width: 767px) {
            .table thead { display: none; }
            .table, .table tbody, .table tr, .table td {
                display: block;
                width: 100%;
            }
            .table tr {
                margin-bottom: 1rem;
                border: 1px solid #dee2e6;
                border-radius: 10px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            }
            .table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
                border: none;
            }
            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 1rem;
                width: 45%;
                padding-right: 1rem;
                text-align: left;
                font-weight: bold;
            }
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="dashboard-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-1"><i class="bi bi-heart-pulse-fill text-danger"></i> Doctor Dashboard</h2>
                <p class="text-muted mb-0">Manage your patient consultations</p>
            </div>
            <div>
                <a class="btn btn-outline-secondary me-2" href="index.html"><i class="bi bi-house-fill"></i> Home</a>
                <button id="refreshBtn" class="btn btn-primary"><i class="bi bi-arrow-clockwise"></i> Refresh</button>
            </div>
        </div>
    </div>
    
    <div class="refresh-indicator" id="refreshIndicator">
        <i class="bi bi-check-circle-fill text-success"></i> Checking for new bookings...
    </div>

    <!-- Analytics Section -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-2">Total Revenue</p>
                            <h3 class="stat-value">₹<?php echo number_format($totalRevenue ?? 0, 0); ?></h3>
                        </div>
                        <i class="bi bi-cash-stack stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-2">Today's Revenue</p>
                            <h3 class="stat-value">₹<?php echo number_format($todayRevenue ?? 0, 0); ?></h3>
                        </div>
                        <i class="bi bi-calendar-check stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-2">Total Bookings</p>
                            <h3 class="stat-value"><?php echo $totalBookings; ?></h3>
                        </div>
                        <i class="bi bi-journal-medical stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card stat-card info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label mb-2">Pending</p>
                            <h3 class="stat-value"><?php echo $pendingBookings; ?></h3>
                        </div>
                        <i class="bi bi-hourglass-split stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card bookings-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="bi bi-clipboard2-pulse"></i> All Bookings</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="ID"><?php echo htmlspecialchars((string)$row['id']); ?></td>
                            <td data-label="Name"><?php echo htmlspecialchars($row['name']); ?></td>
                            <td data-label="Email"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td data-label="Phone"><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td data-label="Type">
                                <span class="badge-type badge-<?php echo htmlspecialchars($row['type']); ?>">
                                    <?php if($row['type'] === 'chat'): ?>
                                        <i class="bi bi-chat-dots"></i> Chat
                                    <?php else: ?>
                                        <i class="bi bi-camera-video"></i> Video
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td data-label="Status">
                                <span class="badge-status badge-<?php echo ($row['status'] ?? 'pending'); ?>">
                                    <?php if(($row['status'] ?? 'pending') === 'completed'): ?>
                                        <i class="bi bi-check-circle"></i> Completed
                                    <?php else: ?>
                                        <i class="bi bi-clock"></i> Pending
                                    <?php endif; ?>
                                </span>
                            </td>
                            <td data-label="Created"><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td data-label="Actions">
                                <div class="d-flex gap-2 flex-wrap">
                                    <?php if ($row['type'] === 'chat'): ?>
                                        <a class="btn btn-sm btn-primary btn-action" href="doctor_chat.php?booking_id=<?php echo urlencode((string)$row['id']); ?>">
                                            <i class="bi bi-chat-dots"></i> Open Chat
                                        </a>
                                    <?php else: ?>
                                        <a class="btn btn-sm btn-success btn-action" href="video_call.php?booking_id=<?php echo urlencode((string)$row['id']); ?>&role=doctor">
                                            <i class="bi bi-camera-video"></i> Start Video
                                        </a>
                                    <?php endif; ?>
                                    <?php if (($row['status'] ?? 'pending') === 'completed'): ?>
                                        <button class="btn btn-sm btn-outline-success btn-action" disabled>
                                            <i class="bi bi-check-circle-fill"></i> Completed
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-outline-warning btn-action" onclick="markComplete(<?php echo (int)$row['id']; ?>)">
                                            <i class="bi bi-check-circle"></i> Complete
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr><td colspan="8" class="text-center">No bookings found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<audio id="notificationSound" src="assets/notification.mp3" preload="auto"></audio>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const latestIdOnLoad = <?php echo $latestBookingId; ?>;
  let isChecking = false;

  // Request notification permission when the page loads
  document.addEventListener('DOMContentLoaded', () => {
    if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
      Notification.requestPermission();
    }
  });

  function playNotification() {
    const sound = document.getElementById('notificationSound');
    if (sound) {
      sound.play().catch(e => console.log("Audio playback requires user interaction first."));
    }
  }

  function showNotification(message, type = 'info') {
    const indicator = document.getElementById('refreshIndicator');
    const icon = type === 'success' ? 'check-circle-fill' : 'info-circle-fill';
    const color = type === 'success' ? 'success' : 'info';
    
    indicator.innerHTML = `<i class="bi bi-${icon} text-${color}"></i> ${message}`;
    indicator.classList.add('show');
    
    setTimeout(() => {
      indicator.classList.remove('show');
    }, 3000);
    
    if (Notification.permission === 'granted' && type === 'success') {
      new Notification('New Booking Arrived!', {
        body: message,
        icon: 'favicon.ico'
      });
    }
  }

  function checkForNewBookings() {
    if (isChecking) return;
    isChecking = true;
    
    const indicator = document.getElementById('refreshIndicator');
    indicator.innerHTML = '<i class="bi bi-arrow-repeat checking"></i> Checking for new bookings...';
    indicator.classList.add('show');
    
    fetch(`check_new_bookings.php?latest_id=${latestIdOnLoad}`)
      .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
      })
      .then(data => {
        indicator.classList.remove('show');
        if (data && data.new_count > 0) {
          playNotification();
          showNotification(`${data.new_count} new booking(s) arrived! Refreshing...`, 'success');
          setTimeout(() => location.reload(), 2000);
        }
      })
      .catch(error => {
        console.error('Error checking for new bookings:', error);
        indicator.classList.remove('show');
      })
      .finally(() => {
        isChecking = false;
      });
  }

  // Check every 15 seconds
  setInterval(checkForNewBookings, 15000);

  // Refresh button
  document.getElementById('refreshBtn').onclick = function() {
    showNotification('Refreshing dashboard...', 'info');
    setTimeout(() => location.reload(), 500);
  };
  
  // Mark complete function with better error handling
  function markComplete(id) {
    const btn = event.target.closest('button');
    const originalHTML = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Processing...';
    
    fetch('mark_complete.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: 'id=' + encodeURIComponent(id)
    })
    .then(response => {
      if (!response.ok) throw new Error('Network response was not ok');
      return response.json();
    })
    .then(data => {
      if (data && data.success) {
        showNotification('Booking marked as completed!', 'success');
        setTimeout(() => location.reload(), 1000);
      } else {
        throw new Error('Server returned failure');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Failed to mark as complete. Please try again.');
      btn.disabled = false;
      btn.innerHTML = originalHTML;
    });
  }
</script>
