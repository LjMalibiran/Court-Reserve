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
        
        @if($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                {{ $errors->first() }}
            </div>
        @endif
        <!-- Session Success Modal Handled Globally -->

        <p class="instruction">
            We have sent the verification code to your email address.
        </p>

        <form action="{{ route('verify.post') }}" method="POST" id="verifyForm">
            @csrf
            
            <div class="otp-container">
                <input type="text" class="otp-input" name="code[]" maxlength="1" required autocomplete="off">
                <input type="text" class="otp-input" name="code[]" maxlength="1" required autocomplete="off">
                <input type="text" class="otp-input" name="code[]" maxlength="1" required autocomplete="off">
                <input type="text" class="otp-input" name="code[]" maxlength="1" required autocomplete="off">
            </div>
            
            <button type="submit" class="btn-primary" id="confirmBtn" disabled style="background-color: #cccccc; cursor: not-allowed;">Confirm</button>
        </form>

        <p id="countdownDisplay" style="color: #dc3545; font-size: 13px; font-weight: normal; margin-top: 15px; margin-bottom: 5px;">
            Code expires in: 3:00
        </p>

        <div style="margin-top: 15px;">
            <form action="{{ route('verify.resend') }}" method="POST" id="resendForm" style="display: none;">
                @csrf
                <button type="submit" style="background: none; border: none; color: #0044ff; text-decoration: underline; cursor: pointer; font-size: 13px;">
                    Resend new code
                </button>
            </form>
        </div>
    </div>

    @php
        $expiresAt = auth()->user()->verification_code_expires_at;
        $secondsLeft = $expiresAt ? \Carbon\Carbon::now()->diffInSeconds($expiresAt, false) : 0;
        if ($secondsLeft < 0) $secondsLeft = 0;
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.otp-input');

            // Countdown Timer Logic
            let timeLeft = {{ $secondsLeft }};
            const timerDisplay = document.getElementById('countdownDisplay');
            const resendForm = document.getElementById('resendForm');
            const confirmBtn = document.getElementById('confirmBtn');
            
            function checkInputs() {
                let allFilled = true;
                inputs.forEach(input => {
                    if (input.value === '') allFilled = false;
                });
                
                if (allFilled && timeLeft > 0) {
                    confirmBtn.disabled = false;
                    confirmBtn.style.backgroundColor = '#0044ff';
                    confirmBtn.style.cursor = 'pointer';
                } else {
                    confirmBtn.disabled = true;
                    confirmBtn.style.backgroundColor = '#cccccc';
                    confirmBtn.style.cursor = 'not-allowed';
                }
            }

            inputs.forEach((input, index) => {
                // Auto-advance to the next input when a number is typed
                input.addEventListener('input', function(e) {
                    // Only allow numbers
                    this.value = this.value.replace(/[^0-9]/g, '');
                    
                    if (this.value.length === 1 && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                    checkInputs();
                });

                // Move to the previous input if they hit Backspace on an empty box
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value === '' && index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].value = '';
                    }
                    setTimeout(checkInputs, 10);
                });
            });
            
            function updateTimer() {
                if (timeLeft <= 0) {
                    timerDisplay.innerHTML = "Code expired!";
                    timerDisplay.style.color = "#721c24";
                    resendForm.style.display = 'block';
                    checkInputs();
                } else {
                    let minutes = Math.floor(timeLeft / 60);
                    let seconds = Math.floor(timeLeft % 60);
                    timerDisplay.innerHTML = `Code expires in: ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                    timeLeft -= 1;
                }
            }

            // Run once immediately, then every second
            updateTimer();
            if (timeLeft > 0) {
                setInterval(updateTimer, 1000);
            }
            
            // Focus first input and run initial check
            if (inputs.length > 0) inputs[0].focus();
            checkInputs();
        });
    </script>

<!-- SweetAlert2 Library for Global Modals -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Success!',
            text: "{!! addslashes(session('success')) !!}",
            icon: 'success',
            confirmButtonColor: '#1557c0'
        });
    });
</script>
@endif

@if(session('error') || $errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Error!',
            text: "{!! addslashes(session('error') ?? $errors->first()) !!}",
            icon: 'error',
            confirmButtonColor: '#dc2626'
        });
    });
</script>
@endif

</body>
</html>