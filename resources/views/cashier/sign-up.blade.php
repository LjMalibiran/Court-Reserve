<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Sign Up | Batangas Badminton</title>
    <style>
        :root {
            /* Matching the bright blue from your mockup */
            --primary-blue: #0a44f2; 
            --text-main: #333333;
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
        /* Left Side: Hero Image */
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
            height: 35px; /* Adjust based on your logo asset size */
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
            line-height: 1.1;
            margin-bottom: 40px;
            text-transform: uppercase;
            letter-spacing: -1px;
        }

        /* Rounded Inputs */
        .input-group {
            margin-bottom: 22px;
        }

        .input-control {
            width: 100%;
            padding: 20px 30px;
            border: none;
            border-radius: 50px; /* Creates the pill shape */
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

        .input-control::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        /* Checkbox Area */
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 10px;
            margin-bottom: 40px;
            font-size: 14px;
            color: #4b5563;
        }

        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: var(--primary-blue);
            cursor: pointer;
            border-radius: 4px;
        }

        .checkbox-group a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .checkbox-group a:hover {
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 20px;
            border: none;
            border-radius: 50px; /* Pill shape matching inputs */
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

        /* Responsive behavior for smaller screens */
        @media (max-width: 900px) {
            body {
                flex-direction: column;
            }
            .hero-section {
                display: none; /* Hide image on mobile/small screens */
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

    <!-- Right Side: Registration Form -->
    <div class="form-section">
        
        <div class="logo-top-right">
            <img src="{{ asset('images/logo.png') }}" alt="Batangas Badminton">
        </div>

        <div class="form-container">
            <h1>Cashier's<br>Sign Up</h1>
            
            <form action="#" method="POST" onsubmit="event.preventDefault(); window.location.href='{{ url('/dashboard') }}';">
                <div class="input-group">
                    <input type="text" class="input-control" placeholder="Enter your name" required>
                </div>
                
                <div class="input-group">
                    <input type="email" class="input-control" placeholder="Email" required>
                </div>
                
                <div class="input-group">
                    <input type="password" class="input-control" placeholder="Password" required>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="terms" checked required>
                    <label for="terms">I agree to the <a href="#">Term and Conditions</a> and <a href="#">Privacy Policy</a></label>
                </div>
                
                <button type="submit" class="btn-submit">Create Account</button>
            </form>
        </div>
        
    </div>

</body>
</html>