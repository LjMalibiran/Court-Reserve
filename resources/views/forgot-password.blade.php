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

        <h2>Forgot Password</h2>
        <p class="instruction">Enter your Google account (Email) to receive instructions for resetting your password.</p>
        
        <form action="#" method="POST" onsubmit="alert('Instructions have been sent to your Google account.'); return false;">
            @csrf 
            <div class="input-group">
                <input type="email" id="email" name="email" required placeholder="Enter your Google account email">
                <label for="email">Google Account</label>
            </div>
            
            <button type="submit" class="btn-primary">Send Instructions</button>
        </form>
    </div>
</body>
</html>
