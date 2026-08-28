@extends('layouts.app')

@section('title', 'Payment | Court Reserve')
@section('header_title', 'Payment')

@section('styles')
<style>
    /* Payment Layout Grid */
    .payment-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px; margin-top: 10px; max-width: 1000px; }
    
    .panel { background: white; border-radius: 12px; padding: 30px; border: 1px solid #eaeaea; position: relative; }
    
    /* Left Panel: Details */
    .sport-title { display: flex; align-items: center; gap: 15px; color: var(--primary-blue); font-size: 22px; font-weight: 700; margin-bottom: 25px; }
    .amount-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .amount-label { color: var(--primary-blue); font-size: 14px; font-weight: 600; }
    .amount-value { color: var(--primary-blue); font-size: 18px; font-weight: bold; }
    
    .gcash-details { display: flex; justify-content: space-between; align-items: center; border-top: 2px solid #f3f4f6; padding-top: 25px; }
    .gcash-info h3 { margin: 0; color: var(--primary-blue); font-size: 18px; font-weight: 700; }
    .gcash-info p { margin: 5px 0 0 0; color: #6b7280; font-size: 16px; }
    
    /* Right Panel: Options */
    .panel h3 { margin-top: 0; color: var(--primary-blue); font-size: 18px; margin-bottom: 8px; }
    .panel p.sub { color: #9ca3af; font-size: 13px; margin-top: 0; margin-bottom: 30px; }
    
    .radio-option { display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px; }
    .radio-label { display: flex; align-items: center; cursor: pointer; color: var(--primary-blue); font-weight: 600; font-size: 16px; }
    .radio-label input { appearance: none; width: 22px; height: 22px; border: 2px solid var(--primary-blue); border-radius: 50%; margin-right: 15px; outline: none; position: relative; cursor: pointer; }
    .radio-label input:checked::after { content: ''; position: absolute; top: 4px; left: 4px; width: 10px; height: 10px; background: var(--primary-blue); border-radius: 50%; }
    .price-text { color: var(--primary-blue); font-weight: 600; font-size: 16px; }
    
    /* Upload Area */
    .upload-container { max-width: 1000px; }
    .upload-area { border: 2px dashed #d1d5db; border-radius: 12px; padding: 50px 20px; text-align: center; background: white; cursor: pointer; transition: 0.2s; display: block; position: relative; }
    .upload-area:hover { border-color: var(--primary-blue); background: #f8fafc; }
    .upload-icon { font-size: 50px; color: var(--primary-blue); margin-bottom: 20px; }
    .upload-text { color: var(--primary-blue); font-size: 16px; font-weight: 600; margin-bottom: 15px; }
    .upload-or { color: #9ca3af; font-size: 14px; margin-bottom: 15px; font-weight: normal; }
    .btn-outline { border: 1px solid var(--primary-blue); color: var(--primary-blue); background: white; padding: 12px 35px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 15px; transition: 0.2s; }
    .btn-outline:hover { background: var(--light-blue); }
    .upload-hint { text-align: center; color: #9ca3af; font-size: 12px; margin-top: 15px; }
    
    .btn-submit { background: var(--primary-blue); color: white; border: none; padding: 16px; border-radius: 8px; width: 100%; max-width: 1000px; font-size: 18px; font-weight: bold; cursor: pointer; margin-top: 30px; transition: 0.2s; }
    .btn-submit:hover { background: #002299; }

    /* Success Receipt Modal specific to Payment page */
    .success-modal .modal-content { text-align: center; overflow: hidden; padding: 40px 30px 30px; border: 1px solid #eaeaea; }
    .success-circle { background: #28a745; color: white; width: 80px; height: 80px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 40px; margin: 0 auto 20px auto; border: 5px solid #d4edda; }
    .modal-title { color: #222; margin: 0 0 10px 0; font-size: 22px; font-weight: 700; }
    .modal-text { color: var(--text-gray); font-size: 14px; line-height: 1.6; margin-bottom: 20px; }
    .modal-text strong { color: var(--primary-blue); }
    .reservation-id { color: var(--primary-blue); font-size: 16px; font-weight: 600; margin-bottom: 15px; }
    .qr-box { border: 2px solid var(--primary-blue); border-radius: 12px; padding: 15px; display: inline-block; margin-bottom: 10px; }
    .qr-hint { color: var(--primary-blue); font-size: 11px; margin-bottom: 20px; }
    .btn-download { border: 2px solid var(--primary-blue); color: var(--primary-blue); background: white; padding: 12px 30px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 15px; width: 100%; box-sizing: border-box; transition: 0.2s; }
    .btn-download:hover { background: var(--primary-blue); color: white; }
    /* Confetti dots */
    .confetti { position: absolute; width: 8px; height: 8px; border-radius: 50%; }
    .confetti-1 { top: 15px; left: 20px; background: #f39c12; }
    .confetti-2 { top: 30px; right: 40px; background: #e74c3c; }
    .confetti-3 { top: 10px; right: 80px; background: #3498db; }
    .confetti-4 { top: 50px; left: 50px; background: #2ecc71; }
    .confetti-5 { top: 25px; left: 100px; background: #9b59b6; }
    .confetti-6 { top: 45px; right: 25px; background: #f1c40f; }
    .confetti-7 { top: 8px; left: 60%; background: #e67e22; }
    .confetti-8 { top: 55px; left: 30%; background: #1abc9c; }

    @media (max-width: 768px) {
        .payment-grid { grid-template-columns: 1fr; }
        .gcash-details { flex-direction: column; align-items: flex-start; gap: 20px; }
        .gcash-details img { align-self: center; margin-top: 10px; }
    }
</style>
@endsection

@section('content')
<form action="{{ url('/reserve/process-payment') }}" method="POST" enctype="multipart/form-data" id="paymentForm" onsubmit="return validatePayment(event)">
    @csrf
    
    <input type="hidden" name="court_id" value="{{ session('court_id') }}">
    <input type="hidden" name="sport" value="{{ session('sport') }}">
    <input type="hidden" name="start_time" value="{{ session('start_time') }}">
    <input type="hidden" name="end_time" value="{{ session('end_time') }}">
    <input type="hidden" name="total_amount" value="{{ session('total_price') }}">

    <div class="payment-grid">
        <div class="panel" style="background: transparent; border: none; padding: 0;">
            <div class="sport-title">
                @if(session('sport') == 'Pickleball')
                    <i class="fa-solid fa-table-tennis-paddle-ball" style="font-size: 30px;"></i>
                @else
                    <i class="fa-solid fa-shuttlecock" style="font-size: 30px; background: -webkit-linear-gradient(#0033cc, #001f7a); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                @endif
                {{ session('sport', 'Badminton') }}
            </div>
            
            <div style="border: 1px solid #eaeaea; border-radius: 12px; padding: 25px; background: white;">
                <div class="amount-row">
                    <span class="amount-label">Total Amount</span>
                    <span class="amount-value">₱ {{ number_format(session('total_price', 230), 2) }}</span>
                </div>
                
                <div class="gcash-details">
                    <div class="gcash-info">
                        <span style="color: #9ca3af; font-size: 13px; font-weight: 600; display: block; margin-bottom: 10px;">Payment Method</span>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="background: #007bff; color: white; width: 45px; height: 45px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; font-size: 22px;">G</div>
                            <div>
                                <h3>Gcash</h3>
                                <p>09123456789</p>
                            </div>
                        </div>
                    </div>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=110x110&data=GcashPayment" alt="GCash QR" style="border-radius: 8px; border: 1px solid #eaeaea; padding: 5px;" class="desktop-only-qr">
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
                <span class="price-text">₱ {{ number_format(session('total_price', 230), 2) }}</span>
            </div>
            
            <div class="radio-option">
                <label class="radio-label" style="align-items: flex-start;">
                    <input type="radio" name="payment_type" value="half" style="margin-top: 3px;">
                    <div style="display: flex; flex-direction: column;">
                        50% Down Payment
                        <span style="color: #9ca3af; font-size: 12px; font-weight: normal; margin-top: 5px; line-height: 1.4;">Please pay the remaining balance<br>before your playing time.</span>
                    </div>
                </label>
                <span class="price-text">₱ {{ number_format(session('total_price', 230) / 2, 2) }}</span>
            </div>
        </div>
    </div>

    <div class="panel upload-container">
        <h3>Upload Receipt <span style="color: #9ca3af; font-size: 13px; font-weight: normal;">(Required)</span></h3>
        <p class="sub">Please upload the Gcash receipt</p>
        
        <label for="receipt" class="upload-area">
            <div class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
            <div class="upload-text">Drag and drop your file here<div class="upload-or">or</div></div>
            <button type="button" class="btn-outline">Choose File</button>
            <input type="file" name="receipt" id="receipt" style="position: absolute; opacity: 0; width: 1px; height: 1px;" accept="image/*">
        </label>
        <div id="receiptError" style="color: var(--danger-red); font-size: 13px; display: none; margin-top: 10px; font-weight: 600;"><i class="fa-solid fa-circle-exclamation"></i> Please upload a GCash receipt before completing your payment.</div>
        <div id="fileNameDisplay" style="color: var(--primary-blue); font-size: 14px; margin-top: 15px; font-weight: 600; text-align: center; display: none;"></div>
    </div>
    <div class="upload-hint upload-container">Accepted file: JPG, PNG (Max.5MB)</div>

    <button type="submit" class="btn-submit">Complete Payment</button>
</form>
@endsection

@section('modals')
<div class="modal-overlay success-modal" id="successModal">
    <div class="modal-content">
        <!-- Confetti Decoration -->
        <div class="confetti confetti-1"></div>
        <div class="confetti confetti-2"></div>
        <div class="confetti confetti-3"></div>
        <div class="confetti confetti-4"></div>
        <div class="confetti confetti-5"></div>
        <div class="confetti confetti-6"></div>
        <div class="confetti confetti-7"></div>
        <div class="confetti confetti-8"></div>
        
        <button class="modal-close" onclick="closeSuccessModal()">&times;</button>
        
        <div class="success-circle">
            <i class="fa-solid fa-check"></i>
        </div>
        
        <h2 class="modal-title">Payment Successful!</h2>
        
        <p class="modal-text">
            Your reservation for <strong>{{ session('flash_sport') }} Court {{ session('flash_court') }}</strong><br>
            on <strong>{{ session('flash_start') ? \Carbon\Carbon::parse(session('flash_start'))->format('M j, Y') : '' }}</strong><br>
            has been successfully booked.
        </p>
        
        <div class="reservation-id">
            Reservation ID: {{ session('reservation_code') }}
        </div>
        
        <div class="qr-box">
            @if(session('reservation_code'))
                <img id="qr-image" src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ urlencode(session('reservation_code')) }}" alt="QR Code">
            @endif
        </div>
        
        <div class="qr-hint">Scan this QR code at the entrance</div>
        
        <button class="btn-download" onclick="downloadQR()">Download QR Code</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // File upload label update
    document.getElementById('receipt').addEventListener('change', function(e) {
        if(e.target.files.length > 0) {
            document.querySelector('.upload-text').innerText = e.target.files[0].name;
            document.querySelector('.upload-text').style.color = 'var(--success-green)';
            document.querySelector('.upload-icon').style.color = 'var(--success-green)';
            document.getElementById('receiptError').style.display = 'none';
        }
    });

    function validatePayment(e) {
        const fileInput = document.getElementById('receipt');
        if(fileInput.files.length === 0) {
            e.preventDefault();
            document.getElementById('receiptError').style.display = 'block';
            return false;
        }
        return true;
    }

    // Check if session has success message, then show modal
    @if(session('success'))
        document.getElementById('successModal').style.display = 'flex';
    @endif

    function closeSuccessModal() {
        document.getElementById('successModal').style.display = 'none';
        // Redirect to reservation tab instead of reloading
        window.location.href = "{{ route('reservation.index') }}";
    }

    function downloadQR() {
        const qrImage = document.getElementById('qr-image').src;
        fetch(qrImage)
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = 'Reservation_QR_{{ session("reservation_code") }}.png';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            })
            .catch(() => alert('Failed to download QR code.'));
    }
</script>
@endsection