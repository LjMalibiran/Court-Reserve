<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Log In | Batangas Badminton</title>
    <style>
        :root {
            --primary-blue: #0a44f2; 
            --text-main: #333333;
            --text-muted: #6b7280;
            --input-bg: #f0f2f5;
        }

        body { 
            margin: 0; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            display: flex; 
            height: 100vh; 
            overflow: hidden; 
            background-color: white;
        }

        /* Left Side: Hero Image */
        .hero-section {
            flex: 1;
            background-image: url('{{ asset("images/admin-bg.jpg") }}'); 
            background-size: cover;
            /* Anchors the image to the bottom left so the equipment stays visible */
            background-position: left bottom; 
            background-repeat: no-repeat;
            position: relative;
        }

        /* Right Side: Form Container */
        .form-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            position: relative;
            padding: 40px;
        }

        /* Small Logo Top Right */
        .logo-top-right {
            position: absolute;
            top: 40px;
            right: 50px;
        }
        
        .logo-top-right img {
            height: 35px;
        }

        /* Form Layout */
        .form-container {
            max-width: 480px;
            margin: auto;
            width: 100%;
            padding: 0 20px;
        }

        .form-container h1 {
            color: var(--primary-blue);
            font-size: 52px;
            font-weight: 800;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: -1px;
        }

        .subtitle {
            color: var(--text-muted);
            font-size: 15px;
            margin-bottom: 40px;
        }

        /* Inputs and Labels */
        .input-group {
            margin-bottom: 25px;
        }

        .input-label {
            display: block;
            font-size: 16px;
            color: #4b5563;
            margin-bottom: 10px;
            padding-left: 15px;
        }

        .input-control {
            width: 100%;
            padding: 20px 30px;
            border: none;
            border-radius: 50px; 
            background-color: var(--input-bg);
            font-size: 16px;
            color: var(--text-main);
            box-sizing: border-box;
            font-family: inherit;
            transition: 0.2s;
        }

        .input-control:focus {
            outline: 2px solid var(--primary-blue);
            background-color: white;
        }

        /* Options Row (Remember Me & Forgot Password) */
        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            padding: 0 10px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #1e3a8a; /* Darker blue to match image */
            font-size: 15px;
            cursor: pointer;
        }

        /* Customizing the checkbox to look like the circle in the image */
        .remember-me input[type="checkbox"] {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 1.5px solid #1e3a8a;
            border-radius: 50%;
            cursor: pointer;
            position: relative;
            outline: none;
        }

        .remember-me input[type="checkbox"]:checked::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 10px;
            height: 10px;
            background-color: #1e3a8a;
            border-radius: 50%;
        }

        .forgot-password {
            color: #1e3a8a;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }

        /* Buttons */
        .btn-submit {
            width: 100%;
            padding: 20px;
            border: none;
            border-radius: 50px; 
            background-color: var(--primary-blue);
            color: white;
            font-size: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
        }

        .btn-submit:hover {
            background-color: #0033cc;
        }

        .btn-submit:active {
            transform: scale(0.98);
        }

        /* Responsive behavior */
        @media (max-width: 900px) {
            body {
                flex-direction: column;
            }
            .hero-section {
                display: none; 
            }
            .logo-top-right {
                position: static;
                text-align: right;
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>

    <!-- Left Side: Court Image -->
    <div class="hero-section"></div>

    <!-- Right Side: Login Form -->
    <div class="form-section">
        
        <div class="logo-top-right">
            <img src="{{ asset('images/logo.png') }}" alt="Batangas Badminton">
        </div>

        <div class="form-container">
            <h1>LOG IN</h1>
            <div class="subtitle">Please log in your account to continue.</div>
            
            <!-- Display Auth Errors if they type the wrong credentials -->
            @if($errors->any())
                <div style="color: #dc2626; background: #fee2e2; padding: 10px 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; border: 1px solid #f87171;">
                    {{ $errors->first() }}
                </div>
            @endif
            
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="input-group">
                    <label class="input-label">Username</label>
                    <input type="text" name="login_id" class="input-control" placeholder="Enter your username" required autofocus>
                </div>
                
                <div class="input-group">
                    <label class="input-label">Password</label>
                    <input type="password" name="password" class="input-control" placeholder="••••••••" required>
                </div>
                
                <div class="options-row">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
                
                <button type="submit" class="btn-submit">Continue</button>
            </form>

        </div>
        
    </div>

</body>
</html>