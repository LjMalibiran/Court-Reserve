<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reservation Reminder</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; margin: 0; padding: 40px 0; color: #1f2937; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .header { background-color: #1e3a8a; padding: 30px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; letter-spacing: 0.5px; }
        .content { padding: 40px; }
        .greeting { font-size: 18px; font-weight: 600; color: #111827; margin-top: 0; }
        .text { font-size: 16px; line-height: 1.6; color: #4b5563; }
        .details-box { background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 25px; margin: 30px 0; }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table td { padding: 6px 0; font-size: 16px; vertical-align: top; }
        .detail-label { font-weight: 600; color: #3b82f6; width: 70px; }
        .detail-value { color: #1f2937; font-weight: 500; }
        .footer { background-color: #f9fafb; padding: 25px; text-align: center; font-size: 14px; color: #6b7280; border-top: 1px solid #f3f4f6; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Batangas Badminton Center</h1>
        </div>
        
        <div class="content">
            <p class="greeting">Hello {{ $userName }},</p>
            
            <p class="text">This is a friendly automated reminder for your upcoming {{ $sport }} reservation. It is scheduled to start in exactly <strong>1 hour</strong>.</p>
            
            <div class="details-box">
                <table class="details-table">
                    <tr>
                        <td class="detail-label">Date:</td>
                        <td class="detail-value">{{ $date }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Time:</td>
                        <td class="detail-value">{{ $time }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Court:</td>
                        <td class="detail-value">Court {{ $court }}</td>
                    </tr>
                </table>
            </div>
            
            <p class="text">Please arrive a few minutes early to prepare. If you need any assistance, approach our front desk.</p>
            <p class="text" style="margin-bottom: 0;">We look forward to seeing you!</p>
        </div>
        
        <div class="footer">
            &copy; {{ date('Y') }} Batangas Badminton Center. All rights reserved.
        </div>
    </div>
</body>
</html>
