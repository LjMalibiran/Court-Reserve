<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login | Batangas Badminton</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Full Screen Background */
        /* Full Screen Background */
        .hero-background {
            /* Kept a very slight gradient overlay so the white login card remains readable */
            background-image: linear-gradient(rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.2)), url('{{ asset('images/admin-bg.jpg') }}');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Center White Card */
        .login-card {
            background-color: white;
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            width: 90%;
            max-width: 380px;
            text-align: center;
        }

        .logo {
            max-width: 200px;
            margin-bottom: 40px;
        }

        /* Buttons */
        .btn {
            display: block;
            width: 100%;
            padding: 14px;
            margin-bottom: 12px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            color: white;
            cursor: pointer;
            text-decoration: none;
            box-sizing: border-box;
            transition: opacity 0.2s;
        }

        .btn-admin {
            background-color: #0d47a1; /* Darker blue */
        }

        .btn-cashier {
            background-color: #1976d2; /* Slightly lighter blue */
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* Tiny Text */
        .terms-text {
            font-size: 10px;
            color: #777;
            margin-top: 10px;
            line-height: 1.4;
        }

        .terms-text a {
            color: #0d47a1;
            text-decoration: none;
        }

        .footer-text {
            margin-top: 60px;
            font-size: 13px;
            color: #555;
        }
        
        .footer-text a {
            color: #0d47a1;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="hero-background">
        <div class="login-card">
            
            <img src="{{ asset('images/logo.png') }}" alt="Batangas Badminton Logo" class="logo">

            <a href="{{ route('admin.login') }}" class="btn btn-admin">Log in as Admin</a>
            <a href="{{ url('/cashier/login') }}" class="btn btn-cashier">Log in as Cashier</a>

            <p class="terms-text">
                By signing up, you agree to Batangas Badminton's<br>
                <a href="#">Term of Use</a> and <a href="#">Privacy Policy</a>
            </p>

            <div class="footer-text">
                Already have an account ? <a href="#">Log In</a>
            </div>

        </div>
    </div>

</body>
</html>