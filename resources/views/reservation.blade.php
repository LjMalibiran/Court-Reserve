@extends('layouts.app')

@section('title', 'New Reservation | Court Reserve')
@section('header_title', 'New Reservation')

@section('styles')
<style>
    /* Step Panels */
    .booking-grid { display: grid; grid-template-columns: 1fr 400px; gap: 30px; margin-top: 10px; max-width: 1000px; }
    
    .left-column { display: flex; flex-direction: column; gap: 20px; }
    .right-column { display: flex; flex-direction: column; gap: 20px; }
    
    .step-panel { background: white; border-radius: 12px; padding: 25px; box-shadow: none; border: 1px solid #eaeaea; }
    .step-header { display: flex; align-items: center; margin-bottom: 20px; }
    .step-circle { background: var(--primary-blue); color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; margin-right: 15px; font-size: 14px; }
    .step-title { margin: 0; color: var(--primary-blue); font-size: 18px; font-weight: 600; }
    
    /* Sport Icon */
    .sport-icon-header { display: flex; justify-content: center; margin-bottom: 10px; }
    .sport-icon-header i { font-size: 30px; color: var(--primary-blue); }
    .sport-icon-header img { width: 35px; }

    /* Court Selection */
    .court-selection { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
    .court-btn { padding: 15px 5px; border: 1px solid #ddd; border-radius: 8px; text-align: center; cursor: pointer; transition: 0.2s; background: white; font-weight: 600; font-size: 15px; color: #9ca3af; }
    .court-btn.active { border-color: var(--primary-blue); color: var(--primary-blue); }
    
    /* Time Selection */
    .date-input { width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-family: inherit; margin-bottom: 20px; outline: none; font-size: 15px; color: var(--text-dark); }
    .time-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 12px; }
    .time-slot { padding: 10px; border: 1px solid #ddd; border-radius: 6px; text-align: center; font-size: 13px; cursor: pointer; transition: 0.2s; color: var(--primary-blue); font-weight: 500; }
    .time-slot.selected { background: var(--primary-blue); color: white; border-color: var(--primary-blue); }
    .time-slot.booked { background: #f9fafb; color: #d1d5db; text-decoration: line-through; cursor: not-allowed; border-color: #f3f4f6; }

    /* Right Panel: Rentals & Summary */
    .rental-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #f3f4f6; }
    .rental-item:last-child { border-bottom: none; padding-bottom: 0; }
    .item-info h4 { margin: 0 0 5px 0; color: var(--primary-blue); font-size: 16px; font-weight: 600; }
    .item-info p { margin: 0; color: #9ca3af; font-size: 11px; }
    
    .counter { display: flex; align-items: center; gap: 10px; }
    .counter-btn { width: 28px; height: 28px; border-radius: 4px; border: 1px solid #ddd; background: white; display: flex; justify-content: center; align-items: center; cursor: pointer; color: var(--text-gray); font-size: 16px; }
    .counter-btn:hover { background: #f9fafb; }
    
    /* Summary Box */
    .summary-box { background: white; border-radius: 12px; padding: 30px; border: 1px solid #eaeaea; position: relative; overflow: hidden; }
    .summary-box::after { content: ""; position: absolute; bottom: -30px; right: -30px; width: 250px; height: 250px; background-image: url('{{ asset('images/shuttlecock.png') }}'); background-size: contain; background-repeat: no-repeat; opacity: 0.05; pointer-events: none; }
    .summary-title { margin: 0 0 25px 0; color: var(--primary-blue); text-align: center; font-size: 20px; font-weight: 600; }
    
    .summary-sport { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; }
    .summary-sport h3 { margin: 0; font-size: 18px; color: var(--primary-blue); font-weight: 700; }
    
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 14px; position: relative; z-index: 1; }
    .summary-row span:first-child { color: var(--text-gray); }
    .summary-row span:last-child { font-weight: 600; color: var(--primary-blue); }
    
    .summary-total { display: flex; justify-content: space-between; margin-top: 25px; padding-top: 20px; border-top: 2px solid #f3f4f6; font-size: 16px; font-weight: bold; position: relative; z-index: 1; color: var(--primary-blue); }
    .btn-proceed { background: var(--primary-blue); color: white; border: none; width: 100%; padding: 15px; border-radius: 8px; font-size: 16px; font-weight: bold; margin-top: 30px; cursor: pointer; position: relative; z-index: 1; transition: 0.2s; }
    .btn-proceed:hover { background: #002299; }

    .alert-error { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }

    @media (max-width: 768px) {
        .booking-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')

@if($errors->any())
    <div class="alert-error">
        <strong><i class="fa-solid fa-circle-exclamation"></i> Booking Failed:</strong> {{ $errors->first() }}
    </div>
@endif

<form action="{{ route('reservations.store') }}" method="POST" id="reservationForm" onsubmit="return validateForm()">
    @csrf

    <!-- Hidden Inputs -->
    <input type="hidden" name="court_id" id="hidden_court_id" value="1">
    <input type="hidden" name="sport" id="hidden_sport" value="Badminton">
    <input type="hidden" name="start_time" id="hidden_start_time" value="">
    <input type="hidden" name="end_time" id="hidden_end_time" value="">
    
    <div class="booking-grid">
        <div class="left-column">
            <div class="sport-icon-header" id="sportIconHeader">
                <!-- Icon injected by JS -->
            </div>

            <div class="sport-toggle" style="display: none;">
                <!-- Keep toggle hidden to preserve functionality/JS -->
                <button type="button" class="sport-btn active" id="btn-badminton" onclick="selectSport('Badminton')">Badminton</button>
                <button type="button" class="sport-btn" id="btn-pickleball" onclick="selectSport('Pickleball')">Pickleball</button>
            </div>

            <div class="step-panel">
                <div class="step-header">
                    <div class="step-circle">1</div>
                    <h2 class="step-title">Select Date & Time</h2>
                </div>
                
                <input type="date" name="reservation_date" class="date-input" id="resDate" min="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}" required>
                
                <p style="font-size: 11px; color: var(--primary-blue); margin-bottom: 15px; font-weight: 600;">Available Time Slot</p>
                
                <div class="time-grid" id="timeSlots">
                    <div class="time-slot" onclick="selectTime(this)">8:00 AM</div>
                    <div class="time-slot" onclick="selectTime(this)">9:00 AM</div>
                    <div class="time-slot" onclick="selectTime(this)">10:00 AM</div>
                    <div class="time-slot" onclick="selectTime(this)">11:00 AM</div>
                    <div class="time-slot" onclick="selectTime(this)">12:00 PM</div>
                    <div class="time-slot" onclick="selectTime(this)">1:00 PM</div>
                    <div class="time-slot" onclick="selectTime(this)">2:00 PM</div>
                    <div class="time-slot" onclick="selectTime(this)">3:00 PM</div>
                    <div class="time-slot" onclick="selectTime(this)">4:00 PM</div>
                    <div class="time-slot" onclick="selectTime(this)">5:00 PM</div>
                    <div class="time-slot" onclick="selectTime(this)">6:00 PM</div>
                    <div class="time-slot" onclick="selectTime(this)">7:00 PM</div>
                    <div class="time-slot" onclick="selectTime(this)">8:00 PM</div>
                    <div class="time-slot" onclick="selectTime(this)">9:00 PM</div>
                </div>
            </div>

            <div class="step-panel">
                <div class="step-header">
                    <div class="step-circle">2</div>
                    <h2 class="step-title">Select Court</h2>
                </div>
                
                <div class="court-selection">
                    <div class="court-btn active" id="court1" onclick="selectCourt(1)">Court 1</div>
                    <div class="court-btn" id="court2" onclick="selectCourt(2)">Court 2</div>
                    <div class="court-btn" id="court3" onclick="selectCourt(3)">Court 3</div>
                </div>
            </div>

            <div class="step-panel">
                <div class="step-header">
                    <div class="step-circle">3</div>
                    <div style="display: flex; flex-direction: column;">
                        <h2 class="step-title">Play Duration</h2>
                        <span style="font-size: 11px; color: #9ca3af; margin-top: 3px;">₱230.00 / hr</span>
                    </div>
                </div>
                <select class="date-input" name="duration" id="durationSelect" style="margin-bottom: 0;">
                    <option value="1">1 Hour</option>
                    <option value="2">2 Hours</option>
                    <option value="3">3 Hours</option>
                </select>
            </div>
        </div>

        <div class="right-column">
            <div class="step-panel" id="rentalSection" style="margin-bottom: 0;">
                <div class="step-header">
                    <div class="step-circle">4</div>
                    <h2 class="step-title">Rental Items <span style="color: #9ca3af; font-size: 13px; font-weight: normal; margin-left: 5px;">(Optional)</span></h2>
                </div>
                
                <div class="rental-item" id="rentalRacket">
                    <div class="item-info">
                        <h4>Racket</h4>
                        <p>₱50.00 / pc</p>
                    </div>
                    <div class="counter">
                        <button type="button" class="counter-btn" onclick="updateRental('racket', -1)">-</button>
                        <input type="text" name="rackets" id="racketCount" value="0" readonly style="width: 25px; text-align: center; border: none; background: transparent; font-weight: 600; font-size: 14px; color: var(--primary-blue);">
                        <button type="button" class="counter-btn" onclick="updateRental('racket', 1)">+</button>
                    </div>
                </div>

                <div class="rental-item" id="rentalShuttlecock">
                    <div class="item-info">
                        <h4>Shuttlecock</h4>
                        <p>₱50.00 / pc</p>
                    </div>
                    <div class="counter">
                        <button type="button" class="counter-btn" onclick="updateRental('shuttlecock', -1)">-</button>
                        <input type="text" name="shuttlecocks" id="shuttlecockCount" value="0" readonly style="width: 25px; text-align: center; border: none; background: transparent; font-weight: 600; font-size: 14px; color: var(--primary-blue);">
                        <button type="button" class="counter-btn" onclick="updateRental('shuttlecock', 1)">+</button>
                    </div>
                </div>
            </div>

            <div class="summary-box">
                <h2 class="summary-title">Summary</h2>
                
                <div class="summary-sport">
                    <div id="summarySportIcon">
                        <!-- JS injected -->
                    </div>
                    <h3 id="summarySportText">Badminton</h3>
                </div>

                <div class="summary-row">
                    <span>Court</span>
                    <span id="summaryCourt">Court 1</span>
                </div>
                <div class="summary-row">
                    <span>Date</span>
                    <span id="summaryDate">{{ date('F j, Y') }}</span>
                </div>
                <div class="summary-row">
                    <span>Time</span>
                    <span id="summaryTime" style="color: #9ca3af;">Not selected</span>
                </div>
                <div class="summary-row">
                    <span>Duration</span>
                    <span id="summaryDuration">1 Hour</span>
                </div>
                <div class="summary-row">
                    <span>Rental</span>
                    <span id="summaryRental">None</span>
                </div>

                <div class="summary-total">
                    <span>Total Amount</span>
                    <span id="summaryTotal">₱ 230.00</span>
                </div>

                <button type="submit" class="btn-proceed">Next</button>
            </div>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script>
    let currentSport = 'Badminton';
    let currentCourtNum = 1;
    let selectedTimeString = null;

    document.getElementById('resDate').addEventListener('change', function() {
        const dateObj = new Date(this.value);
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('summaryDate').innerText = dateObj.toLocaleDateString(undefined, options);
        checkAvailability(); 
    });

    function selectSport(sport) {
        currentSport = sport;
        document.getElementById('hidden_sport').value = sport;
        
        document.getElementById('btn-badminton').classList.remove('active');
        document.getElementById('btn-pickleball').classList.remove('active');
        document.getElementById('btn-' + sport.toLowerCase()).classList.add('active');

        if (sport === 'Pickleball') {
            document.getElementById('rentalRacket').style.display = 'none';
            document.getElementById('rentalShuttlecock').style.display = 'none';
            document.getElementById('racketCount').value = 0;
            document.getElementById('shuttlecockCount').value = 0;
        } else {
            document.getElementById('rentalRacket').style.display = 'flex';
            document.getElementById('rentalShuttlecock').style.display = 'flex';
        }

        document.getElementById('summarySportText').innerText = sport;
        if (sport === 'Badminton') {
            const badmIcon = `<i class="fa-solid fa-shuttlecock"></i>`;
            document.getElementById('summarySportIcon').innerHTML = badmIcon;
            document.getElementById('sportIconHeader').innerHTML = `<i class="fa-solid fa-shuttlecock" style="background: -webkit-linear-gradient(#0033cc, #001f7a); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>`;
        } else {
            const pickIcon = `<i class="fa-solid fa-table-tennis-paddle-ball"></i>`;
            document.getElementById('summarySportIcon').innerHTML = pickIcon;
            document.getElementById('sportIconHeader').innerHTML = pickIcon;
        }

        currentCourtNum = 1;
        updateCourtDisplay();
    }

    function selectCourt(courtNum) {
        currentCourtNum = courtNum;
        updateCourtDisplay();
    }

    function updateCourtDisplay() {
        document.querySelectorAll('.court-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('court' + currentCourtNum).classList.add('active');
        
        document.querySelectorAll('.court-icon-badminton').forEach(icon => {
            icon.style.display = (currentSport === 'Badminton') ? 'block' : 'none';
        });
        document.querySelectorAll('.court-icon-pickleball').forEach(icon => {
            icon.style.display = (currentSport === 'Pickleball') ? 'block' : 'none';
        });
        
        document.getElementById('summaryCourt').innerText = 'Court ' + currentCourtNum;
        
        let actualCourtId = (currentSport === 'Pickleball') ? 2 : 1;
        document.getElementById('hidden_court_id').value = actualCourtId;

        checkAvailability(); 
        calculateTotal();
    }

    function updateRental(type, change) {
        const input = document.getElementById(type + 'Count');
        let val = parseInt(input.value) + change;
        
        if (val >= 0 && val <= 5) {
            input.value = val;
            calculateTotal();
        }
    }

    function calculateTotal() {
        let durationStr = document.getElementById('durationSelect').value;
        let durationHours = parseInt(durationStr);
        let courtPrice = 230; 
        
        let total = durationHours * courtPrice;

        if (currentSport === 'Badminton') {
            let rackets = parseInt(document.getElementById('racketCount').value);
            let shuttles = parseInt(document.getElementById('shuttlecockCount').value);
            total += (rackets * 50) + (shuttles * 50);

            let rentalText = [];
            if (rackets > 0) rentalText.push(rackets + 'x Racket');
            if (shuttles > 0) rentalText.push(shuttles + 'x Shuttlecock');
            document.getElementById('summaryRental').innerText = rentalText.length > 0 ? rentalText.join(', ') : 'None';
        } else {
            document.getElementById('summaryRental').innerText = 'None';
        }

        document.getElementById('summaryDuration').innerText = durationHours + (durationHours > 1 ? ' Hours' : ' Hour');
        document.getElementById('summaryTotal').innerText = '₱ ' + total.toFixed(2);
    }

    function selectTime(element) {
        if(element.classList.contains('booked')) return;

        document.querySelectorAll('.time-slot').forEach(slot => slot.classList.remove('selected'));
        element.classList.add('selected');
        
        let timeText = element.innerText;
        selectedTimeString = timeText;
        document.getElementById('summaryTime').innerText = timeText;
        document.getElementById('summaryTime').style.color = "white";

        let parts = timeText.match(/^(\d+):(\d+)\s*(AM|PM)$/i);
        let startHour = parseInt(parts[1]);
        let modifier = parts[3].toUpperCase();

        if (modifier === 'PM' && startHour !== 12) startHour += 12;
        if (modifier === 'AM' && startHour === 12) startHour = 0;

        let duration = parseInt(document.getElementById('durationSelect').value);
        let endHour = startHour + duration;

        let startStr = startHour.toString().padStart(2, '0') + ':00:00';
        let endStr = endHour.toString().padStart(2, '0') + ':00:00';

        document.getElementById('hidden_start_time').value = startStr;
        document.getElementById('hidden_end_time').value = endStr;
    }

    function validateForm() {
        if (!document.getElementById('hidden_start_time').value) {
            alert('Please select a time slot!');
            return false;
        }
        return true;
    }

    function checkAvailability() {
        let date = document.getElementById('resDate').value;
        let courtId = document.getElementById('hidden_court_id').value;

        if(!date || !courtId) return;

        fetch(`/api/check-availability?date=${date}&court_id=${courtId}`)
            .then(response => response.json())
            .then(data => {
                let bookedSlots = data.booked_slots;

                document.querySelectorAll('.time-slot').forEach(slot => {
                    let timeText = slot.innerText.trim();
                    
                    slot.classList.remove('booked');
                    
                    if(bookedSlots.includes(timeText)) {
                        slot.classList.add('booked');
                        slot.classList.remove('selected');
                        
                        if(selectedTimeString === timeText) {
                            selectedTimeString = null;
                            document.getElementById('summaryTime').innerText = "Not selected";
                            document.getElementById('summaryTime').style.color = "#ffcccc";
                            document.getElementById('hidden_start_time').value = "";
                            document.getElementById('hidden_end_time').value = "";
                        }
                    }
                });
                markPastSlots();
            });
    }

    document.getElementById('durationSelect').addEventListener('change', calculateTotal);

    function markPastSlots() {
        let selectedDate = document.getElementById('resDate').value;
        let now = new Date();
        let manilaTime = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
        let todayStr = manilaTime.getFullYear() + '-' + 
                       String(manilaTime.getMonth() + 1).padStart(2, '0') + '-' + 
                       String(manilaTime.getDate()).padStart(2, '0');
        
        if (selectedDate !== todayStr) return;
        
        let currentHour = manilaTime.getHours();
        
        document.querySelectorAll('.time-slot').forEach(slot => {
            let timeText = slot.innerText.trim();
            let parts = timeText.match(/^(\d+):(\d+)\s*(AM|PM)$/i);
            if (!parts) return;
            let slotHour = parseInt(parts[1]);
            let modifier = parts[3].toUpperCase();
            if (modifier === 'PM' && slotHour !== 12) slotHour += 12;
            if (modifier === 'AM' && slotHour === 12) slotHour = 0;
            
            if (slotHour <= currentHour) {
                slot.classList.add('booked');
                slot.classList.remove('selected');
                
                if (selectedTimeString === timeText) {
                    selectedTimeString = null;
                    document.getElementById('summaryTime').innerText = "Not selected";
                    document.getElementById('summaryTime').style.color = "#ffcccc";
                    document.getElementById('hidden_start_time').value = "";
                    document.getElementById('hidden_end_time').value = "";
                }
            }
        });
    }
    
    updateCourtDisplay();
    calculateTotal();
    checkAvailability();
    markPastSlots();

    (function() {
        let params = new URLSearchParams(window.location.search);
        let sport = params.get('sport');
        if (sport === 'Pickleball' || sport === 'Badminton') {
            selectSport(sport);
        }
    })();
</script>
@endsection