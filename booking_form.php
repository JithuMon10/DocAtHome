<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Consultation - DocAtHome</title>
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
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="2"/></svg>');
            opacity: 0.3;
            z-index: 0;
        }
        .booking-card {
            background-color: white;
            border-radius: 25px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.3);
            overflow: visible;
            animation: slideUp 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            position: relative;
            z-index: 1;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        @keyframes checkmark {
            0% { transform: scale(0) rotate(45deg); }
            50% { transform: scale(1.2) rotate(45deg); }
            100% { transform: scale(1) rotate(45deg); }
        }
        .card-header {
            background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%);
            position: relative;
            overflow: hidden;
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
        .progress-bar-container {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: rgba(255,255,255,0.2);
        }
        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #38ef7d, #11998e);
            transition: width 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            width: 0%;
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
            font-size: 1.1rem;
        }
        .form-label .required-star {
            color: #ff6b6b;
            font-size: 0.8rem;
        }
        .input-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .form-control, .form-select {
            border-radius: 15px;
            padding: 16px 20px;
            padding-right: 45px;
            border: 2px solid #e9ecef;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            font-size: 1rem;
            width: 100%;
            background: white;
        }
        .form-control:focus, .form-select:focus {
            border-color: #00d2ff;
            box-shadow: 0 0 0 5px rgba(0, 210, 255, 0.15), 0 10px 25px rgba(0, 210, 255, 0.1);
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
        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
            opacity: 0;
            transition: all 0.3s ease;
            pointer-events: none;
        }
        .input-icon.show {
            opacity: 1;
        }
        .input-icon.valid {
            color: #20bf6b;
        }
        .input-icon.invalid {
            color: #ff6b6b;
        }
        .char-counter {
            position: absolute;
            right: 15px;
            bottom: -22px;
            font-size: 0.75rem;
            color: #6c757d;
            transition: color 0.3s ease;
        }
        .char-counter.warning {
            color: #f39c12;
        }
        .char-counter.danger {
            color: #ff6b6b;
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
        .btn-primary:hover::before {
            left: 200%;
        }
        .btn-primary:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 35px rgba(0, 210, 255, 0.5);
        }
        .btn-primary:active {
            transform: translateY(-2px) scale(0.98);
        }
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        .price-summary {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            padding: 20px;
            margin: 25px 0;
            border: 2px solid #e9ecef;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .price-summary h5 {
            color: #495057;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .price-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .price-item:last-child {
            border-bottom: none;
            padding-top: 15px;
            font-size: 1.3rem;
            font-weight: 700;
            color: #3a7bd5;
        }
        .tooltip-info {
            position: relative;
            display: inline-block;
            cursor: help;
        }
        .tooltip-info .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 8px;
            padding: 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.85rem;
        }
        .tooltip-info:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }
        .consult-type-label {
            display: block;
            padding: 30px 20px;
            border: 3px solid #e9ecef;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            text-align: center;
            background: white;
            position: relative;
            overflow: hidden;
        }
        .consult-type-label::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 210, 255, 0.05) 0%, rgba(58, 123, 213, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .consult-type-label:hover {
            border-color: #00d2ff;
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 12px 30px rgba(0, 210, 255, 0.2);
        }
        .consult-type-label:hover::before {
            opacity: 1;
        }
        .consult-type-input:checked + .consult-type-label {
            border-color: #00d2ff;
            background: linear-gradient(135deg, #e3f8ff 0%, #d4f1f4 100%);
            box-shadow: 0 12px 35px rgba(0, 210, 255, 0.3);
            transform: translateY(-8px) scale(1.05);
        }
        .consult-type-input:checked + .consult-type-label::after {
            content: '✓';
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, #20bf6b 0%, #01a3a4 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1rem;
            animation: checkmark 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        .consult-type-input {
            display: none;
        }
        .consult-type-icon {
            font-size: 3.5rem;
            color: #3a7bd5;
            margin-bottom: 15px;
            transition: all 0.4s ease;
            display: inline-block;
        }
        .consult-type-label:hover .consult-type-icon {
            animation: float 2s ease-in-out infinite;
        }
        .consult-type-input:checked + .consult-type-label .consult-type-icon {
            transform: scale(1.2) rotate(360deg);
            color: #00d2ff;
        }
        .consult-type-label h6 {
            font-weight: 700;
            color: #495057;
            margin: 0;
            font-size: 1.1rem;
        }
        .consult-type-label .price-tag {
            display: inline-block;
            margin-top: 8px;
            padding: 5px 15px;
            background: linear-gradient(135deg, #00d2ff 0%, #3a7bd5 100%);
            color: white;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .doctor-option {
            white-space: normal;
            overflow-wrap: break-word;
        }
        .btn-light {
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-light:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="booking-card">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <div>
                                <h3 class="mb-0"><i class="bi bi-calendar-heart"></i> Book Your Consultation</h3>
                                <small style="opacity: 0.9;">Fill in your details to get started</small>
                            </div>
                            <a href="index.html" class="btn btn-light btn-sm">
                                <i class="bi bi-house-door-fill"></i> Home
                            </a>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar-fill" id="progressBar"></div>
                        </div>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <form action="payment.php" method="POST" id="bookingForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-wrapper">
                                        <label for="name" class="form-label">
                                            <i class="bi bi-person-fill"></i> Full Name
                                            <span class="required-star">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
                                        <i class="bi bi-check-circle-fill input-icon valid" id="nameIcon"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-wrapper">
                                        <label for="email" class="form-label">
                                            <i class="bi bi-envelope-fill"></i> Email Address
                                            <span class="required-star">*</span>
                                        </label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="your.email@example.com" required>
                                        <i class="bi bi-check-circle-fill input-icon valid" id="emailIcon"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-wrapper">
                                        <label for="phone" class="form-label">
                                            <i class="bi bi-telephone-fill"></i> Phone Number
                                            <span class="required-star">*</span>
                                        </label>
                                        <input type="tel" class="form-control" id="phone" name="phone" placeholder="+91 XXXXX XXXXX" required>
                                        <i class="bi bi-check-circle-fill input-icon valid" id="phoneIcon"></i>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-wrapper">
                                        <label for="doctor" class="form-label">
                                            <i class="bi bi-person-badge-fill"></i> Select Doctor
                                        </label>
                                        <?php
                                        $selected_doctor = isset($_GET['doctor']) ? $_GET['doctor'] : '';
                                        ?>
                                        <select id="doctor" name="doctor" class="form-select">
                                            <option value="">Choose your doctor</option>
                                            <option value="dr_gopan" class="doctor-option" <?php echo ($selected_doctor == 'dr_gopan') ? 'selected' : ''; ?>>Dr. Aarav Mehta - MBBS, MD (General Medicine)</option>
                                            <option value="dr_christy" class="doctor-option" <?php echo ($selected_doctor == 'dr_christy') ? 'selected' : ''; ?>>Dr. Nisha Reddy - MBBS, DNB (Emergency Medicine)</option>
                                            <option value="dr_reddy" class="doctor-option" <?php echo ($selected_doctor == 'dr_reddy') ? 'selected' : ''; ?>>Dr. Karan Bhattacharya - MBBS, MD (Internal Medicine)</option>
                                            <option value="dr_sura" class="doctor-option" <?php echo ($selected_doctor == 'dr_sura') ? 'selected' : ''; ?>>Dr. Rohan Pillai - MBBS, MD (Emergency Medicine)</option>
                                            <option value="dr_stephen" class="doctor-option" <?php echo ($selected_doctor == 'dr_stephen') ? 'selected' : ''; ?>>Dr. Sneha Iyer - MBBS, MD (General Medicine)</option>
                                            <option value="dr_sahadevan" class="doctor-option" <?php echo ($selected_doctor == 'dr_sahadevan') ? 'selected' : ''; ?>>Dr. Priya Sharma - MBBS, MD (General Medicine)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label d-block mb-3">
                                    <i class="bi bi-chat-square-heart-fill"></i> Consultation Type
                                    <span class="required-star">*</span>
                                </label>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="radio" name="type" id="type_chat" value="chat" class="consult-type-input" required>
                                        <label for="type_chat" class="consult-type-label">
                                            <div class="consult-type-icon"><i class="bi bi-chat-dots-fill"></i></div>
                                            <h6>Chat Consultation</h6>
                                            <span class="price-tag">₹100</span>
                                            <div class="tooltip-info" style="margin-top: 8px;">
                                                <i class="bi bi-info-circle" style="color: #3a7bd5;"></i>
                                                <span class="tooltip-text">Text-based consultation with doctor</span>
                                            </div>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="radio" name="type" id="type_video" value="video" class="consult-type-input" required>
                                        <label for="type_video" class="consult-type-label">
                                            <div class="consult-type-icon"><i class="bi bi-camera-video-fill"></i></div>
                                            <h6>Video Consultation</h6>
                                            <span class="price-tag">₹150</span>
                                            <div class="tooltip-info" style="margin-top: 8px;">
                                                <i class="bi bi-info-circle" style="color: #3a7bd5;"></i>
                                                <span class="tooltip-text">Face-to-face video call with doctor</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="input-wrapper">
                                <label for="notes" class="form-label">
                                    <i class="bi bi-journal-medical"></i> Medical Notes / Symptoms
                                </label>
                                <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Describe your symptoms or any specific concerns..." maxlength="500"></textarea>
                                <span class="char-counter" id="charCounter">0 / 500</span>
                            </div>

                            <div class="price-summary" id="priceSummary" style="display: none;">
                                <h5><i class="bi bi-receipt"></i> Price Summary</h5>
                                <div class="price-item">
                                    <span>Consultation Type:</span>
                                    <span id="consultType">-</span>
                                </div>
                                <div class="price-item">
                                    <span>Consultation Fee:</span>
                                    <span id="consultFee">₹0</span>
                                </div>
                                <div class="price-item">
                                    <span>Total Amount:</span>
                                    <span id="totalAmount">₹0</span>
                                </div>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="bi bi-credit-card-fill"></i> Proceed to Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form elements
        const form = document.getElementById('bookingForm');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        const notesInput = document.getElementById('notes');
        const chatRadio = document.getElementById('type_chat');
        const videoRadio = document.getElementById('type_video');
        const progressBar = document.getElementById('progressBar');
        const priceSummary = document.getElementById('priceSummary');
        const charCounter = document.getElementById('charCounter');
        
        // Icons
        const nameIcon = document.getElementById('nameIcon');
        const emailIcon = document.getElementById('emailIcon');
        const phoneIcon = document.getElementById('phoneIcon');

        // Validation functions
        function validateName(name) {
            return name.trim().length >= 3 && /^[a-zA-Z\s]+$/.test(name);
        }

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function validatePhone(phone) {
            const cleaned = phone.replace(/\D/g, '');
            return cleaned.length >= 10;
        }

        // Real-time validation with icons
        nameInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                if (validateName(this.value)) {
                    this.classList.remove('invalid');
                    this.classList.add('valid');
                    nameIcon.classList.remove('bi-x-circle-fill', 'invalid');
                    nameIcon.classList.add('bi-check-circle-fill', 'valid', 'show');
                } else {
                    this.classList.remove('valid');
                    this.classList.add('invalid');
                    nameIcon.classList.remove('bi-check-circle-fill', 'valid');
                    nameIcon.classList.add('bi-x-circle-fill', 'invalid', 'show');
                }
            } else {
                this.classList.remove('valid', 'invalid');
                nameIcon.classList.remove('show');
            }
            updateProgress();
        });

        emailInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                if (validateEmail(this.value)) {
                    this.classList.remove('invalid');
                    this.classList.add('valid');
                    emailIcon.classList.remove('bi-x-circle-fill', 'invalid');
                    emailIcon.classList.add('bi-check-circle-fill', 'valid', 'show');
                } else {
                    this.classList.remove('valid');
                    this.classList.add('invalid');
                    emailIcon.classList.remove('bi-check-circle-fill', 'valid');
                    emailIcon.classList.add('bi-x-circle-fill', 'invalid', 'show');
                }
            } else {
                this.classList.remove('valid', 'invalid');
                emailIcon.classList.remove('show');
            }
            updateProgress();
        });

        phoneInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                if (validatePhone(this.value)) {
                    this.classList.remove('invalid');
                    this.classList.add('valid');
                    phoneIcon.classList.remove('bi-x-circle-fill', 'invalid');
                    phoneIcon.classList.add('bi-check-circle-fill', 'valid', 'show');
                } else {
                    this.classList.remove('valid');
                    this.classList.add('invalid');
                    phoneIcon.classList.remove('bi-check-circle-fill', 'valid');
                    phoneIcon.classList.add('bi-x-circle-fill', 'invalid', 'show');
                }
            } else {
                this.classList.remove('valid', 'invalid');
                phoneIcon.classList.remove('show');
            }
            updateProgress();
        });

        // Character counter for notes
        notesInput.addEventListener('input', function() {
            const length = this.value.length;
            const maxLength = 500;
            charCounter.textContent = `${length} / ${maxLength}`;
            
            if (length > maxLength * 0.9) {
                charCounter.classList.add('danger');
                charCounter.classList.remove('warning');
            } else if (length > maxLength * 0.7) {
                charCounter.classList.add('warning');
                charCounter.classList.remove('danger');
            } else {
                charCounter.classList.remove('warning', 'danger');
            }
        });

        // Price calculator
        function updatePrice() {
            const chatSelected = chatRadio.checked;
            const videoSelected = videoRadio.checked;
            
            if (chatSelected || videoSelected) {
                priceSummary.style.display = 'block';
                
                const type = chatSelected ? 'Chat' : 'Video';
                const fee = chatSelected ? 100 : 150;
                
                document.getElementById('consultType').textContent = type + ' Consultation';
                document.getElementById('consultFee').textContent = '₹' + fee;
                document.getElementById('totalAmount').textContent = '₹' + fee;
            } else {
                priceSummary.style.display = 'none';
            }
            updateProgress();
        }

        chatRadio.addEventListener('change', updatePrice);
        videoRadio.addEventListener('change', updatePrice);

        // Progress bar calculation
        function updateProgress() {
            let progress = 0;
            const totalFields = 4; // name, email, phone, type
            
            if (validateName(nameInput.value)) progress += 25;
            if (validateEmail(emailInput.value)) progress += 25;
            if (validatePhone(phoneInput.value)) progress += 25;
            if (chatRadio.checked || videoRadio.checked) progress += 25;
            
            progressBar.style.width = progress + '%';
        }

        // Form submission with validation
        form.addEventListener('submit', function(e) {
            let isValid = true;
            let errorMessage = '';

            if (!validateName(nameInput.value)) {
                isValid = false;
                errorMessage += '• Please enter a valid name (at least 3 characters, letters only)\n';
                nameInput.classList.add('invalid');
            }

            if (!validateEmail(emailInput.value)) {
                isValid = false;
                errorMessage += '• Please enter a valid email address\n';
                emailInput.classList.add('invalid');
            }

            if (!validatePhone(phoneInput.value)) {
                isValid = false;
                errorMessage += '• Please enter a valid phone number (at least 10 digits)\n';
                phoneInput.classList.add('invalid');
            }

            if (!chatRadio.checked && !videoRadio.checked) {
                isValid = false;
                errorMessage += '• Please select a consultation type\n';
            }

            if (!isValid) {
                e.preventDefault();
                alert('Please fix the following errors:\n\n' + errorMessage);
            }
        });

        // Auto-format phone number
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) value = value.slice(0, 10);
            
            if (value.length >= 6) {
                e.target.value = value.slice(0, 5) + ' ' + value.slice(5);
            } else {
                e.target.value = value;
            }
        });

        // Smooth scroll on form focus
        const inputs = document.querySelectorAll('.form-control, .form-select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });

        // Initialize
        updateProgress();
        
        // Add floating animation to submit button when form is complete
        setInterval(() => {
            if (progressBar.style.width === '100%') {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.style.animation = 'float 2s ease-in-out infinite';
            }
        }, 100);
    </script>
</body>
</html>