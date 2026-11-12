<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | Court Reserve</title>
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
            padding: 50px 40px 30px 40px; 
            border-radius: 20px; 
            width: 100%; 
            max-width: 380px; 
            border: 1.5px solid #2b308b; 
            box-sizing: border-box;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
            position: relative;
        }

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
            margin-top: 10px; 
            font-size: 28px; 
            font-weight: bold;
            margin-bottom: 15px; 
        }

        p.instruction {
            font-size: 14px;
            color: #555;
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
    </style>
</head>
<body>
    <div class="auth-form-container">
        <a href="{{ route('login') }}" class="back-button">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>

        <h2>Reset Password</h2>
        <p class="instruction">Please enter your new password below.</p>
        
        <form action="{{ route('forgot.reset.post') }}" method="POST">
            @csrf 

            @if($errors->any())
                <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: left;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="input-group">
                <input type="password" id="password" name="password" required placeholder="New Password">
                <label for="password">New Password</label>
            </div>
            <ul id="password-reqs" style="font-size: 11px; text-align: left; margin-bottom: 25px; margin-top: -10px; padding-left: 20px; color: #dc3545; display: none;">
                <li id="req-length">More than 8 characters</li>
                <li id="req-letter">At least one letter</li>
                <li id="req-number">At least one number</li>
                <li id="req-symbol">At least one symbol</li>
            </ul>

            <div class="input-group">
                <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Confirm New Password">
                <label for="password_confirmation">Confirm Password</label>
            </div>
            
            <button type="submit" class="btn-primary" id="updateBtn" disabled style="opacity: .6; cursor: not-allowed;">Update Password</button>
        </form>
    </div>

    <script>
        const pwInput = document.getElementById('password');
        const reqsList = document.getElementById('password-reqs');
        const updateBtn = document.getElementById('updateBtn');

        function checkPassword() {
            const password = pwInput.value;
            let valid = false;

            if (password.length > 0) {
                reqsList.style.display = 'block';

                const hasLength = password.length > 8;
                const hasLetter = /[a-zA-Z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSymbol = /[^a-zA-Z0-9]/.test(password);

                document.getElementById('req-length').style.color = hasLength ? '#28a745' : '#dc3545';
                document.getElementById('req-letter').style.color = hasLetter ? '#28a745' : '#dc3545';
                document.getElementById('req-number').style.color = hasNumber ? '#28a745' : '#dc3545';
                document.getElementById('req-symbol').style.color = hasSymbol ? '#28a745' : '#dc3545';

                valid = hasLength && hasLetter && hasNumber && hasSymbol;
            } else {
                reqsList.style.display = 'none';
            }

            updateBtn.disabled = !valid;
            updateBtn.style.opacity = valid ? '1' : '.6';
            updateBtn.style.cursor = valid ? 'pointer' : 'not-allowed';
        }

        pwInput.addEventListener('input', checkPassword);
        checkPassword();
    </script>
@include('partials.password-toggle')
</body>
</html>
