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

        /* Mirror the camera feed so movement feels natural like a mirror */
        #reader video {
            transform: scaleX(-1) !important;
        }
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
                @include('partials.notif-bell')
            </div>
        </header>

        <div class="qr-grid">
            <!-- LEFT COLUMN -->
            <div class="left-col">
                <div class="status-container">
                    @if(session('reservation'))
                        @php
                            $isPassed = \Carbon\Carbon::parse(session('reservation')->end_time)->isPast();
                        @endphp
                        @if($isPassed)
                            <div class="status-badge" style="background-color: #fee2e2; color: #dc2626;">
                                <i class="fa-solid fa-triangle-exclamation"></i> Passed Reservation
                            </div>
                        @else
                            <div class="status-badge">
                                <i class="fa-solid fa-circle-check"></i> Valid Reservation
                            </div>
                        @endif
                    @elseif(session('error'))
                        <div class="status-badge" style="background-color: #fce4e4; color: #cc0000;">
                            <i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}
                        </div>
                    @endif
                </div>

                <div class="scan-box">
                    <h4>Scan Qr Code</h4>
                    <div id="reader" style="width: 100%; max-width: 300px;"></div>
                    <button id="start-camera-btn" type="button" class="btn-go" style="margin-top: 15px; padding: 10px 20px; font-size: 14px; background: #2563eb;">Use Phone Camera</button>
                    <!-- <img src="{{ asset('images/qr-placeholder.png') }}" alt="QR Code" class="qr-placeholder" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg'"> -->
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

                        @if($res)
                            <div class="user-profile">
                                @php
                                    $customerName = $res->user ? $res->user->name : ($res->walk_in_name ?: 'Walk-In Customer');
                                @endphp
                                <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($customerName) }}&background=60a5fa&color=fff'">
                                <h4>{{ $customerName }}</h4>
                            </div>

                            <table class="details-table">
                                <tr>
                                    <td>Reservation ID</td>
                                    <td>{{ $res->reservation_code }}</td>
                                </tr>
                                <tr>
                                    <td>Sport</td>
                                    <td>Badminton</td>
                                </tr>
                                <tr>
                                    <td>Court</td>
                                    <td>Court {{ $res->court_id }}</td>
                                </tr>
                                <tr>
                                    <td>Date & Time</td>
                                    <td>{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y') . ', ' . \Carbon\Carbon::parse($res->start_time)->format('g:i A') . ' - ' . \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}</td>
                                </tr>
                                <tr>
                                    <td>Rent Item</td>
                                    <td>
                                        @if($res->rentalItems && $res->rentalItems->count() > 0)
                                            @foreach($res->rentalItems as $item)
                                                {{ $item->quantity }}x {{ $item->item_name }}<br>
                                            @endforeach
                                        @else
                                            None
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Duration</td>
                                    <td>{{ \Carbon\Carbon::parse($res->start_time)->diffInHours(\Carbon\Carbon::parse($res->end_time)) }} Hour(s)</td>
                                </tr>
                                <tr>
                                    <td>Payment Type</td>
                                    <td>
                                        <span style="background: #e0f2fe; color: #0284c7; padding: 3px 8px; border-radius: 4px; font-weight: bold; font-size: 12px;">
                                            {{ $res->payment_type ? ucfirst($res->payment_type) : 'Unknown' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Payment Details</td>
                                    <td>
                                        <div style="display: flex; flex-direction: column; gap: 4px;">
                                            @php 
                                                $actual_paid = $res->amount_paid;
                                                if ($actual_paid == 0) {
                                                    if (strtolower($res->payment_type) == 'full') $actual_paid = $res->total_price;
                                                    elseif (strtolower($res->payment_type) == 'half') $actual_paid = $res->total_price / 2;
                                                }
                                                $balance = max(0, $res->total_price - $actual_paid); 
                                            @endphp
                                            <span>Total: <b>₱{{ number_format($res->total_price, 2) }}</b></span>
                                            <span style="color: #16a34a;">Paid: <b>₱{{ number_format($actual_paid, 2) }}</b></span>
                                            @if($balance > 0)
                                                <span style="color: #dc2626;">Balance: <b>₱{{ number_format($balance, 2) }}</b></span>
                                            @else
                                                <span style="color: #16a34a;"><i class="fa-solid fa-check-circle"></i> Fully Paid</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Receipt</td>
                                    <td>
                                        @if($res->receipt_path)
                                            <a href="javascript:void(0);" onclick="document.getElementById('receiptModal').style.display='flex'" style="display: inline-flex; align-items: center; gap: 5px; color: #1557c0; text-decoration: none; font-weight: bold; cursor: pointer;">
                                                <i class="fa-regular fa-image"></i> View Receipt
                                            </a>
                                        @else
                                            <span style="color: #9ca3af;">No receipt uploaded</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td style="color: #22c55e;">{{ ucfirst($res->status) }}</td>
                                </tr>
                            </table>

                            @php
                                $isPassed = \Carbon\Carbon::parse($res->end_time)->isPast();
                            @endphp

                            @if($isPassed)
                                <div style="background-color: #fee2e2; color: #dc2626; padding: 12px; border-radius: 6px; text-align: center; margin-bottom: 20px; font-weight: bold; font-size: 14px; border: 1px solid #f87171;">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Warning: This reservation has already passed.
                                </div>
                            @endif

                            <form action="{{ url('/cashier/qr-verification/verify/' . $res->id) }}" method="POST" class="verify-container">
                                @csrf
                                <button type="submit" class="btn-verify" {!! $isPassed ? 'disabled style="background: #e5e7eb; color: #9ca3af; cursor: not-allowed; box-shadow: none;"' : '' !!}>
                                    Verify
                                </button>
                            </form>
                        @else
                            <div style="text-align: center; color: var(--text-muted); padding: 50px 0;">
                                <i class="fa-solid fa-qrcode" style="font-size: 40px; color: #ccc; margin-bottom: 15px;"></i>
                                <p style="margin: 0;">No reservation scanned yet.<br>Scan a QR code or enter a code manually to see details here.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </main>

    @if(session('reservation') && session('reservation')->receipt_path)
    <!-- RECEIPT MODAL -->
    <div id="receiptModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
        <div style="background: #fff; padding: 20px; border-radius: 12px; max-width: 500px; width: 90%; text-align: center; position: relative;">
            <button onclick="document.getElementById('receiptModal').style.display='none'" style="position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 24px; cursor: pointer; color: #666;">&times;</button>
            <h3 style="margin-top: 0; margin-bottom: 20px; color: #1e3a8a;">Payment Receipt</h3>
            <img src="{{ asset('storage/' . session('reservation')->receipt_path) }}" alt="Receipt" style="max-width: 100%; max-height: 70vh; border-radius: 8px; border: 1px solid #e5e7eb;">
        </div>
    </div>
    @endif

    <!-- Hardware Scanner Script -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrInput = document.getElementById('qrInput');
            
            document.body.addEventListener('click', function(e) {
                if (e.target.tagName !== 'BUTTON' && e.target.tagName !== 'A' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'VIDEO') {
                    qrInput.focus();
                }
            });

            qrInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); 
                    document.getElementById('qrSearchForm').submit();
                }
            });

            // Camera Scanner logic
            const startCameraBtn = document.getElementById('start-camera-btn');
            const html5QrCode = new Html5Qrcode("reader");

            startCameraBtn.addEventListener('click', function() {
                startCameraBtn.style.display = 'none';
                
                Html5Qrcode.getCameras().then(devices => {
                    if (devices && devices.length) {
                        // Use the last camera (often the back camera on mobile) or the only camera available
                        let cameraId = devices.length > 1 ? devices[devices.length - 1].id : devices[0].id;
                        // Dynamically size the scan box to 70% of the camera feed (great for laptops)
                        let config = { 
                            fps: 15, 
                            qrbox: function(viewfinderWidth, viewfinderHeight) {
                                let minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                                let qrboxSize = Math.floor(minEdge * 0.70);
                                return { width: qrboxSize, height: qrboxSize };
                            }
                        };
                        
                        html5QrCode.start(
                            cameraId, 
                            config,
                            (decodedText, decodedResult) => {
                                // Once we get a scan, fill the input and submit
                                html5QrCode.stop().catch(err => console.log(err));
                                qrInput.value = decodedText;
                                document.getElementById('qrSearchForm').submit();
                            },
                            (errorMessage) => {
                                // ignore background scanning errors
                            })
                        .catch((err) => {
                            console.log("Error starting scanner", err);
                            alert("Could not start camera. Please ensure camera permissions are granted in your browser.");
                            startCameraBtn.style.display = 'inline-block';
                        });
                    } else {
                        alert("No cameras found on this device.");
                        startCameraBtn.style.display = 'inline-block';
                    }
                }).catch(err => {
                    console.log("Error getting cameras", err);
                    alert("Error accessing cameras. Please ensure camera permissions are granted.");
                    startCameraBtn.style.display = 'inline-block';
                });
            });
        });
    </script>
@include('partials.notif-script')
</body>
</html>
