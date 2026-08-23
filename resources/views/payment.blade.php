<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment | Court Reserve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #0033cc;
            --light-blue: #e6edff;
            --text-dark: #333;
            --text-gray: #777;
            --bg-gray: #f8f9fa;
        }

        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: var(--bg-gray); display: flex; height: 100vh; overflow: hidden; }

        /* Sidebar */
        .sidebar { width: 250px; background-color: white; border-right: 1px solid #ddd; display: flex; flex-direction: column; }
        .logo-container { padding: 20px; text-align: center; border-bottom: 1px solid #ddd; }
        .nav-menu { list-style: none; padding: 0; margin: 20px 0; flex-grow: 1; }
        .nav-menu li { margin-bottom: 5px; }
        .nav-menu a { display: flex; align-items: center; padding: 15px 30px; color: var(--primary-blue); text-decoration: none; font-size: 16px; font-weight: 500; transition: 0.2s; }
        .nav-menu a i { margin-right: 15px; font-size: 20px; width: 20px; text-align: center; }
        .nav-menu a:hover, .nav-menu a.active { background-color: var(--light-blue); border-left: 4px solid var(--primary-blue); }

        /* Main Content */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 0 40px 40px 40px; box-sizing: border-box; }
        .top-header { padding: 20px 0; display: flex; justify-content: space-between; align-items: center; }
        .top-header h1 { color: var(--primary-blue); margin: 0; font-size: 32px; }

        /* Payment Layout Grid */
        .payment-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 25px; margin-top: 10px; }
        
        .panel { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eaeaea; }
        
        /* Left Panel: Details */
        .sport-title { display: flex; align-items: center; gap: 15px; color: var(--primary-blue); font-size: 20px; font-weight: bold; margin-bottom: 20px; }
        .amount-row { display: flex; justify-content: space-between; font-weight: bold; font-size: 16px; color: var(--primary-blue); margin-bottom: 20px; }
        .gcash-details { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #eee; padding-top: 20px; }
        .gcash-info h3 { margin: 0; color: var(--primary-blue); font-size: 18px; }
        .gcash-info p { margin: 5px 0 0 0; color: var(--text-gray); font-size: 18px; }
        
        /* Right Panel: Options */
        .panel h3 { margin-top: 0; color: var(--primary-blue); font-size: 18px; margin-bottom: 5px; }
        .panel p.sub { color: var(--text-gray); font-size: 13px; margin-top: 0; margin-bottom: 20px; }
        
        .radio-option { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .radio-label { display: flex; align-items: center; cursor: pointer; color: var(--primary-blue); font-weight: 600; font-size: 16px; }
        .radio-label input { appearance: none; width: 20px; height: 20px; border: 2px solid var(--primary-blue); border-radius: 50%; margin-right: 12px; outline: none; position: relative; cursor: pointer; }
        .radio-label input:checked::after { content: ''; position: absolute; top: 3px; left: 3px; width: 10px; height: 10px; background: var(--primary-blue); border-radius: 50%; }
        .price-text { color: var(--primary-blue); font-weight: 600; font-size: 16px; }
        
        /* Upload Area */
        .upload-area { border: 2px dashed #ccc; border-radius: 12px; padding: 40px; text-align: center; background: #fafafa; cursor: pointer; transition: 0.2s; }
        .upload-area:hover { border-color: var(--primary-blue); background: var(--light-blue); }
        .upload-icon { font-size: 40px; color: var(--primary-blue); margin-bottom: 15px; }
        .upload-text { color: var(--primary-blue); font-size: 16px; font-weight: 600; margin-bottom: 10px; }
        .btn-outline { border: 1px solid var(--primary-blue); color: var(--primary-blue); background: white; padding: 10px 25px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px; margin-top: 10px; }
        
        .btn-submit { background: var(--primary-blue); color: white; border: none; padding: 16px; border-radius: 8px; width: 100%; font-size: 18px; font-weight: bold; cursor: pointer; margin-top: 25px; }
        .btn-submit:hover { background: #002299; }

        /* Modal Styles */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: white; padding: 40px; border-radius: 20px; width: 90%; max-width: 450px; text-align: center; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .modal-close { position: absolute; top: 15px; right: 20px; font-size: 24px; color: #555; cursor: pointer; border: none; background: none; }
        .success-circle { background: #28a745; color: white; width: 70px; height: 70px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 35px; margin: 0 auto 20px auto; }
        .modal-title { color: var(--primary-blue); margin: 0 0 10px 0; font-size: 24px; }
        .modal-text { color: var(--text-gray); font-size: 14px; line-height: 1.5; margin-bottom: 25px; }
        .qr-box { border: 1px solid #0033cc; border-radius: 12px; padding: 20px; display: inline-block; margin-bottom: 15px; }
        .btn-download { border: 1px solid var(--primary-blue); color: var(--primary-blue); background: white; padding: 12px 30px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 15px; width: 100%; }
    
        /* Mobile App Navigation Override */
        @media (max-width: 768px) {
            body { 
                flex-direction: column; 
            }
            
            /* Transforms sidebar into a bottom navbar */
            .sidebar {
                position: fixed; 
                bottom: 0; 
                left: 0; 
                width: 100%; 
                height: 70px;
                flex-direction: row; 
                border-right: none; 
                border-top: 1px solid #ddd;
                z-index: 1000; 
                padding: 0;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
            }
            
            /* Hide the big logo on mobile */
            .logo-container { 
                display: none; 
            }
            
            /* Arrange the icons horizontally */
            .nav-menu { 
                display: flex; 
                flex-direction: row; 
                margin: 0; 
                width: 100%; 
                justify-content: space-around; 
                align-items: center; 
            }
            
            .nav-menu a { 
                padding: 10px; 
                flex-direction: column; /* Stacks icon above text */
                font-size: 11px; 
                border-left: none; 
                color: #777;
            }
            
            .nav-menu a i { 
                margin-right: 0; 
                margin-bottom: 4px; 
                font-size: 20px; 
            }
            
            /* Mobile active state (underline instead of left border) */
            .nav-menu a:hover, .nav-menu a.active { 
                border-left: none; 
                background: transparent; 
                color: var(--primary-blue); 
            }

            /* Push main content up so it isn't hidden behind the new bottom bar */
            .main-content { 
                padding: 20px;
                padding-bottom: 90px; 
            }
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-width: 180px; height: auto;">
        </div>
        <ul class="nav-menu">
            <li><a href="{{ url('/home') }}"><i class="fa-solid fa-house"></i> Home</a></li>
            <li><a href="{{ route('reservation.index') }}" class="active"><i class="fa-regular fa-calendar-plus"></i> Reservation</a></li>
            <li><a href="{{ route('history.index') }}"><i class="fa-solid fa-clock-rotate-left"></i> History</a></li>
            <li><a href="{{ route('profile.index') }}"><i class="fa-regular fa-user"></i> Profile</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <h1>Payment</h1>
            <i class="fa-regular fa-bell" style="font-size: 24px; color: var(--primary-blue);"></i>
        </header>

        <!-- 1. ADDED THE FORM WRAPPER -->
        <form action="{{ url('/reserve/process-payment') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- Hidden inputs to carry the reservation data from the previous page -->
            <input type="hidden" name="court_id" value="1"> <!-- Replace with dynamic data -->
            <input type="hidden" name="start_time" value="2026-06-01 16:00:00">
            <input type="hidden" name="total_amount" value="250.00">

            <div class="payment-grid">
                
                <div class="panel">
                    <div class="sport-title">
                        <img src="{{ asset('images/shuttlecock.png') }}" width="35" alt="Badminton">
                        Badminton
                    </div>
                    
                    <div style="border: 1px solid #eee; border-radius: 12px; padding: 20px;">
                        <div class="amount-row">
                            <span>Total Amount</span>
                            <span>₱ 250.00</span>
                        </div>
                        
                        <div class="gcash-details">
                            <div class="gcash-info">
                                <span style="color: #999; font-size: 12px;">Payment Method</span>
                                <div style="display: flex; align-items: center; gap: 10px; margin-top: 5px;">
                                    <div style="background: #007bff; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; font-size: 20px;">G</div>
                                    <div>
                                        <h3>Gcash</h3>
                                        <p>09123456789</p>
                                    </div>
                                </div>
                            </div>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=GcashPayment" alt="GCash QR" style="border-radius: 8px;">
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <h3>Payment Option</h3>
                    <p class="sub">50% Down Payment Required to Confirm Reservation</p>
                    
                    <div class="radio-option">
                        <label class="radio-label">
                            <input type="radio" name="payment_type" value="full" checked>
                            Full Payment
                        </label>
                        <span class="price-text">₱ 250.00</span>
                    </div>
                    
                    <div class="radio-option" style="align-items: flex-start;">
                        <label class="radio-label">
                            <input type="radio" name="payment_type" value="half">
                            <div style="display: flex; flex-direction: column;">
                                50% Down Payment
                                <span style="color: var(--text-gray); font-size: 12px; font-weight: normal; margin-top: 3px;">Please pay the remaining balance<br>before your playing time.</span>
                            </div>
                        </label>
                        <span class="price-text">₱ 125.00</span>
                    </div>
                </div>
            </div>

            <div class="panel">
                <h3>Upload Receipt <span style="color: var(--text-gray); font-size: 13px; font-weight: normal;">(Required)</span></h3>
                <p class="sub">Please upload the Gcash receipt</p>
                
                <!-- 2. UPGRADED TO ACTUALLY UPLOAD A FILE -->
                <div class="upload-area" onclick="document.getElementById('receipt-upload').click()">
                    <i class="fa-solid fa-cloud-arrow-up upload-icon"></i>
                    <div class="upload-text" id="file-name-display">Drag and drop your file here</div>
                    <div style="color: var(--text-gray); font-size: 14px; margin-bottom: 10px;">or</div>
                    <button type="button" class="btn-outline">Choose File</button>
                    
                    <!-- The actual hidden file input -->
                    <input type="file" id="receipt-upload" name="receipt" accept="image/png, image/jpeg" style="display: none;" required onchange="document.getElementById('file-name-display').innerText = this.files[0].name">
                </div>
                <div style="text-align: center; color: #999; font-size: 12px; margin-top: 15px;">Accepted file: JPG, PNG (Max.5MB)</div>
            </div>

            <!-- 3. CHANGED TO A REAL SUBMIT BUTTON -->
            <button type="submit" class="btn-submit">Complete Payment</button>

        </form>
    </main>

    <div id="successModal" class="modal-overlay">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">&times;</button>
            
            <div class="success-circle">
                <i class="fa-solid fa-check"></i>
            </div>
            
            <h2 class="modal-title">Reservation Confirmed!</h2>
            <p class="modal-text">
                Your reservation for<br>
                <strong style="color: var(--primary-blue);">Badminton Court 1</strong><br>
                on <strong style="color: var(--primary-blue);">June 1, 2026</strong> at <strong style="color: var(--primary-blue);">4:00 PM</strong><br>
                has been confirmed
            </p>
            
            <div style="color: var(--text-gray); font-size: 15px; margin-bottom: 10px;">
                Reservation ID: <strong style="color: var(--primary-blue);">BC26-01</strong>
            </div>
            
            <div class="qr-box">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=BC26-01" alt="Reservation QR">
            </div>
            
            <p style="color: #999; font-size: 11px; margin-top: 0; margin-bottom: 20px;">Please arrive 3-5 minutes before your schedule time.</p>
            
            <button class="btn-download">Download QR</button>
        </div>
    </div>

    <script>
        function showModal() {
            document.getElementById('successModal').style.display = 'flex';
        }
        function closeModal() {
            document.getElementById('successModal').style.display = 'none';
            // Redirect them back to history or dashboard after closing
            window.location.href = "{{ route('history.index') }}"; 
        }

        // Automatically show modal if Laravel flashes a 'success' message
        @if(session('success'))
            window.onload = function() {
                showModal();
            };
        @endif
    </script>
</body>
</html>