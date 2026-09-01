<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account | Court Reserve</title>
    <style>
        body { 
            margin: 0; 
            height: 100vh; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            background-image: url("{{ asset('images/auth-bg.jpg') }}"); 
            background-size: cover; 
            background-position: left center; 
            background-repeat: no-repeat;
            background-color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }

        .auth-form-container { 
            background: white; 
            padding: 40px 40px 30px 40px; 
            border-radius: 20px; 
            width: 100%; 
            max-width: 380px; 
            border: 1.5px solid #2b308b; 
            box-sizing: border-box;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Back Button */
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            text-decoration: none;
            color: #2b308b;
            font-weight: bold;
            font-size: 14px;
            display: flex;
            align-items: center;
        }
        .back-button svg {
            width: 16px;
            height: 16px;
            margin-right: 5px;
        }

        h2 { 
            color: #0b2057; 
            margin-top: 20px; 
            font-size: 32px; 
            font-weight: bold;
            margin-bottom: 25px; 
        }

        .input-group { 
            position: relative; 
            margin-bottom: 25px; 
            text-align: left;
        }
        
        .input-group input { 
            width: 100%; 
            padding: 14px 15px; 
            border: 1.5px solid #6c757d; 
            border-radius: 8px; 
            font-size: 15px; 
            box-sizing: border-box; 
            background: transparent;
            color: #333;
        }
        
        .input-group input:focus { 
            outline: none; 
            border-color: #0033cc; 
        }

        .input-group input.is-invalid {
            border-color: #dc3545 !important;
        }

        .input-group label { 
            position: absolute; 
            top: -10px; 
            left: 15px; 
            background: white; 
            padding: 0 5px; 
            font-size: 14px; 
            color: #2b308b; 
            font-weight: 500; 
        }

        .custom-checkbox {
            display: flex;
            align-items: center;
            color: #555;
            cursor: pointer;
            font-size: 12px;
            margin-bottom: 25px;
            text-align: left;
        }
        .custom-checkbox input {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border: 1.5px solid #0044ff;
            border-radius: 50%;
            margin-right: 10px;
            cursor: pointer;
            outline: none;
            position: relative;
            flex-shrink: 0;
        }
        .custom-checkbox input:checked::after {
            content: '';
            position: absolute;
            top: 3px; left: 3px;
            width: 9px; height: 9px;
            background: #0044ff;
            border-radius: 50%;
        }
        .custom-checkbox a {
            color: #0044ff;
            text-decoration: none;
            font-weight: bold;
        }
        .custom-checkbox a:hover {
            text-decoration: underline;
        }

        .btn-primary { 
            background: #0044ff; 
            color: white; 
            border: none; 
            padding: 15px; 
            border-radius: 8px; 
            width: 100%; 
            font-size: 18px; 
            font-weight: 500; 
            cursor: pointer; 
        }
        .btn-primary:hover { background-color: #0033cc; }
        .btn-primary:disabled { background-color: #cccccc; cursor: not-allowed; }

        .alert-error { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; text-align: left; }

        .auth-footer { 
            font-size: 11px; 
            color: #777; 
            margin-top: 15px;
        }
        .auth-footer a { 
            color: #0044ff; 
            text-decoration: none; 
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            text-align: left;
            position: relative;
        }
        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #333;
        }
        .modal-content h3 { margin-top: 0; color: #0b2057; }
        .modal-content p { font-size: 14px; color: #555; line-height: 1.5; }
    </style>
</head>
<body>
    
    <div class="auth-form-container">
        <!-- Back Button -->
        <a href="/" class="back-button">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>

        <h2>Create Account</h2>
        
        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            
            <div class="input-group" style="margin-bottom: 25px;">
                <input type="text" name="name" id="name" class="@error('name') is-invalid @enderror" required oninput="checkInputs()" value="{{ old('name') }}">
                <label for="name">Name</label>
                @error('name')
                    <span class="error-text" style="color: #dc3545; font-size: 11px; margin-top: 5px; display: block; font-weight: 500;">{{ $message }}</span>
                @enderror
            </div>

            <div class="input-group" style="margin-bottom: 25px;">
                <input type="email" name="email" id="email" class="@error('email') is-invalid @enderror" required oninput="checkInputs()" value="{{ old('email') }}" placeholder="example@gmail.com">
                <label for="email">Gmail Address</label>
                <span id="email-error" class="error-text" style="color: #dc3545; font-size: 11px; margin-top: 5px; display: none; font-weight: 500;"></span>
                @error('email')
                    <span class="error-text" style="color: #dc3545; font-size: 11px; margin-top: 5px; display: block; font-weight: 500;">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="input-group" style="margin-bottom: 25px;">
                <input type="text" name="contact" id="contact" maxlength="11" class="@error('contact') is-invalid @enderror" required oninput="this.value = this.value.replace(/[^0-9]/g, ''); checkInputs()" value="{{ old('contact') }}">
                <label for="contact">Contact Number</label>
                <span id="contact-error" class="error-text" style="color: #dc3545; font-size: 11px; margin-top: 5px; display: none; font-weight: 500;"></span>
                @error('contact')
                    <span class="error-text" style="color: #dc3545; font-size: 11px; margin-top: 5px; display: block; font-weight: 500;">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="input-group" style="margin-bottom: 5px;">
                <input type="password" name="password" id="password" class="@error('password') is-invalid @enderror" required oninput="checkInputs()">
                <label for="password">Password</label>
                @error('password')
                    <span class="error-text" style="color: #dc3545; font-size: 11px; margin-top: 5px; display: block; font-weight: 500;">{{ $message }}</span>
                @enderror
            </div>
            <ul id="password-reqs" style="font-size: 11px; text-align: left; margin-bottom: 25px; margin-top: 5px; padding-left: 20px; color: #dc3545; display: none;">
                <li id="req-length">More than 8 characters</li>
                <li id="req-letter">At least one letter</li>
                <li id="req-number">At least one number</li>
                <li id="req-symbol">At least one symbol</li>
            </ul>
            
            <label class="custom-checkbox">
                <input type="checkbox" id="terms" name="terms" required onchange="checkInputs()">
                <span>I agree to the <a href="javascript:void(0)" onclick="openModal()">Terms and Conditions</a></span>
            </label>
            
            <button type="submit" class="btn-primary" id="signUpBtn" disabled>Sign Up</button>
        </form>

        <div class="auth-footer">
            <p>Already have an account? <a href="/login">Sign In</a></p>
        </div>
    </div>

    <!-- Terms Modal -->
    <div id="termsModal" class="modal">
        <div class="modal-content" style="max-width: 600px;">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h3>Terms and Conditions</h3>
            
            <div style="max-height: 60vh; overflow-y: auto; margin-bottom: 20px; padding-right: 15px; font-size: 14px; color: #444; line-height: 1.6; text-align: justify;">
                <p>Welcome to Court Reserve, the online reservation system of Batangas Badminton Center and Fitness Gym. By accessing and using this website, you agree to comply with the following Terms and Conditions.</p>
                <ul style="padding-left: 20px; margin: 0; text-align: left;">
                    <li style="margin-bottom: 12px;"><strong style="color: #0b2057;">User Accounts:</strong> Users are responsible for maintaining the confidentiality of their account credentials and for all activities conducted under their account.</li>
                    <li style="margin-bottom: 12px;"><strong style="color: #0b2057;">Reservations:</strong> Court reservations are processed on a first-come, first-served basis. Reservations are subject to court availability and are confirmed only after the required payment has been completed.</li>
                    <li style="margin-bottom: 12px;"><strong style="color: #0b2057;">Payments:</strong> A 50% down payment is required to secure a reservation. Failure to complete the required payment may result in the cancellation of the reservation request.</li>
                    <li style="margin-bottom: 12px;"><strong style="color: #0b2057;">Cancellation and Refund Policy:</strong> Users may request cancellation or rescheduling through the system. Refunds of the down payment will only be granted for cancellations made at least five (5) hours before the scheduled reservation time. Cancellations made less than five (5) hours before the reservation schedule are non-refundable. Refunds may take 24 hours working time.</li>
                    <li style="margin-bottom: 12px;"><strong style="color: #0b2057;">User Conduct:</strong> Users agree not to misuse the system, provide false information, attempt unauthorized access, or engage in activities that may disrupt the website's operations.</li>
                    <li style="margin-bottom: 12px;"><strong style="color: #0b2057;">Limitation of Liability:</strong> Batangas Badminton Center and Fitness Gym shall not be held liable for reservation issues, delays, or service interruptions caused by internet connectivity problems, technical failures, power outages, or other circumstances beyond its control.</li>
                    <li style="margin-bottom: 12px;"><strong style="color: #0b2057;">Privacy:</strong> Personal information collected through the website shall be used solely for reservation management, communication, payment processing, and service improvement. User information will be handled in accordance with applicable data privacy laws.</li>
                    <li style="margin-bottom: 12px;"><strong style="color: #0b2057;">Changes to Terms and Conditions:</strong> Batangas Badminton Center and Fitness Gym reserves the right to modify these Terms and Conditions at any time. Continued use of the website after such changes constitutes acceptance of the updated terms.</li>
                </ul>
            </div>

            <button class="btn-primary" onclick="acceptTerms()">I Accept</button>
        </div>
    </div>

    <script>
        function checkInputs() {
            const name = document.getElementById('name').value;
            const emailInput = document.getElementById('email');
            const contactInput = document.getElementById('contact');
            const password = document.getElementById('password').value;
            const terms = document.getElementById('terms').checked;
            const signUpBtn = document.getElementById('signUpBtn');
            
            const email = emailInput.value;
            const contact = contactInput.value;

            // Email Validation
            let emailValid = false;
            const emailError = document.getElementById('email-error');
            if (email.length > 0) {
                if (!email.endsWith('@gmail.com')) {
                    emailError.textContent = "Must be a valid Gmail address (@gmail.com)";
                    emailError.style.display = "block";
                    emailInput.classList.add('is-invalid');
                } else {
                    emailError.style.display = "none";
                    emailInput.classList.remove('is-invalid');
                    emailValid = true;
                }
            } else {
                emailError.style.display = "none";
                emailInput.classList.remove('is-invalid');
            }

            // Contact Validation
            let contactValid = false;
            const contactError = document.getElementById('contact-error');
            if (contact.length > 0) {
                if (contact.length !== 11) {
                    contactError.textContent = "Contact number must be exactly 11 digits";
                    contactError.style.display = "block";
                    contactInput.classList.add('is-invalid');
                } else {
                    contactError.style.display = "none";
                    contactInput.classList.remove('is-invalid');
                    contactValid = true;
                }
            } else {
                contactError.style.display = "none";
                contactInput.classList.remove('is-invalid');
            }

            // Password Validation (Bulleted List)
            const reqsList = document.getElementById('password-reqs');
            let passwordValid = false;
            
            if (password.length > 0) {
                reqsList.style.display = "block";
                
                const hasLength = password.length > 8;
                const hasLetter = /[a-zA-Z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSymbol = /[^a-zA-Z0-9]/.test(password);

                document.getElementById('req-length').style.color = hasLength ? "#28a745" : "#dc3545";
                document.getElementById('req-letter').style.color = hasLetter ? "#28a745" : "#dc3545";
                document.getElementById('req-number').style.color = hasNumber ? "#28a745" : "#dc3545";
                document.getElementById('req-symbol').style.color = hasSymbol ? "#28a745" : "#dc3545";

                passwordValid = hasLength && hasLetter && hasNumber && hasSymbol;
            } else {
                reqsList.style.display = "none";
            }

            // Overall Button Check
            if (name.trim() !== '' && emailValid && contactValid && passwordValid && terms) {
                signUpBtn.disabled = false;
            } else {
                signUpBtn.disabled = true;
            }
        }
        
        window.onload = function() {
            checkInputs();
        };

        // Modal Logic
        const modal = document.getElementById("termsModal");
        
        function openModal() {
            modal.style.display = "flex";
        }
        
        function closeModal() {
            modal.style.display = "none";
        }

        function acceptTerms() {
            document.getElementById('terms').checked = true;
            closeModal();
            checkInputs();
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
