<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Batangas Badminton</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body, html { 
            margin: 0; 
            padding: 0; 
            height: 100%; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: white;
        }

        /* Split Layout Container */
        .split-layout {
            display: flex;
            height: 100vh;
            width: 100%;
        }

        /* --- Left Side: Image Panel --- */
        .left-panel {
            flex: 1.2; /* Takes up slightly more than half the screen */
            background-image: url('{{ asset('images/admin-bg.jpg') }}');
            background-size: cover;
            background-position: center left;
            position: relative;
            display: flex;
            align-items: center;
            padding-left: 5%;
        }

        /* The "WELCOME BACK" Overlay Text */
        .welcome-overlay {
            font-size: 5vw; /* Scales with screen size */
            font-weight: 900;
            font-style: italic;
            color: #002277; /* Dark navy blue */
            line-height: 0.9;
            text-transform: uppercase;
            /* Creates the thick white border effect around the letters */
            -webkit-text-stroke: 3px white;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.2);
            margin-top: 10%; /* Pushes it down below the logo in the photo */
        }

        /* --- Right Side: Form Panel --- */
        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 8%;
            position: relative;
            background: white;
        }

        /* Back Button & Logo Container */
        .top-nav {
            position: absolute;
            top: 30px;
            left: 0;
            width: 100%;
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-sizing: border-box;
        }

        .back-btn {
            color: #777;
            text-decoration: none;
            font-size: 14px;
            transition: 0.2s;
        }
        .back-btn:hover { color: #0033cc; }

        .logo { max-width: 180px; }

        /* Form Container */
        .form-container {
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
        }

        .form-container h1 {
            color: #0033cc; /* Bright blue */
            font-size: 45px;
            font-weight: 900;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .subtitle {
            color: #777;
            font-size: 14px;
            margin-bottom: 40px;
        }

        /* Input Fields */
        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            color: #555;
            font-size: 15px;
            margin-bottom: 8px;
            padding-left: 10px;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px;
            background-color: #f0f2f5; /* Light gray pill */
            border: 2px solid transparent;
            border-radius: 30px; /* Fully rounded edges */
            font-size: 15px;
            box-sizing: border-box;
            transition: 0.3s;
            color: #333;
        }

        .form-control:focus {
            outline: none;
            border-color: #0033cc;
            background-color: white;
        }

        /* Options Row (Remember Me & Forgot Pass) */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 0 10px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #555;
            font-size: 14px;
            cursor: pointer;
        }

        /* Custom circular checkbox */
        .remember-me input[type="checkbox"] {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #0033cc;
            border-radius: 50%;
            display: inline-block;
            position: relative;
            cursor: pointer;
        }
        .remember-me input[type="checkbox"]:checked {
            background-color: #0033cc;
        }

        .forgot-pass {
            color: #0033cc;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 16px;
            background-color: #0033ff; /* Bright vivid blue */
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
            box-shadow: 0 4px 15px rgba(0, 51, 255, 0.3);
        }

        .btn-submit:hover {
            background-color: #0022cc;
            transform: translateY(-2px);
        }

        /* Error Message Styling */
        .error-message {
            background-color: #fee2e2;
            color: #b91c1c;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #ef4444;
        }

        /* Mobile Layout Adjustments */
        @media (max-width: 900px) {
            .split-layout { flex-direction: column; }
            
            /* Hide the left image panel on mobile so the form takes priority */
            .left-panel { display: none; }
            
            .right-panel {
                padding: 20px;
                background-image: linear-gradient(rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.95)), url('{{ asset('images/admin-bg.jpg') }}');
                background-size: cover;
                background-position: center;
            }
            .top-nav { position: relative; top: 0; padding: 0 0 30px 0; }
            .form-container { background: white; padding: 30px 20px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        }
    </style>
</head>
<body>

    <div class="split-layout">
        
        <div class="left-panel">
            <div class="welcome-overlay">
                WELCOME BACK
            </div>
        </div>

        <div class="right-panel">
            
            <div class="top-nav">
                <a href="{{ route('staff.selection') }}" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Back</a>
                <img src="{{ asset('images/logo.png') }}" alt="Batangas Badminton Logo" class="logo">
            </div>

            <div class="form-container">
                <h1>LOG IN</h1>
                <p class="subtitle">Please log in to your account to continue.</p>

                <form action="{{ route('admin.login.submit') }}" method="POST">
                    @csrf
                    
                    @error('login_id')
                        <div class="error-message">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </div>
                    @enderror

                    <div class="form-group">
                        <label>Username or Email</label>
                        <input type="text" name="login_id" class="form-control" value="{{ old('login_id') }}" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            Remember me
                        </label>
                        <a href="#" class="forgot-pass">Forgot Password?</a>
                    </div>

                    <button type="submit" class="btn-submit">Continue</button>
                </form>
            </div>

        </div>
    </div>

</body>
</html>