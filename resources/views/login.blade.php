<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Started | Court Reserve</title>
    <style>
        body { 
            margin: 0; 
            height: 100vh; 
            display: flex; 
            justify-content: center; /* This perfectly centers items horizontally */
            align-items: center;     /* This perfectly centers items vertically */
            background-image: url("{{ asset('images/auth-bg.jpg') }}"); 
            background-size: cover; 
            background-position: left center; 
            background-repeat: no-repeat;
            background-color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }

        /* The Main Card */
        .auth-form-container { 
            background: white; 
            padding: 50px 40px 30px 40px; 
            border-radius: 20px; 
            width: 100%; 
            max-width: 380px; 
            /* REMOVED: margin-left: 20%; */
            border: 1.5px solid #2b308b; 
            box-sizing: border-box;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); /* Added a subtle shadow to make it pop off the background */
        }

        h2 { 
            color: #0b2057; 
            margin-top: 0; 
            font-size: 32px; 
            font-weight: bold;
            margin-bottom: 35px; 
        }

        /* Floating Label Input Fields */
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

        /* Checkbox & Forgot Password */
        .form-actions { 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
            font-size: 12px; 
            margin-bottom: 25px; 
        }
        
        .form-actions a { 
            color: #0044ff; 
            text-decoration: none; 
            font-weight: 500; 
        }

        /* Custom Circular Checkbox */
        .remember-me {
            display: flex;
            align-items: center;
            color: #0044ff;
            cursor: pointer;
        }
        .remember-me input {
            appearance: none;
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            border: 1.5px solid #0044ff;
            border-radius: 50%;
            margin-right: 8px;
            cursor: pointer;
            outline: none;
            position: relative;
        }
        .remember-me input:checked::after {
            content: '';
            position: absolute;
            top: 3px; left: 3px;
            width: 9px; height: 9px;
            background: #0044ff;
            border-radius: 50%;
        }

        /* Sign In Button */
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

        /* Error Messages */
        .alert-error { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; text-align: left; }

        /* Divider */
        .divider { 
            display: flex; 
            align-items: center; 
            text-align: center; 
            margin: 30px 0 20px; 
            color: #888; 
            font-size: 12px; 
        }
        .divider::before, .divider::after { 
            content: ''; 
            flex: 1; 
            border-bottom: 1px solid #ccc; 
        }
        .divider span { padding: 0 10px; }

        /* Social Icons */
        .social-logins { 
            display: flex; 
            justify-content: center; 
            gap: 20px; 
            margin-bottom: 35px; 
        }
        .social-logins svg { 
            width: 24px; 
            height: 24px; 
            cursor: pointer; 
            transition: 0.2s; 
        }
        .social-logins svg:hover { transform: scale(1.1); }

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

        /* Footer */
        .auth-footer { 
            font-size: 11px; 
            color: #777; 
            margin-top: 15px;
        }
        .auth-footer a { 
            color: #0044ff; 
            text-decoration: none; 
        }

        /* Disabled Button State */
        .btn-primary:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    
    <div class="auth-form-container" style="position: relative;">
        <!-- Back Button -->
        <a href="/" class="back-button">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>

        <h2>Get Started</h2>
        
        <form action="{{ route('login.post') }}" method="POST">
            @csrf 
            
            <div class="input-group">
                <input type="text" id="login_id" name="login_id" class="@error('login_id') is-invalid @enderror" required placeholder="Enter username or contact number" oninput="checkInputs()" value="{{ old('login_id') }}">
                <label for="login_id">Username or Contact Number</label>
                @error('login_id')
                    <span class="error-text" style="color: #dc3545; font-size: 11px; margin-top: 5px; display: block; font-weight: 500;">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="input-group">
                <input type="password" name="password" id="password" class="@error('password') is-invalid @enderror" required oninput="checkInputs()">
                <label for="password">Password</label>
                @error('password')
                    <span class="error-text" style="color: #dc3545; font-size: 11px; margin-top: 5px; display: block; font-weight: 500;">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-actions">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    Remember me
                </label>
                <a href="{{ route('forgot.password') }}">Forgot Password?</a>
            </div>
            
            <button type="submit" class="btn-primary" id="signInBtn" disabled>Sign In</button>
        </form>

        <div class="auth-footer">
            Don't have an account ? <a href="/register">Sign Up</a>
        </div>
    </div>

    <script>
        function checkInputs() {
            const loginId = document.getElementById('login_id').value;
            const password = document.getElementById('password').value;
            const signInBtn = document.getElementById('signInBtn');

            if (loginId.trim() !== '' && password.trim() !== '') {
                signInBtn.disabled = false;
            } else {
                signInBtn.disabled = true;
            }
        }
        
        // Check inputs on initial load as well in case of browser autofill
        window.onload = function() {
            checkInputs();
        };
    </script>
</body>
</html>