<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Verification | Batangas Badminton</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Base Dashboard Variables & Styling */
        :root { 
            --primary-blue: #1557c0;
            --dark-blue: #002277;
            --bg-color: #f4f6f9;
            --card-bg: #ffffff;
            --text-main: #333333;
            --text-muted: #777777;
            --success-bg: #dcedc8;
            --success-text: #2e7d32;
        }
        
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); display: flex; height: 100vh; overflow: hidden; }

        /* Main Content Container matching Dashboard */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 30px; }
        
        /* Header */
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .top-header h1 { margin: 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; }
        .header-right { display: flex; align-items: center; gap: 20px; color: var(--dark-blue); font-weight: 500; font-size: 14px; }
        .header-right i { font-size: 20px; cursor: pointer; }

        /* --- QR VERIFICATION SPECIFIC CSS --- */
        .qr-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            align-items: start;
        }

        /* Left Column: Scanner */
        .status-container { display: flex; justify-content: center; margin-bottom: 15px; }
        .status-badge {
            background-color: #f0fdf4;
            color: #22c55e;
            padding: 8px 30px;
            border-radius: 50px;
            text-align: center;
            font-weight: 600;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-badge i { font-size: 22px; }
        
        .scan-box {
            background: #fff;
            border: 2px solid #3b82f6;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin-bottom: 20px;
            height: 380px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .scan-box h4 { color: #1e3a8a; margin-top: 0; margin-bottom: 20px; font-size: 16px; font-weight: 600; }
        .qr-placeholder { max-width: 250px; width: 100%; }
        
        .manual-entry {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
        }
        .manual-entry h4 { color: #1e3a8a; margin-top: 0; margin-bottom: 15px; font-size: 14px; font-weight: 600; }
        .input-group { display: flex; gap: 10px; }
        .input-group input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            color: #333;
        }
        .input-group input:focus { outline: 1px solid #0033ff; color: #333; }
        .btn-go {
            background: #0033ff;
            color: #fff;
            border: none;
            padding: 0 30px;
            border-radius: 6px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
        }

        /* Right Column: Details */
        .details-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
        }
        .details-header {
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        .details-header h3 {
            color: #0033ff;
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }
        .details-body { padding: 30px; }
        
        .user-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }
        .user-profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .user-profile h4 { margin: 0; font-size: 16px; color: #1e3a8a; font-weight: 600; }
        
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .details-table td { padding: 10px 0; font-size: 13px; border: none; }
        .details-table td:first-child { color: #9ca3af; width: 35%; }
        .details-table td:last-child { color: #1e3a8a; font-weight: 500; }
        
        .verify-container { text-align: center; }
        .btn-verify {
            background: #0033ff;
            color: #fff;
            border: none;
            padding: 12px 60px;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            display: inline-block;
        }
        .btn-verify:hover { background: #0022cc; }
    </style>
</head>
<body>

    <!-- 1. Pull in your beautiful custom Sidebar -->
    @include('cashier.sidebar')

    <!-- 2. Main Content perfectly matching the Dashboard format -->
    <main class="main-content">
        
        <!-- Top Header matches dashboard -->
        <header class="top-header">
            <h1>QR Verification</h1>
            <div class="header-right">
                <span>{{ now()->format('l, F j, Y') }}</span>
                <i class="fa-regular fa-bell"></i>
            </div>
        </header>

        <div class="qr-grid">
            <!-- LEFT COLUMN -->
            <div class="left-col">
                <div class="status-container">
                    @if(session('reservation'))
                        <div class="status-badge">
                            <i class="fa-solid fa-circle-check"></i> Valid Reservation
                        </div>
                    @elseif(session('error'))
                        <div class="status-badge" style="background-color: #fce4e4; color: #cc0000;">
                            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
                        </div>
                    @endif
                </div>

                <div class="scan-box">
                    <h4>Scan Qr Code</h4>
                    <img src="{{ asset('images/qr-placeholder.png') }}" alt="QR Code" class="qr-placeholder" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg'">
                </div>

                <div class="manual-entry">
                    <h4>Manual Entry</h4>
                    <form action="{{ url('/cashier/qr-verification/search') }}" method="POST" id="qrSearchForm">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="qr_code" id="qrInput" placeholder="Enter the code" required autofocus>
                            <button type="submit" class="btn-go">GO</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="right-col">
                <div class="details-card">
                    <div class="details-header">
                        <h3>Reservation Found</h3>
                    </div>
                    
                    <div class="details-body">
                        @php 
                            $res = session('reservation'); 
                        @endphp

                        <div class="user-profile">
                            <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar" onerror="this.src='https://ui-avatars.com/api/?name={{ $res ? urlencode($res->customer_name) : (Auth::user() ? urlencode(Auth::user()->name) : 'Cashier') }}&background=60a5fa&color=fff'">
                            <h4>{{ $res ? $res->customer_name : (Auth::user() ? Auth::user()->name : 'Not Logged In') }}</h4>
                        </div>

                        <table class="details-table">
                            <tr>
                                <td>Reservation ID</td>
                                <td>{{ $res ? $res->reservation_id : 'BC26-02' }}</td>
                            </tr>
                            <tr>
                                <td>Sport</td>
                                <td>Badminton</td>
                            </tr>
                            <tr>
                                <td>Court</td>
                                <td>{{ $res ? 'Court ' . $res->court_number : 'Court 1' }}</td>
                            </tr>
                            <tr>
                                <td>Date & Time</td>
                                <td>{{ $res ? $res->date . ', ' . $res->time_slot : 'Mon, June 1, 2026, 4:00 - 5:00 PM' }}</td>
                            </tr>
                            <tr>
                                <td>Rent Item</td>
                                <td>{{ $res ? $res->rented_items : '1 Racket, 1 Shuttlecock' }}</td>
                            </tr>
                            <tr>
                                <td>Duration</td>
                                <td>{{ $res ? $res->duration . ' Hour' : '1 Hour' }}</td>
                            </tr>
                            <tr>
                                <td>Payment</td>
                                <td>Paid <i class="fa-regular fa-image" style="margin-left: 5px; color: #6b7280;"></i></td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td style="color: #22c55e;">{{ $res ? $res->status : 'Confirmed' }}</td>
                            </tr>
                        </table>

                        <form action="{{ url('/cashier/qr-verification/verify/' . ($res ? $res->id : '1')) }}" method="POST" class="verify-container">
                            @csrf
                            <button type="submit" class="btn-verify" {{ !$res ? 'disabled style="opacity:0.5; cursor:not-allowed;"' : '' }}>
                                Verify
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Hardware Scanner Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrInput = document.getElementById('qrInput');
            
            document.body.addEventListener('click', function(e) {
                if (e.target.tagName !== 'BUTTON' && e.target.tagName !== 'A' && e.target.tagName !== 'INPUT') {
                    qrInput.focus();
                }
            });

            qrInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); 
                    document.getElementById('qrSearchForm').submit();
                }
            });
        });
    </script>
</body>
</html>