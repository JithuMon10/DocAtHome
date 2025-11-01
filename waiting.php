<?php
$bookingId = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;
$role = isset($_GET['role']) ? $_GET['role'] : 'patient';

if ($bookingId <= 0) {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiting for Doctor - DocAtHome</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .waiting-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0093E9 0%, #80D0C7 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
        }
        .waiting-container::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg"><circle cx="30" cy="30" r="25" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="2"/></svg>');
            opacity: 0.4;
            z-index: 0;
        }
        .waiting-card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.3);
            padding: 3.5rem;
            text-align: center;
            max-width: 700px;
            width: 90%;
            position: relative;
            overflow: hidden;
            animation: slideUp 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            z-index: 1;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .waiting-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #00d2ff, #3a7bd5, #20bf6b, #01a3a4, #00d2ff);
            background-size: 200% 100%;
            animation: color-flow 3s infinite linear;
        }
        @keyframes color-flow {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }
        .spinner {
            width: 100px;
            height: 100px;
            margin: 0 auto 2.5rem;
        }
        .spinner svg {
            animation: rotate 2s linear infinite;
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 4px 8px rgba(0, 210, 255, 0.3));
        }
        .spinner .path {
            stroke: url(#gradient);
            stroke-linecap: round;
            animation: dash 1.5s ease-in-out infinite;
        }
        .status-dot {
            width: 14px;
            height: 14px;
            background-color: #28a745;
            border-radius: 50%;
            display: inline-block;
            margin-right: 10px;
            animation: blink 1.5s infinite;
        }
        @keyframes rotate { 100% { transform: rotate(360deg); } }
        @keyframes dash {
            0% { stroke-dasharray: 1, 150; stroke-dashoffset: 0; }
            50% { stroke-dasharray: 90, 150; stroke-dashoffset: -35; }
            100% { stroke-dasharray: 90, 150; stroke-dashoffset: -124; }
        }
        .waiting-card h2 {
            font-weight: 700;
            background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.3; }
        }
        .waiting-steps {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 18px;
            padding: 25px;
            margin: 25px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .step-item {
            display: flex;
            align-items: center;
            margin: 12px 0;
            padding: 15px;
            border-radius: 12px;
            transition: all 0.4s ease;
            background: white;
        }
        .step-item.active {
            background: linear-gradient(135deg, #e3f8ff 0%, #d4f1f4 100%);
            border-left: 5px solid #00d2ff;
            transform: translateX(5px);
            box-shadow: 0 4px 12px rgba(0, 210, 255, 0.2);
        }
        .step-item.completed {
            background: linear-gradient(135deg, #e8f8f0 0%, #d4f4dd 100%);
            border-left: 5px solid #20bf6b;
            box-shadow: 0 4px 12px rgba(32, 191, 107, 0.2);
        }
        .step-number {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 18px;
            box-shadow: 0 4px 10px rgba(0, 210, 255, 0.3);
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }
        .step-item.completed .step-number {
            background: linear-gradient(135deg, #20bf6b 0%, #01a3a4 100%);
            box-shadow: 0 4px 10px rgba(32, 191, 107, 0.3);
        }
        .step-item.active .step-number {
            animation: pulse-step 1.5s infinite;
        }
        @keyframes pulse-step {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .countdown {
            font-size: 2.8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin: 25px 0;
        }
        .booking-info {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 20px;
            margin: 25px 0;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 2px solid #e9ecef;
        }
        .btn-custom {
            border-radius: 25px;
            padding: 14px 35px;
            font-weight: 700;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        .btn-custom:active {
            transform: translateY(-1px);
        }
        .btn-primary.btn-custom {
            background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%);
            border: none;
        }
        .btn-primary.btn-custom:hover {
            box-shadow: 0 10px 25px rgba(0, 210, 255, 0.4);
        }
        .alert-info {
            border-radius: 15px;
            border: none;
            background: linear-gradient(135deg, #e3f8ff 0%, #d4f1f4 100%);
            box-shadow: 0 4px 12px rgba(0, 210, 255, 0.15);
            border: 2px solid #00d2ff;
        }
        .status-dot {
            background-color: #20bf6b;
        }
    </style>
</head>
<body>
    <div class="waiting-container">
        <div class="waiting-card">
            <div class="spinner">
                <svg viewBox="25 25 50 50">
                    <defs>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#00d2ff;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#3a7bd5;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="4" />
                </svg>
            </div>
            
            <h2 class="mb-4 text-primary">
                <i class="bi bi-camera-video"></i> Waiting for Doctor
            </h2>
            
            <div class="booking-info">
                <h5><i class="bi bi-calendar-check"></i> Booking Confirmed</h5>
                <p class="mb-0"><strong>Booking ID:</strong> #<?php echo htmlspecialchars((string)$bookingId); ?></p>
                <p class="mb-0"><strong>Status:</strong> <span class="text-success">Confirmed & Waiting for Doctor</span></p>
            </div>
            
            <div class="waiting-steps">
                <h6 class="mb-3"><i class="bi bi-list-check"></i> What's happening:</h6>
                
                <div class="step-item active" id="step1">
                    <div class="step-number">1</div>
                    <div>
                        <strong>Booking Confirmed</strong><br>
                        <small class="text-muted">Your appointment is scheduled</small>
                    </div>
                </div>
                
                <div class="step-item" id="step2">
                    <div class="step-number">2</div>
                    <div>
                        <strong>Waiting for Doctor</strong><br>
                        <small class="text-muted">Doctor will join the call shortly</small>
                    </div>
                </div>
                
                <div class="step-item" id="step3">
                    <div class="step-number">3</div>
                    <div>
                        <strong>Video Call Starts</strong><br>
                        <small class="text-muted">You'll be automatically redirected</small>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info">
                <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Preparation Tips:</h6>
                <ul class="list-unstyled mb-0 text-start">
                    <li><i class="bi bi-check-circle text-success"></i> Ensure good lighting</li>
                    <li><i class="bi bi-check-circle text-success"></i> Test your camera and microphone</li>
                    <li><i class="bi bi-check-circle text-success"></i> Find a quiet, private space</li>
                    <li><i class="bi bi-check-circle text-success"></i> Have your questions ready</li>
                </ul>
            </div>
            
            <div class="countdown" id="countdown">
                <i class="bi bi-clock"></i> <span id="timer">00:00</span>
            </div>
            
            <div class="mt-4">
                <a href="index.html" class="btn btn-outline-secondary btn-custom me-2">
                    <i class="bi bi-house"></i> Back to Home
                </a>
                <button id="refreshBtn" class="btn btn-primary btn-custom">
                    <i class="bi bi-arrow-clockwise"></i> Refresh Status
                </button>
            </div>
            
            <div class="mt-4">
                <small class="text-muted">
                    <i class="bi bi-shield-check"></i> 
                    If you've been waiting for more than 5 minutes, please contact support.
                </small>
            </div>
        </div>
    </div>

    <script>
        const bookingId = <?php echo $bookingId; ?>;
        const role = '<?php echo htmlspecialchars($role); ?>';
        
        let startTime = Date.now();
        let timerInterval;
        let checkInterval;
        
        // Timer functionality
        function updateTimer() {
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            const minutes = Math.floor(elapsed / 60);
            const seconds = elapsed % 60;
            document.getElementById('timer').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        }
        
        // Update steps based on progress
        function updateSteps() {
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            
            // Step 1 is always completed
            document.getElementById('step1').classList.add('completed');
            
            // Step 2 is active after 5 seconds
            if (elapsed > 5) {
                document.getElementById('step2').classList.add('active');
            }
            
            // Step 3 will be activated when doctor joins
        }
        
        // Check if doctor is ready every 2 seconds
        function checkDoctorReady() {
            fetch(`chats/booking_${bookingId}.join?t=${Date.now()}`)
                .then(response => {
                    if (response.ok) {
                        // Doctor has joined, update UI and redirect
                        document.getElementById('step2').classList.remove('active');
                        document.getElementById('step2').classList.add('completed');
                        document.getElementById('step3').classList.add('active');
                        
                        // Show connecting message
                        document.querySelector('.spinner').style.display = 'none';
                        document.querySelector('h2').innerHTML = '<i class="bi bi-camera-video"></i> Doctor Joined! Connecting...';
                        
                        // Redirect after a short delay
                        setTimeout(() => {
                            window.location.href = `video_call.php?booking_id=${bookingId}&role=${role}`;
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.log('Waiting for doctor to join...');
                });
        }
        
        // Initialize
        function init() {
            // Start timer
            timerInterval = setInterval(updateTimer, 1000);
            
            // Start step updates
            setInterval(updateSteps, 1000);
            
            // Check for doctor immediately and then every 2 seconds
            checkDoctorReady();
            checkInterval = setInterval(checkDoctorReady, 2000);
            
            // Manual refresh button
            document.getElementById('refreshBtn').addEventListener('click', function() {
                checkDoctorReady();
                this.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Checking...';
                setTimeout(() => {
                    this.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Refresh Status';
                }, 2000);
            });
        }
        
        // Stop checking after 10 minutes to prevent infinite polling
        setTimeout(() => {
            if (checkInterval) clearInterval(checkInterval);
            if (timerInterval) clearInterval(timerInterval);
            
            document.querySelector('.waiting-card').innerHTML = `
                <div class="text-center">
                    <div class="spinner" style="border-color: #ffc107; border-top-color: #ffc107;"></div>
                    <h2 class="text-warning mb-4">
                        <i class="bi bi-exclamation-triangle"></i> Still Waiting
                    </h2>
                    <p class="text-muted mb-4">
                        It's been a while. The doctor might be busy or there could be a technical issue.
                    </p>
                    <div class="alert alert-warning">
                        <h6><i class="bi bi-info-circle"></i> What you can do:</h6>
                        <ul class="list-unstyled mb-0 text-start">
                            <li>• Try refreshing the page</li>
                            <li>• Check your internet connection</li>
                            <li>• Contact support if the issue persists</li>
                        </ul>
                    </div>
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-custom" onclick="location.reload()">
                            <i class="bi bi-arrow-clockwise"></i> Try Again
                        </button>
                        <a href="index.html" class="btn btn-outline-secondary btn-custom">
                            <i class="bi bi-house"></i> Back to Home
                        </a>
                    </div>
                </div>
            `;
        }, 600000); // 10 minutes
        
        // Start the waiting process
        init();
    </script>
</body>
</html>
