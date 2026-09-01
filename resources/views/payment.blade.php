@extends('layouts.app')

@section('title', 'Payment | Court Reserve')
@section('header_title', 'Payment')

@php
    // Strictly calculate the total to ensure the updated ₱250/hr Pickleball price 
    // and correct hours are applied perfectly before the user pays.
    $sport = session('sport', 'Badminton');
    $courtPrice = ($sport === 'Pickleball') ? 250 : 230;
    
    $startTime = session('start_time') ? \Carbon\Carbon::parse(session('start_time')) : now();
    $endTime = session('end_time') ? \Carbon\Carbon::parse(session('end_time')) : now()->addHour();
    $hours = $startTime->diffInHours($endTime);
    if ($hours < 1) $hours = 1;

    $rackets = session('rackets', 0);
    $shuttles = session('shuttles', 0);
    $rentals = ($rackets * 50) + ($shuttles * 50);

    $totalAmount = ($hours * $courtPrice) + $rentals;
    $halfAmount = $totalAmount / 2;
@endphp

@section('styles')
<style>
    .payment-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 30px; margin-top: 10px; max-width: 1000px; }
    
    .panel { background: white; border-radius: 16px; padding: 32px; border: 1.5px solid #e2e8f0; position: relative; }
    
    /* Left Panel: Details */
    .sport-title { display: flex; align-items: center; gap: 12px; color: #0f2b6e; font-size: 24px; font-weight: 800; margin-bottom: 25px; }
    
    .amount-box { border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 24px; background: white; }
    .amount-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .amount-label { color: #0f2b6e; font-size: 15px; font-weight: 700; }
    .amount-value { color: #0f2b6e; font-size: 18px; font-weight: 800; }
    
    .gcash-details { display: flex; justify-content: space-between; align-items: center; border-top: 1.5px solid #e2e8f0; padding-top: 24px; }
    .gcash-info h3 { margin: 0; color: #0f2b6e; font-size: 18px; font-weight: 700; }
    .gcash-info p { margin: 2px 0 0 0; color: #64748b; font-size: 14px; font-weight: 600;}
    .gcash-qr-wrap { background: white; padding: 6px; border: 1.5px solid #e2e8f0; border-radius: 8px; }
    
    /* Right Panel: Options */
    .panel-title { margin-top: 0; color: #0f2b6e; font-size: 20px; font-weight: 800; margin-bottom: 6px; }
    .panel-sub { color: #64748b; font-size: 13px; margin-top: 0; margin-bottom: 24px; font-weight: 500;}
    
    .radio-option { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 20px; cursor: pointer; }
    .radio-label { display: flex; align-items: flex-start; cursor: pointer; color: #0f2b6e; font-weight: 700; font-size: 16px; flex: 1;}
    .radio-label input { appearance: none; min-width: 20px; width: 20px; height: 20px; border: 2px solid #cbd5e1; border-radius: 50%; margin-right: 14px; outline: none; position: relative; cursor: pointer; margin-top: 2px;}
    .radio-label input:checked { border-color: #0033cc; }
    .radio-label input:checked::after { content: ''; position: absolute; top: 3px; left: 3px; width: 10px; height: 10px; background: #0033cc; border-radius: 50%; }
    .price-text { color: #0f2b6e; font-weight: 800; font-size: 16px; margin-left: 15px;}
    .radio-sub { color: #94a3b8; font-size: 12px; font-weight: 500; margin-top: 4px; line-height: 1.4; display: block; }
    
    /* Upload Area */
    .upload-area { border: 2px dashed #cbd5e1; border-radius: 12px; padding: 40px 20px; text-align: center; background: white; cursor: pointer; transition: 0.2s; display: block; position: relative; margin-bottom: 12px;}
    .upload-area:hover { border-color: #0033cc; background: #f8fafc; }
    .upload-icon { font-size: 45px; color: #0033cc; margin-bottom: 15px; }
    .upload-text { color: #0f2b6e; font-size: 16px; font-weight: 700; margin-bottom: 12px; }
    .upload-or { color: #64748b; font-size: 13px; margin-bottom: 15px; font-weight: 600; }
    .btn-outline { border: 1.5px solid #0033cc; color: #0033cc; background: white; padding: 10px 28px; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 14px; transition: 0.2s; }
    .btn-outline:hover { background: #f0f4ff; }
    .upload-hint { text-align: center; color: #94a3b8; font-size: 12px; font-weight: 600;}
    
    /* Submit Button */
    .btn-submit-container { max-width: 1000px; display: flex; justify-content: space-between; margin-top: 10px;}
    .btn-submit { background: #0033cc; color: white; border: none; padding: 16px 40px; border-radius: 8px; font-size: 16px; font-weight: 700; cursor: pointer; transition: 0.2s; min-width: 200px;}
    .btn-submit:hover { background: #002299; }
    
    .btn-back { background: white; color: #64748b; border: 1.5px solid #cbd5e1; padding: 15px 40px; border-radius: 8px; font-size: 16px; font-weight: 700; cursor: pointer; transition: 0.2s; min-width: 200px; text-decoration: none; text-align: center; display: inline-block; box-sizing: border-box; }
    .btn-back:hover { background: #f1f5f9; color: #334155; }
    
    .error-msg { color: #dc2626; font-size: 13px; display: none; margin-top: 10px; font-weight: 600; text-align: center;}

    /* MODAL */
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); justify-content: center; align-items: center; z-index: 9999; padding: 20px; }
    .modal-content { background: white; border-radius: 20px; padding: 40px; width: 100%; max-width: 450px; position: relative; box-shadow: 0 10px 40px rgba(0,0,0,0.15); text-align: center; }
    .modal-close { position: absolute; top: 20px; right: 24px; background: none; border: none; font-size: 26px; color: #0f2b6e; cursor: pointer; padding: 0; line-height: 1; font-weight: 300;}
    
    .success-icon-wrap { position: relative; width: 90px; height: 90px; margin: 0 auto 20px auto; }
    .success-circle { background: #22c55e; color: white; width: 100%; height: 100%; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 45px; position: relative; z-index: 2; box-shadow: 0 0 0 6px #dcfce7;}
    .confetti { position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: 1; }
    .confetti::before, .confetti::after { content: ''; position: absolute; width: 8px; height: 8px; border-radius: 50%; }
    .confetti::before { background: #eab308; top: -15px; left: -10px; box-shadow: 60px 10px 0 #22c55e, 100px 30px 0 #0033cc, -20px 50px 0 #ef4444; }
    .confetti::after { background: #0033cc; bottom: -15px; right: -10px; box-shadow: -80px -10px 0 #ef4444, -100px -30px 0 #22c55e, 20px -50px 0 #eab308; }

    .modal-title { color: #0033cc; margin: 0 0 15px 0; font-size: 24px; font-weight: 800; }
    .modal-text { color: #64748b; font-size: 14px; line-height: 1.6; margin-bottom: 20px; font-weight: 500;}
    .modal-text strong { color: #0033cc; font-weight: 700;}
    
    .reservation-id { color: #64748b; font-size: 16px; font-weight: 500; margin-bottom: 12px; }
    .reservation-id strong { color: #0033cc; font-weight: 800;}
    
    .qr-box { border: 2px solid #0033cc; border-radius: 12px; padding: 15px; display: inline-block; margin-bottom: 12px; }
    .qr-hint { color: #64748b; font-size: 11px; margin-bottom: 25px; font-weight: 600;}
    
    .btn-download { border: 1.5px solid #0033cc; color: #0033cc; background: white; padding: 14px; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 15px; width: 100%; box-sizing: border-box; transition: 0.2s; }
    .btn-download:hover { background: #f0f4ff; }

    @media (max-width: 768px) {
        .payment-grid { grid-template-columns: 1fr; }
        .gcash-details { flex-direction: column; align-items: flex-start; gap: 20px; }
        .btn-submit-container { flex-direction: column; gap: 15px; }
        .btn-submit, .btn-back { width: 100%; min-width: auto; margin: 0; }
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
    <input type="hidden" name="total_amount" value="{{ $totalAmount }}">

    <div class="payment-grid">
        <!-- Left Panel: Summary -->
        <div>
            <div class="sport-title">
                @if($sport == 'Pickleball')
                    <svg width="40" height="40" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="32" cy="32" r="24" fill="#f97316" stroke="#ea580c" stroke-width="2"/>
                        <circle cx="32" cy="18" r="3.2" fill="#ffffff"/><circle cx="21" cy="23" r="3.2" fill="#ffffff"/><circle cx="43" cy="23" r="3.2" fill="#ffffff"/><circle cx="16" cy="32" r="3.2" fill="#ffffff"/><circle cx="32" cy="32" r="3.5" fill="#ffffff"/><circle cx="48" cy="32" r="3.2" fill="#ffffff"/><circle cx="21" cy="41" r="3.2" fill="#ffffff"/><circle cx="43" cy="41" r="3.2" fill="#ffffff"/><circle cx="32" cy="46" r="3.2" fill="#ffffff"/>
                    </svg>
                @else
                    <svg width="40" height="40" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g transform="translate(6, 6) rotate(-25 26 26)">
                            <path d="M12 10C10 18 12 28 18 36L34 36C40 28 42 18 40 10C35 12 26 12 12 10Z" fill="#e0e7ff" fill-opacity="0.35" stroke="#0033cc" stroke-width="2.5" stroke-linejoin="round"/>
                            <path d="M16 11C20 18 22 28 24 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                            <path d="M36 11C32 18 30 28 28 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                            <path d="M26 11L26 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                            <path d="M14 20C20 23 32 23 38 20" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                            <path d="M16 28C21 31 36 28" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/>
                            <rect x="18" y="36" width="16" height="3" rx="1" fill="#0033cc"/>
                            <path d="M18 39C18 44.5 21.5 48 26 48C30.5 48 34 44.5 34 39H18Z" fill="#0033cc"/>
                        </g>
                    </svg>
                @endif
                {{ $sport }}
            </div>
            
            <div class="amount-box">
                <div class="amount-row">
                    <span class="amount-label">Total Amount</span>
                    <span class="amount-value">₱ {{ number_format($totalAmount, 2) }}</span>
                </div>
                
                <div class="gcash-details">
                    <div>
                        <span style="color: #94a3b8; font-size: 12px; font-weight: 600; display: block; margin-bottom: 12px;">Payment Method</span>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="background: #007bff; color: white; width: 42px; height: 42px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; font-size: 22px;">G</div>
                            <div class="gcash-info">
                                <h3>Gcash</h3>
                                <p>09123456789</p>
                            </div>
                        </div>
                    </div>
                    <div class="gcash-qr-wrap">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data=GcashPayment" alt="GCash QR">
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel: Options & Upload -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            <div class="panel" style="padding: 24px;">
                <h3 class="panel-title">Payment Option</h3>
                <p class="panel-sub">50% Down Payment Required to<br>Confirm Reservation</p>
                
                <label class="radio-option">
                    <div class="radio-label">
                        <input type="radio" name="payment_type" value="full" checked>
                        Full Payment
                    </div>
                    <span class="price-text">₱ {{ number_format($totalAmount, 2) }}</span>
                </label>
                
                <label class="radio-option" style="margin-bottom: 0;">
                    <div class="radio-label">
                        <input type="radio" name="payment_type" value="half">
                        <div>
                            50% Down Payment
                            <span class="radio-sub">Please pay the remaining balance<br>before your playing time.</span>
                        </div>
                    </div>
                    <span class="price-text">₱ {{ number_format($halfAmount, 2) }}</span>
                </label>
            </div>

            <div class="panel" style="padding: 24px;">
                <h3 class="panel-title" style="font-size: 18px;">Upload Receipt <span style="color: #94a3b8; font-size: 13px; font-weight: 500;">(Required)</span></h3>
                <p class="panel-sub">Please upload the Gcash receipt</p>
                
                <label for="receipt" class="upload-area">
                    <div class="upload-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                    <div class="upload-text">Drag and drop your file here<div class="upload-or">or</div></div>
                    <div class="btn-outline">Choose File</div>
                    <input type="file" name="receipt" id="receipt" style="position: absolute; opacity: 0; width: 1px; height: 1px;" accept="image/*">
                </label>
                <div class="upload-hint">Accepted file: JPG, PNG (Max.5MB)</div>
                <div class="error-msg" id="receiptError"><i class="fa-solid fa-circle-exclamation"></i> Please upload a receipt.</div>
            </div>
        </div>
    </div>
    
    <div class="btn-submit-container">
        <a href="{{ route('reservation.index') }}" class="btn-back">Back</a>
        <button type="submit" class="btn-submit">Next</button>
    </div>
</form>
@endsection

@section('modals')
<div class="modal-overlay" id="successModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeSuccessModal()">&times;</button>
        
        <div class="success-icon-wrap">
            <div class="confetti"></div>
            <div class="success-circle">
                <i class="fa-solid fa-check"></i>
            </div>
        </div>
        
        <h2 class="modal-title">Request Received!</h2>
        
        <p class="modal-text">
            Your reservation for<br>
            <strong>{{ session('flash_sport') }} Court {{ session('flash_court') }}</strong><br>
            on <strong>{{ session('flash_start') ? \Carbon\Carbon::parse(session('flash_start'))->format('M j, Y') : '' }} at {{ session('flash_start') ? \Carbon\Carbon::parse(session('flash_start'))->format('g:i A') : '' }}</strong><br>
            has been submitted and is awaiting confirmation.
        </p>
        
        <div class="reservation-id">
            Reservation ID: <strong>{{ session('reservation_code') }}</strong>
        </div>
        
        <div class="qr-box">
            @if(session('reservation_code'))
                <img id="qr-image" src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode(session('reservation_code')) }}" crossorigin="anonymous" alt="QR Code" style="display: block;">
            @endif
        </div>
        
        <div class="qr-hint">Please arrive 3-5 minutes before your scheduled time.</div>
        
        <button class="btn-download" onclick="downloadReceipt()">Download Receipt</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('receipt').addEventListener('change', function(e) {
        if(e.target.files.length > 0) {
            document.querySelector('.upload-text').innerText = e.target.files[0].name;
            document.querySelector('.upload-text').style.color = '#22c55e';
            document.querySelector('.upload-icon').style.color = '#22c55e';
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

    @if(session('success'))
        document.getElementById('successModal').style.display = 'flex';
    @endif

    function closeSuccessModal() {
        document.getElementById('successModal').style.display = 'none';
        window.location.href = "{{ route('reservation.index') }}";
    }

    function downloadReceipt() {
        const modalContent = document.querySelector('#successModal .modal-content');
        const closeBtn = document.querySelector('#successModal .modal-close');
        const downloadBtn = document.querySelector('#successModal .btn-download');

        // Hide buttons before capture
        closeBtn.style.display = 'none';
        downloadBtn.style.display = 'none';

        html2canvas(modalContent, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff'
        }).then(canvas => {
            const url = canvas.toDataURL('image/png');
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'Receipt_{{ session("reservation_code") }}.png';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            
            // Restore buttons
            closeBtn.style.display = 'block';
            downloadBtn.style.display = 'block';
        }).catch(err => {
            console.error(err);
            alert('Failed to download receipt.');
            closeBtn.style.display = 'block';
            downloadBtn.style.display = 'block';
        });
    }
</script>
<!-- Add html2canvas library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
@endsection