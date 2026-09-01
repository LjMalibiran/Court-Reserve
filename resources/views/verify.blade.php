<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Account | Court Reserve</title>
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
            padding: 50px 40px; 
            border-radius: 20px; 
            width: 100%; 
            max-width: 420px; 
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
            margin-bottom: 25px; 
        }

        p.instruction {
            color: #6c757d;
            font-size: 15px;
            line-height: 1.5;
            margin-bottom: 35px;
            padding: 0 10px;
            text-align: left;
        }

        /* OTP Input Boxes */
        .otp-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
        }

        .otp-input {
            width: 55px;
            height: 65px;
            border: 1.5px solid #0044ff;
            border-radius: 8px;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            color: #333;
            background: transparent;
        }

        .otp-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 68, 255, 0.2);
        }

        /* Confirm Button */
        .btn-primary { 
            background: #0044ff; 
            color: white; 
            border: none; 
            padding: 15px; 
            border-radius: 12px; 
            width: 80%; 
            font-size: 18px; 
            font-weight: 500; 
            cursor: pointer; 
            transition: 0.2s;
        }
        .btn-primary:hover { background-color: #0033cc; }

    </style>
</head>
<body>
    
    <div class="auth-form-container">
        <h2>Verify your Account</h2>
        
        <p class="instruction">
            We have sent the verification code to your Gmail address.
        </p>
        
        <form action="{{ route('verify.post') }}" method="POST" id="verifyForm">
            @csrf
            
            <div class="otp-container">
                <input type="text" class="otp-input" name="code[]" maxlength="1" required autocomplete="off">
                <input type="text" class="otp-input" name="code[]" maxlength="1" required autocomplete="off">
                <input type="text" class="otp-input" name="code[]" maxlength="1" required autocomplete="off">
                <input type="text" class="otp-input" name="code[]" maxlength="1" required autocomplete="off">
            </div>
            
            <button type="submit" class="btn-primary">Confirm</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.otp-input');

            inputs.forEach((input, index) => {
                // Auto-advance to the next input when a number is typed
                input.addEventListener('input', function(e) {
                    // Only allow numbers
                    this.value = this.value.replace(/[^0-9]/g, '');
                    
                    if (this.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                // Move to the previous input if they hit Backspace on an empty box
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value === '' && index > 0) {
                        inputs[index - 1].focus();
                    }
                });
            });
            
            // Focus the very first input automatically when the page loads
            if(inputs.length > 0) {
                inputs[0].focus();
            }
        });
    </script>
</body>
</html>