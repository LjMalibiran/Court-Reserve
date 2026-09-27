<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help & Support | Batangas Badminton</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --primary-blue: #1557c0;
            --dark-blue: #002277;
            --bg-color: #f4f6f9;
            --card-bg: #ffffff;
            --text-main: #333333;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }
        
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); display: flex; height: 100vh; overflow: hidden; }
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 30px; }
        
        /* Header */
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .top-header h1 { margin: 0; font-size: 28px; color: var(--dark-blue); font-weight: 700; }
        
        .help-container { max-width: 900px; margin: 0 auto; width: 100%; }

        /* Guide Cards */
        .guides-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 40px; }
        .guide-card { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid var(--border-color); display: flex; gap: 20px; }
        .guide-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
        .guide-icon.blue { background: #eff6ff; color: #3b82f6; }
        .guide-icon.green { background: #f0fdf4; color: #22c55e; }
        .guide-icon.purple { background: #faf5ff; color: #a855f7; }
        .guide-icon.orange { background: #fff7ed; color: #f97316; }
        .guide-content h3 { margin: 0 0 8px 0; font-size: 16px; color: var(--text-main); }
        .guide-content p { margin: 0; font-size: 13px; color: var(--text-muted); line-height: 1.5; }
        
        /* FAQ Accordion */
        .faq-section { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid var(--border-color); margin-bottom: 40px; }
        .faq-section h2 { margin: 0 0 20px 0; font-size: 20px; color: var(--dark-blue); }
        
        .accordion-item { border-bottom: 1px solid var(--border-color); }
        .accordion-item:last-child { border-bottom: none; }
        .accordion-header { width: 100%; text-align: left; background: none; border: none; padding: 18px 0; font-size: 15px; font-weight: 600; color: var(--text-main); cursor: pointer; display: flex; justify-content: space-between; align-items: center; }
        .accordion-header:hover { color: var(--primary-blue); }
        .accordion-icon { transition: transform 0.3s; color: var(--text-muted); }
        .accordion-item.active .accordion-icon { transform: rotate(180deg); }
        .accordion-content { max-height: 0; overflow: hidden; transition: max-height 0.3s ease-out; }
        .accordion-inner { padding: 0 0 20px 0; font-size: 14px; color: var(--text-muted); line-height: 1.6; }

        /* Support Box */
        .support-box { background: linear-gradient(135deg, var(--dark-blue), var(--primary-blue)); border-radius: 12px; padding: 30px; color: white; display: flex; align-items: center; justify-content: space-between; }
        .support-info h3 { margin: 0 0 10px 0; font-size: 20px; }
        .support-info p { margin: 0; opacity: 0.9; font-size: 14px; }
        .support-btn { background: white; color: var(--primary-blue); border: none; padding: 12px 25px; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-block; transition: 0.2s; }
        .support-btn:hover { background: #f8fafc; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
    </style>
</head>
<body>

    @include('admin.sidebar')

    <main class="main-content">
        <header class="top-header">
            <h1>Help & Support</h1>
        </header>

        <div class="help-container">
            
            <!-- System Guides -->
            <div class="guides-grid">
                <div class="guide-card">
                    <div class="guide-icon blue"><i class="fa-solid fa-qrcode"></i></div>
                    <div class="guide-content">
                        <h3>QR Verification</h3>
                        <p>Scan a user's reservation QR code at the front desk. The system will automatically pull up their booking details for you to confirm attendance.</p>
                    </div>
                </div>
                
                <div class="guide-card">
                    <div class="guide-icon green"><i class="fa-solid fa-shoe-prints"></i></div>
                    <div class="guide-content">
                        <h3>Walk-In Bookings</h3>
                        <p>Use the Walk-In tab for guests without an account. You can manually assign them a court and process their payment on the spot.</p>
                    </div>
                </div>

                <div class="guide-card">
                    <div class="guide-icon purple"><i class="fa-solid fa-calendar-xmark"></i></div>
                    <div class="guide-content">
                        <h3>Blocking Dates</h3>
                        <p>In Settings, you can block off dates or specific hours for tournaments. Users won't be able to book online during these blocked times.</p>
                    </div>
                </div>

                <div class="guide-card">
                    <div class="guide-icon orange"><i class="fa-solid fa-money-bill-transfer"></i></div>
                    <div class="guide-content">
                        <h3>Processing Refunds</h3>
                        <p>If a user cancels a GCash booking, it will appear in the Refunds tab. Review the details and mark it as 'Refunded' once you return the money.</p>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="faq-section">
                <h2>Frequently Asked Questions</h2>
                
                <div class="accordion">
                    <div class="accordion-item">
                        <button class="accordion-header">
                            Why are certain time slots missing on the reservation page?
                            <i class="fa-solid fa-chevron-down accordion-icon"></i>
                        </button>
                        <div class="accordion-content">
                            <div class="accordion-inner">
                                If a time slot is missing or unclickable, it means it is either outside of the system's Operating Hours, or an Admin has set a Blocked Date for that specific time range in the Settings tab.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <button class="accordion-header">
                            How do I add a new Cashier account?
                            <i class="fa-solid fa-chevron-down accordion-icon"></i>
                        </button>
                        <div class="accordion-content">
                            <div class="accordion-inner">
                                Go to the "Manage Staff" tab in the sidebar. Click the "Add Staff" button at the top right, fill out the cashier's details, and assign them the Cashier role. They can then log in using those credentials.
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <button class="accordion-header">
                            How do I resend a reminder to a user?
                            <i class="fa-solid fa-chevron-down accordion-icon"></i>
                        </button>
                        <div class="accordion-content">
                            <div class="accordion-inner">
                                Go to the Reservations tab and locate the booking. Click the bell icon button next to the reservation. This will trigger both an SMS and an Email reminder to the user regarding their upcoming schedule.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script>
        // Accordion Logic
        document.querySelectorAll('.accordion-header').forEach(button => {
            button.addEventListener('click', () => {
                const item = button.closest('.accordion-item');
                const content = item.querySelector('.accordion-content');
                const isActive = item.classList.contains('active');

                // Close all other accordions
                document.querySelectorAll('.accordion-item').forEach(otherItem => {
                    otherItem.classList.remove('active');
                    otherItem.querySelector('.accordion-content').style.maxHeight = null;
                });

                // Toggle current accordion
                if (!isActive) {
                    item.classList.add('active');
                    content.style.maxHeight = content.scrollHeight + 'px';
                }
            });
        });
    </script>
</body>
</html>