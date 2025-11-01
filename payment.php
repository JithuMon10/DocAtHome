<?php
// payment.php

// Capture data from booking form
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$type = trim($_POST['type'] ?? '');
$notes = trim($_POST['notes'] ?? '');
$doctor = trim($_POST['doctor'] ?? '');

// Basic validation: if no name/email/type, redirect back
if ($name === '' || $email === '' || $type === '') {
    header('Location: booking_form.php');
    exit;
}

// Determine cost
$cost = 0;
if ($type === 'chat') {
    $cost = 100;
} elseif ($type === 'video') {
    $cost = 150;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - DocAtHome</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background: linear-gradient(135deg, #0093E9 0%, #80D0C7 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
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
        .payment-container { 
            max-width: 550px; 
            margin: 50px auto;
            animation: slideUp 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            position: relative;
            z-index: 1;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes flipIn {
            from { transform: perspective(1000px) rotateY(-90deg); opacity: 0; }
            to { transform: perspective(1000px) rotateY(0); opacity: 1; }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        .payment-card { 
            border-radius: 25px; 
            box-shadow: 0 30px 80px rgba(0,0,0,0.3); 
            border: none;
            background: white;
            overflow: hidden;
        }
        .card-header { 
            background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%); 
            color: white; 
            border-top-left-radius: 25px; 
            border-top-right-radius: 25px;
            position: relative;
            overflow: hidden;
            padding: 25px;
        }
        .card-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
            animation: pulse 4s infinite;
        }
        .card-header::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            animation: shimmer 3s infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1) rotate(0deg); opacity: 0.5; }
            50% { transform: scale(1.2) rotate(180deg); opacity: 0.8; }
        }
        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 200%; }
        }
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            position: relative;
        }
        .step {
            flex: 1;
            text-align: center;
            position: relative;
        }
        .step-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            margin: 0 auto 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .step.active .step-circle {
            background: white;
            color: #00d2ff;
            transform: scale(1.2);
            box-shadow: 0 4px 15px rgba(255,255,255,0.4);
        }
        .step.completed .step-circle {
            background: #20bf6b;
            color: white;
        }
        .step-label {
            font-size: 0.75rem;
            opacity: 0.9;
        }
        .form-label {
            font-weight: 700;
            color: #495057;
            margin-bottom: 0.7rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-label i {
            color: #3a7bd5;
        }
        .form-control {
            border-radius: 15px;
            padding: 16px 20px;
            border: 2px solid #e9ecef;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            font-size: 1rem;
        }
        .form-control:focus { 
            box-shadow: 0 0 0 5px rgba(0, 210, 255, 0.15), 0 10px 25px rgba(0, 210, 255, 0.1); 
            border-color: #00d2ff;
            transform: translateY(-3px);
            outline: none;
        }
        .form-control.valid {
            border-color: #20bf6b;
            background: linear-gradient(to right, white 95%, rgba(32, 191, 107, 0.05) 100%);
        }
        .form-control.invalid {
            border-color: #ff6b6b;
            animation: shake 0.5s;
        }
        .btn-primary { 
            background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%); 
            border: none; 
            border-radius: 15px; 
            padding: 18px; 
            font-weight: 700;
            font-size: 1.2rem;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 210, 255, 0.3);
        }
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
            transition: left 0.6s;
        }
        .btn-primary:hover::before { left: 200%; }
        .btn-primary:hover { 
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 210, 255, 0.5);
        }
        .btn-primary:active {
            transform: translateY(-2px) scale(0.98);
        }
        .btn-success { 
            background: linear-gradient(135deg, #20bf6b 0%, #01a3a4 100%); 
            border: none; 
            border-radius: 15px; 
            padding: 18px; 
            font-weight: 700;
            font-size: 1.2rem;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: 0 8px 20px rgba(32, 191, 107, 0.3);
        }
        .btn-success:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 35px rgba(32, 191, 107, 0.5);
        }
        .btn-success:active {
            transform: translateY(-2px) scale(0.98);
        }
        
        /* Visual Credit Card */
        .credit-card {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border-radius: 20px;
            padding: 30px;
            color: white;
            position: relative;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
            font-family: 'Courier New', Courier, monospace;
            transition: all 0.5s ease;
            animation: flipIn 0.8s ease;
        }
        .credit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, transparent 100%);
            border-radius: 20px;
        }
        .credit-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }
        .credit-card .card-chip {
            width: 55px;
            height: 42px;
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 50%, #ffd700 100%);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(255, 215, 0, 0.4);
            position: relative;
        }
        .credit-card .card-chip::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 35px;
            height: 28px;
            background: linear-gradient(135deg, rgba(0,0,0,0.2) 0%, transparent 100%);
            border-radius: 4px;
        }
        .credit-card .card-number {
            font-size: 1.6rem;
            letter-spacing: 4px;
            margin-top: 25px;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            position: relative;
            z-index: 2;
        }
        .credit-card .card-holder, .credit-card .card-expiry {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            z-index: 2;
        }
        .credit-card .card-logo {
            position: absolute;
            right: 30px;
            bottom: 25px;
            font-size: 3rem;
            opacity: 0.15;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
            z-index: 0;
            pointer-events: none;
        }
        .credit-card > * {
            position: relative;
            z-index: 10;
        }

        /* Payment Method Selection */
        .payment-method {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            position: relative;
            height: 100%;
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .payment-method:hover {
            border-color: #00d2ff;
            box-shadow: 0 4px 15px rgba(0, 210, 255, 0.2);
            transform: translateY(-2px);
        }
        .payment-method.active {
            border-color: #00d2ff;
            background: linear-gradient(135deg, rgba(0, 210, 255, 0.05) 0%, rgba(58, 123, 213, 0.05) 100%);
            box-shadow: 0 4px 15px rgba(0, 210, 255, 0.3);
        }
        .payment-method.active::after {
            content: '✓';
            position: absolute;
            top: 10px;
            right: 15px;
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        .payment-method-icon {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        .payment-details {
            display: none;
        }
        .payment-details.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .upi-input {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .upi-apps {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 15px;
        }
        .upi-app {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #e9ecef;
        }
        .upi-app:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        #otp-step { display: none; }
        #processing-step { display: none; }
        .spinner-border { 
            width: 5rem; 
            height: 5rem;
            border-width: 0.5rem;
            border-color: #00d2ff;
            border-right-color: transparent;
        }
        .text-primary { color: #3a7bd5 !important; }
        .otp-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
        }
        .otp-input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 1.8rem;
            font-weight: bold;
            border: 3px solid #e9ecef;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .otp-input:focus {
            border-color: #00d2ff;
            box-shadow: 0 0 0 4px rgba(0, 210, 255, 0.15);
            transform: scale(1.1);
            outline: none;
        }
        .otp-input.filled {
            border-color: #20bf6b;
            background: linear-gradient(135deg, #e8f8f0 0%, #d4f4dd 100%);
        }
        .amount-display {
            background: linear-gradient(135deg, #e3f8ff 0%, #d4f1f4 100%);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            border: 2px solid #00d2ff;
        }
        .amount-display h5 {
            color: #3a7bd5;
            font-weight: 700;
            margin: 0;
        }
        .secure-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(32, 191, 107, 0.1);
            color: #20bf6b;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container payment-container">
        <div class="payment-card">
            <div class="card-header text-center">
                <h4><i class="bi bi-shield-lock-fill"></i> Secure Payment</h4>
                <div class="secure-badge mt-2">
                    <i class="bi bi-check-circle-fill"></i>
                    SSL Encrypted
                </div>
                <div class="step-indicator">
                    <div class="step active" id="step1">
                        <div class="step-circle">1</div>
                        <div class="step-label">Card Details</div>
                    </div>
                    <div class="step" id="step2">
                        <div class="step-circle">2</div>
                        <div class="step-label">Verify OTP</div>
                    </div>
                    <div class="step" id="step3">
                        <div class="step-circle">3</div>
                        <div class="step-label">Processing</div>
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- This form will submit the final data to the booking handler -->
                <form id="finalBookingForm" action="booking_handler.php" method="POST">
                    <!-- Hidden fields to pass booking data -->
                    <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>">
                    <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <input type="hidden" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
                    <input type="hidden" name="type" value="<?php echo htmlspecialchars($type); ?>">
                    <input type="hidden" name="notes" value="<?php echo htmlspecialchars($notes); ?>">
                    <input type="hidden" name="doctor" value="<?php echo htmlspecialchars($doctor); ?>">

                    <!-- Step 1: Payment Details -->
                    <div id="payment-step">
                        <div class="mb-4">
                            <h5><i class="bi bi-cash-coin"></i> Total Amount: ₹<?php echo $cost; ?>.00</h5>
                            <small class="text-muted"><?php echo ucfirst($type); ?> Consultation</small>
                        </div>
                        
                        <!-- Payment Method Selection -->
                        <div class="mb-4">
                            <h6 class="mb-3"><i class="bi bi-wallet2"></i> Select Payment Method</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="payment-method active" data-method="card">
                                        <div class="text-center">
                                            <div class="payment-method-icon"><i class="bi bi-credit-card-fill" style="color: #00d2ff;"></i></div>
                                            <h6 class="mb-0">Credit/Debit Card</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="payment-method" data-method="upi">
                                        <div class="text-center">
                                            <div class="payment-method-icon"><i class="bi bi-phone-fill" style="color: #20bf6b;"></i></div>
                                            <h6 class="mb-0">UPI</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="payment-method" data-method="netbanking">
                                        <div class="text-center">
                                            <div class="payment-method-icon"><i class="bi bi-bank" style="color: #f39c12;"></i></div>
                                            <h6 class="mb-0">Net Banking</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Credit Card Details -->
                        <div class="payment-details active" id="card-details">
                        <div class="credit-card mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="card-chip"></div>
                                <div class="card-logo"><i class="bi bi-credit-card-2-front-fill"></i></div>
                            </div>
                            <div class="card-number" id="cc-number-display">#### #### #### ####</div>
                            <div class="d-flex justify-content-between mt-3">
                                <div>
                                    <div class="card-holder text-muted">Card Holder</div>
                                    <div id="cc-holder-display"><?php echo htmlspecialchars($name); ?></div>
                                </div>
                                <div>
                                    <div class="card-expiry text-muted">Expires</div>
                                    <div id="cc-expiry-display">MM/YY</div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="cardNumber" class="form-label">
                                <i class="bi bi-credit-card"></i> Card Number
                            </label>
                            <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9101 1121" value="4444 4444 4444 4444" maxlength="19">
                        </div>
                        <div class="row mb-3">
                            <div class="col-7">
                                <label for="expiryDate" class="form-label">
                                    <i class="bi bi-calendar-event"></i> Expiry Date
                                </label>
                                <input type="text" class="form-control" id="expiryDate" placeholder="MM/YY" value="12/28" maxlength="5">
                            </div>
                            <div class="col-5">
                                <label for="cvv" class="form-label">
                                    <i class="bi bi-lock-fill"></i> CVV
                                </label>
                                <input type="password" class="form-control" id="cvv" placeholder="123" value="123" maxlength="3">
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="button" id="payBtn" class="btn btn-primary btn-lg">
                                <i class="bi bi-lock-fill"></i> Pay ₹<?php echo $cost; ?>
                            </button>
                        </div>
                        </div>
                        
                        <!-- UPI Payment Details -->
                        <div class="payment-details" id="upi-details">
                            <div class="mb-4">
                                <label class="form-label"><i class="bi bi-phone-fill"></i> Enter UPI ID</label>
                                <input type="text" class="form-control" id="upiId" placeholder="yourname@upi" value="demo@paytm">
                            </div>
                            <div class="d-grid">
                                <button type="button" id="upiPayBtn" class="btn btn-primary btn-lg">
                                    <i class="bi bi-phone-fill"></i> Pay with UPI
                                </button>
                            </div>
                        </div>
                        
                        <!-- NetBanking Details -->
                        <div class="payment-details" id="netbanking-details">
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-bank"></i> Select Your Bank</label>
                                <select class="form-select" id="bankSelect">
                                    <option value="">Choose your bank...</option>
                                    <option value="sbi">State Bank of India</option>
                                    <option value="hdfc">HDFC Bank</option>
                                    <option value="icici">ICICI Bank</option>
                                    <option value="axis">Axis Bank</option>
                                    <option value="kotak">Kotak Mahindra Bank</option>
                                    <option value="pnb">Punjab National Bank</option>
                                    <option value="bob">Bank of Baroda</option>
                                    <option value="canara">Canara Bank</option>
                                    <option value="other">Other Banks</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-person-fill"></i> User ID</label>
                                <input type="text" class="form-control" id="bankUserId" placeholder="Enter your User ID" value="demo123">
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><i class="bi bi-lock-fill"></i> Password</label>
                                <input type="password" class="form-control" id="bankPassword" placeholder="Enter your Password" value="password">
                            </div>
                            <div class="d-grid">
                                <button type="button" id="netbankingPayBtn" class="btn btn-primary btn-lg">
                                    <i class="bi bi-shield-check"></i> Proceed to Pay
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: OTP Verification -->
                    <div id="otp-step" class="text-center">
                        <div class="mb-4">
                            <i class="bi bi-phone-fill" style="font-size: 3rem; color: #00d2ff;"></i>
                            <h5 class="mt-3 mb-2"><i class="bi bi-shield-check"></i> OTP Verification</h5>
                            <p class="text-muted">Enter the 6-digit code sent to your phone</p>
                        </div>
                        <div class="otp-inputs">
                            <input type="text" class="otp-input" maxlength="1" data-index="0">
                            <input type="text" class="otp-input" maxlength="1" data-index="1">
                            <input type="text" class="otp-input" maxlength="1" data-index="2">
                            <input type="text" class="otp-input" maxlength="1" data-index="3">
                            <input type="text" class="otp-input" maxlength="1" data-index="4">
                            <input type="text" class="otp-input" maxlength="1" data-index="5">
                        </div>
                        <div class="d-grid mt-4">
                            <button type="button" id="verifyBtn" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle-fill"></i> Verify & Proceed
                            </button>
                        </div>
                        <p class="text-muted mt-3 small">Didn't receive code? <a href="#" class="text-primary">Resend</a></p>
                    </div>

                    <!-- Step 3: Processing -->
                    <div id="processing-step" class="text-center p-5">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h5 class="mt-4 text-primary"><i class="bi bi-hourglass-split"></i> Processing Payment...</h5>
                        <p class="text-muted mt-2">Please wait while we confirm your booking</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    // Elements
    const paymentStep = document.getElementById('payment-step');
    const otpStep = document.getElementById('otp-step');
    const processingStep = document.getElementById('processing-step');
    const payBtn = document.getElementById('payBtn');
    const verifyBtn = document.getElementById('verifyBtn');
    const finalBookingForm = document.getElementById('finalBookingForm');
    const cardNumberInput = document.getElementById('cardNumber');
    const expiryDateInput = document.getElementById('expiryDate');
    const cvvInput = document.getElementById('cvv');
    const ccNumberDisplay = document.getElementById('cc-number-display');
    const ccExpiryDisplay = document.getElementById('cc-expiry-display');
    const otpInputs = document.querySelectorAll('.otp-input');
    
    // Step indicators
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    const step3 = document.getElementById('step3');

    // Payment method switching
    const paymentMethods = document.querySelectorAll('.payment-method');
    const paymentDetails = document.querySelectorAll('.payment-details');
    
    paymentMethods.forEach(method => {
        method.addEventListener('click', () => {
            // Remove active class from all methods
            paymentMethods.forEach(m => m.classList.remove('active'));
            // Add active class to clicked method
            method.classList.add('active');
            
            // Hide all payment details
            paymentDetails.forEach(detail => detail.classList.remove('active'));
            
            // Show selected payment details
            const selectedMethod = method.getAttribute('data-method');
            document.getElementById(`${selectedMethod}-details`).classList.add('active');
        });
    });
    
    // UPI Pay button
    const upiPayBtn = document.getElementById('upiPayBtn');
    if (upiPayBtn) {
        upiPayBtn.addEventListener('click', () => {
            const upiId = document.getElementById('upiId').value;
            if (!upiId) {
                alert('Please enter your UPI ID');
                return;
            }
            proceedToOTP();
        });
    }
    
    // NetBanking Pay button
    const netbankingPayBtn = document.getElementById('netbankingPayBtn');
    if (netbankingPayBtn) {
        netbankingPayBtn.addEventListener('click', () => {
            const bank = document.getElementById('bankSelect').value;
            const userId = document.getElementById('bankUserId').value;
            const password = document.getElementById('bankPassword').value;
            
            if (!bank) {
                alert('Please select your bank');
                return;
            }
            if (!userId) {
                alert('Please enter your User ID');
                return;
            }
            if (!password) {
                alert('Please enter your Password');
                return;
            }
            proceedToOTP();
        });
    }

    // Card number formatting and validation
    cardNumberInput.addEventListener('input', (e) => {
        let val = e.target.value.replace(/\D/g, '');
        if (val.length > 16) val = val.slice(0, 16);
        
        let formatted = val.replace(/(\d{4})/g, '$1 ').trim();
        e.target.value = formatted;
        ccNumberDisplay.textContent = formatted || '#### #### #### ####';
        
        if (val.length === 16) {
            e.target.classList.add('valid');
            e.target.classList.remove('invalid');
        } else if (val.length > 0) {
            e.target.classList.add('invalid');
            e.target.classList.remove('valid');
        }
    });

    // Expiry date formatting
    expiryDateInput.addEventListener('input', (e) => {
        let val = e.target.value.replace(/\D/g, '');
        if (val.length >= 2) {
            val = val.slice(0, 2) + '/' + val.slice(2, 4);
        }
        e.target.value = val;
        ccExpiryDisplay.textContent = val || 'MM/YY';
        
        if (val.length === 5) {
            e.target.classList.add('valid');
            e.target.classList.remove('invalid');
        } else if (val.length > 0) {
            e.target.classList.add('invalid');
            e.target.classList.remove('valid');
        }
    });

    // CVV validation
    cvvInput.addEventListener('input', (e) => {
        let val = e.target.value.replace(/\D/g, '');
        e.target.value = val;
        
        if (val.length === 3) {
            e.target.classList.add('valid');
            e.target.classList.remove('invalid');
        } else if (val.length > 0) {
            e.target.classList.add('invalid');
            e.target.classList.remove('valid');
        }
    });

    // Common function to proceed to OTP
    function proceedToOTP() {
        paymentStep.style.display = 'none';
        otpStep.style.display = 'block';
        step1.classList.remove('active');
        step1.classList.add('completed');
        step2.classList.add('active');
        otpInputs[0].focus();
    }

    // Pay button click
    payBtn.addEventListener('click', () => {
        const cardNum = cardNumberInput.value.replace(/\s/g, '');
        const expiry = expiryDateInput.value;
        const cvv = cvvInput.value;
        
        if (cardNum.length !== 16) {
            alert('Please enter a valid 16-digit card number');
            cardNumberInput.focus();
            return;
        }
        if (expiry.length !== 5) {
            alert('Please enter a valid expiry date (MM/YY)');
            expiryDateInput.focus();
            return;
        }
        if (cvv.length !== 3) {
            alert('Please enter a valid 3-digit CVV');
            cvvInput.focus();
            return;
        }
        
        proceedToOTP();
    });

    // OTP input handling with auto-advance
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            const val = e.target.value;
            
            if (val && /^\d$/.test(val)) {
                input.classList.add('filled');
                // Move to next input
                if (index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            } else {
                input.classList.remove('filled');
                e.target.value = '';
            }
        });
        
        input.addEventListener('keydown', (e) => {
            // Handle backspace
            if (e.key === 'Backspace' && !input.value && index > 0) {
                otpInputs[index - 1].focus();
                otpInputs[index - 1].value = '';
                otpInputs[index - 1].classList.remove('filled');
            }
            
            // Handle paste
            if (e.key === 'v' && (e.ctrlKey || e.metaKey)) {
                e.preventDefault();
                navigator.clipboard.readText().then(text => {
                    const digits = text.replace(/\D/g, '').slice(0, 6);
                    digits.split('').forEach((digit, i) => {
                        if (otpInputs[i]) {
                            otpInputs[i].value = digit;
                            otpInputs[i].classList.add('filled');
                        }
                    });
                });
            }
        });
    });

    // Verify button click
    verifyBtn.addEventListener('click', () => {
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        
        if (otp.length !== 6) {
            alert('Please enter all 6 digits of the OTP');
            otpInputs[0].focus();
            return;
        }
        
        if (!/^\d{6}$/.test(otp)) {
            alert('Invalid OTP. Please enter only numbers.');
            otpInputs.forEach(input => {
                input.value = '';
                input.classList.remove('filled');
            });
            otpInputs[0].focus();
            return;
        }
        
        // Show processing
        otpStep.style.display = 'none';
        processingStep.style.display = 'block';
        step2.classList.remove('active');
        step2.classList.add('completed');
        step3.classList.add('active');
        
        // Submit form after delay
        setTimeout(() => {
            finalBookingForm.submit();
        }, 2000);
    });
</script>

</body>
</html>