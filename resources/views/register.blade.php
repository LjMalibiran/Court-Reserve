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

        /* The Main Card */
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
        }

        h2 { 
            color: #0b2057; 
            margin-top: 0; 
            font-size: 32px; 
            font-weight: bold;
            margin-bottom: 30px; 
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

        /* Custom Circular Checkbox for Terms */
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

        /* Sign Up Button */
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
            margin: 25px 0 20px; 
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
            margin-bottom: 30px; 
        }
        .social-logins svg { 
            width: 24px; 
            height: 24px; 
            cursor: pointer; 
            transition: 0.2s; 
        }
        .social-logins svg:hover { transform: scale(1.1); }

        /* Footer */
        .auth-footer { 
            font-size: 11px; 
            color: #777; 
        }
        .auth-footer a { 
            color: #0044ff; 
            text-decoration: none; 
        }
    </style>
</head>
<body>
    
    <div class="auth-form-container">
        <h2>Create Account</h2>
        
        @if($errors->any())
            <div class="alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            
            <div class="input-group">
                <input type="text" name="name" id="name" required>
                <label for="name">Name</label>
            </div>
            
            <div class="input-group">
                <input type="text" name="contact" id="contact" required>
                <label for="contact">Contact Number</label>
            </div>
            
            <div class="input-group">
                <input type="password" name="password" id="password" required>
                <label for="password">Password</label>
            </div>
            
            <label class="custom-checkbox">
                <input type="checkbox" id="terms" name="terms" checked required>
                <span>I agree to the <a href="#">Terms and Conditions</a></span>
            </label>
            
            <button type="submit" class="btn-primary">Sign Up</button>
        </form>

        <div class="divider">
            <span>Or Continue with</span>
        </div>

        <div class="social-logins">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z" fill="#1877F2"/>
            </svg>
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.15 2.95.97 3.67 2.14-3.17 1.94-2.58 6.07.6 7.4-1 2.32-2 4.52-2.92 5.47zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.02 4.41-3.74 4.25z" fill="#000000"/>
            </svg>
        </div>

        <div class="auth-footer">
            <p>Already have an account? <a href="/login">Sign In</a></p>
        </div>
    </div>

</body>
</html>