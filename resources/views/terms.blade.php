<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms & Conditions | Batangas Badminton</title>
    <style>
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f4f6f9; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0; 
            padding: 20px; 
        }
        .terms-container { 
            max-width: 900px; 
            background: transparent; 
        }
        h1 { 
            color: #002277; 
            font-size: 36px; 
            margin-top: 0; 
            margin-bottom: 10px; 
        }
        .subtitle { 
            color: #555; 
            margin-bottom: 25px; 
            line-height: 1.5; 
            font-size: 15px;
        }
        .terms-box { 
            border: 2px solid #1557c0; 
            border-radius: 8px; 
            padding: 25px 30px; 
            background: #fff; 
            margin-bottom: 20px; 
        }
        .terms-list { 
            padding-left: 20px; 
            margin: 0; 
            color: #444; 
            line-height: 1.6; 
            font-size: 15px;
        }
        .terms-list li { margin-bottom: 12px; }
        .terms-list strong { color: #002277; font-weight: 600; }
        
        .btn-container { text-align: right; }
        .btn-agree { 
            display: inline-block; 
            background-color: #0044ff; 
            color: white; 
            padding: 12px 24px; 
            text-decoration: none; 
            border-radius: 6px; 
            font-weight: 500; 
            font-size: 16px; 
            transition: 0.2s; 
            border: none; 
            cursor: pointer; 
        }
        .btn-agree:hover { background-color: #0033cc; }
    </style>
</head>
<body>

    <div class="terms-container">
        <h1>Terms & Conditions</h1>
        <p class="subtitle">Welcome to Court Reserve, the online reservation system of Batangas Badminton Center and Fitness Gym. By accessing and using this website, you agree to comply with the following Terms and Conditions.</p>
        
        <div class="terms-box">
            <ul class="terms-list">
                <li><strong>User Accounts</strong> Users are responsible for maintaining the confidentiality of their account credentials and for all activities conducted under their account.</li>
                <li><strong>Reservations</strong> Court reservations are processed on a first-come, first-served basis. Reservations are subject to court availability and are confirmed only after the required payment has been completed.</li>
                <li><strong>Payments</strong> A 50% down payment is required to secure a reservation. Failure to complete the required payment may result in the cancellation of the reservation request.</li>
                <li><strong>Cancellation and Refund Policy</strong> Users may request cancellation or rescheduling through the system. Refunds of the down payment will only be granted for cancellations made at least five (5) hours before the scheduled reservation time. Cancellations made less than five (5) hours before the reservation schedule are non-refundable. Refunds may take 24 hours working time.</li>
                <li><strong>User Conduct</strong> Users agree not to misuse the system, provide false information, attempt unauthorized access, or engage in activities that may disrupt the website's operations.</li>
                <li><strong>Limitation of Liability</strong> Batangas Badminton Center and Fitness Gym shall not be held liable for reservation issues, delays, or service interruptions caused by internet connectivity problems, technical failures, power outages, or other circumstances beyond its control.</li>
                <li><strong>Privacy</strong> Personal information collected through the website shall be used solely for reservation management, communication, payment processing, and service improvement. User information will be handled in accordance with applicable data privacy laws.</li>
                <li><strong>Changes to Terms and Conditions</strong> Batangas Badminton Center and Fitness Gym reserves the right to modify these Terms and Conditions at any time. Continued use of the website after such changes constitutes acceptance of the updated terms.</li>
            </ul>
        </div>

        <div class="btn-container">
            <!-- Clicking this drops them straight into the main system! -->
            <a href="{{ url('/home') }}" class="btn-agree">I Agree & Continue</a>
        </div>
    </div>

</body>
</html>