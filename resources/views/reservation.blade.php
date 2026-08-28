@extends('layouts.app')

@section('title', 'New Reservation | Court Reserve')
@section('header_title', 'New Reservation')

@section('styles')
<style>
    .booking-grid { display: grid; grid-template-columns: 1fr 400px; gap: 30px; margin-top: 10px; max-width: 1100px; }
    .left-column { display: flex; flex-direction: column; gap: 20px; }
    .right-column { display: flex; flex-direction: column; gap: 20px; }

    .step-panel { background: white; border-radius: 12px; padding: 25px; box-shadow: none; border: 1px solid #eaeaea; }
    .step-header { display: flex; align-items: center; margin-bottom: 20px; }
    .step-circle { background: #0f2b6e; color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; margin-right: 15px; font-size: 14px; }
    .step-title { margin: 0; color: #0f2b6e; font-size: 18px; font-weight: 700; }

    .sport-selection-cards { display: flex; gap: 15px; }
    .sport-card { flex: 1; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 15px; display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer; transition: 0.2s; background: white; font-weight: 700; color: #94a3b8; font-size: 16px; }
    .sport-card svg { opacity: 0.5; transition: 0.2s; }
    .sport-card.active { border-color: #0033cc; background: #f0f4ff; color: #0033cc; }
    .sport-card.active svg { opacity: 1; }

    .court-selection { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
    .court-btn { padding: 15px 5px; border: 1.5px solid #e2e8f0; border-radius: 8px; text-align: center; cursor: pointer; transition: 0.2s; background: white; font-weight: 600; font-size: 15px; color: #9ca3af; }
    .court-btn.active { border-color: #0033cc; color: #0033cc; background: #f0f4ff; }
    .court-btn.disabled { opacity: 0.4; cursor: not-allowed; background: #f8fafc; }

    .date-input { width: 100%; padding: 15px; border: 1.5px solid #e2e8f0; border-radius: 8px; box-sizing: border-box; font-family: inherit; margin-bottom: 20px; outline: none; font-size: 15px; color: #0f2b6e; font-weight: 600;}
    .time-label { font-size: 11px; color: #0f2b6e; margin-bottom: 15px; font-weight: 700; display: block; }
    .time-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 12px; }
    .time-slot { padding: 12px 10px; border: 1.5px solid #e2e8f0; border-radius: 6px; text-align: center; font-size: 13px; cursor: pointer; transition: 0.2s; color: #0033cc; font-weight: 600; }
    .time-slot.selected { background: #0033cc; color: white; border-color: #0033cc; }
    .time-slot.booked { background: #f9fafb; color: #cbd5e1; text-decoration: line-through; cursor: not-allowed; border-color: #f1f5f9; }

    .duration-sub { font-size: 11px; color: #9ca3af; margin-top: 3px; font-weight: normal; }

    .rental-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .rental-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #f3f4f6; }
    .rental-item:last-child { border-bottom: none; padding-bottom: 0; }
    .item-info h4 { margin: 0 0 5px 0; color: #0f2b6e; font-size: 16px; font-weight: 700; }
    .item-info p { margin: 0; color: #9ca3af; font-size: 11px; font-weight: 600;}

    .counter { display: flex; align-items: center; border: 1.5px solid #e2e8f0; border-radius: 6px; overflow: hidden; }
    .counter-btn { width: 32px; height: 32px; background: white; border: none; display: flex; justify-content: center; align-items: center; cursor: pointer; color: #0f2b6e; font-size: 16px; font-weight: 600;}
    .counter-btn:hover { background: #f8fafc; }
    .counter-input { width: 32px; text-align: center; border: none; border-left: 1.5px solid #e2e8f0; border-right: 1.5px solid #e2e8f0; background: transparent; font-weight: 700; font-size: 14px; color: #0f2b6e; pointer-events: none;}

    .summary-box { background: white; border-radius: 12px; padding: 30px; border: 1px solid #eaeaea; position: relative; overflow: hidden; }
    .summary-box::after { content: ""; position: absolute; bottom: -50px; right: -30px; width: 280px; height: 280px; background-image: url('{{ asset('images/shuttlecock.png') }}'); background-size: contain; background-repeat: no-repeat; opacity: 0.04; pointer-events: none; }
    .summary-title { margin: 0 0 25px 0; color: #0f2b6e; text-align: center; font-size: 20px; font-weight: 700; }

    .summary-sport { display: flex; align-items: center; gap: 15px; margin-bottom: 30px; }
    .summary-sport h3 { margin: 0; font-size: 18px; color: #0f2b6e; font-weight: 800; }

    .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 14px; position: relative; z-index: 1; }
    .summary-row span:first-child { color: #64748b; font-weight: 500;}
    .summary-row span:last-child { font-weight: 700; color: #0f2b6e; }

    .summary-total { display: flex; justify-content: space-between; margin-top: 25px; padding-top: 20px; border-top: 2px solid #f3f4f6; font-size: 16px; position: relative; z-index: 1; }
    .summary-total span:first-child { color: #0033cc; font-weight: 700; }
    .summary-total span:last-child { color: #0f2b6e; font-weight: 800; font-size: 20px; }

    .btn-proceed { background: #0033cc; color: white; border: none; width: 100%; padding: 16px; border-radius: 8px; font-size: 16px; font-weight: bold; margin-top: 30px; cursor: pointer; position: relative; z-index: 1; transition: 0.2s; }
    .btn-proceed:hover { background: #002299; }
    .alert-error { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; font-weight: 600;}

    @media (max-width: 768px) {
        .booking-grid { grid-template-columns: 1fr; }
        .sport-selection-cards { flex-direction: column; gap: 10px; }
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
    <input type="hidden" name="court_id" id="hidden_court_id" value="1">
    <input type="hidden" name="sport" id="hidden_sport" value="Badminton">
    <input type="hidden" name="start_time" id="hidden_start_time" value="">
    <input type="hidden" name="end_time" id="hidden_end_time" value="">
    
    <div class="booking-grid">
        <div class="left-column">
            <!-- STEP 1: Select Sport -->
            <div class="step-panel">
                <div class="step-header">
                    <div class="step-circle">1</div>
                    <h2 class="step-title">Select Sport</h2>
                </div>
                <div class="sport-selection-cards">
                    <div class="sport-card active" id="btn-badminton" onclick="selectSport('Badminton')">
                        <svg width="28" height="28" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g transform="translate(6, 6) rotate(-25 26 26)">
                                <path d="M12 10C10 18 12 28 18 36L34 36C40 28 42 18 40 10C35 12 26 12 12 10Z" fill="#e0e7ff" fill-opacity="0.35" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round"/>
                                <path d="M16 11C20 18 22 28 24 36" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M36 11C32 18 30 28 28 36" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M26 11L26 36" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M14 20C20 23 32 23 38 20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M16 28C21 31 31 31 36 28" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <rect x="18" y="36" width="16" height="3" rx="1" fill="currentColor"/>
                                <path d="M18 39C18 44.5 21.5 48 26 48C30.5 48 34 44.5 34 39H18Z" fill="currentColor"/>
                            </g>
                        </svg>
                        Badminton
                    </div>
                    <div class="sport-card" id="btn-pickleball" onclick="selectSport('Pickleball')">
                        <svg width="28" height="28" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="32" cy="32" r="24" fill="#f97316" stroke="currentColor" stroke-width="2"/>
                            <circle cx="32" cy="18" r="3.2" fill="#ffffff"/>
                            <circle cx="21" cy="23" r="3.2" fill="#ffffff"/>
                            <circle cx="43" cy="23" r="3.2" fill="#ffffff"/>
                            <circle cx="16" cy="32" r="3.2" fill="#ffffff"/>
                            <circle cx="32" cy="32" r="3.5" fill="#ffffff"/>
                            <circle cx="48" cy="32" r="3.2" fill="#ffffff"/>
                            <circle cx="21" cy="41" r="3.2" fill="#ffffff"/>
                            <circle cx="43" cy="41" r="3.2" fill="#ffffff"/>
                            <circle cx="32" cy="46" r="3.2" fill="#ffffff"/>
                        </svg>
                        Pickleball
                    </div>
                </div>
            </div>

            <!-- STEP 2: Date & Time -->
            <div class="step-panel">
                <div class="step-header">
                    <div class="step-circle">2</div>
                    <h2 class="step-title">Select Date & Time</h2>
                </div>
                <input type="date" name="reservation_date" class="date-input" id="resDate" min="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}" required>
                <span class="time-label">Available Time Slot</span>
                <div class="time-grid" id="timeSlots">
                    <!-- JS Injected Time Slots -->
                </div>
            </div>

            <!-- STEP 3: Court -->
            <div class="step-panel">
                <div class="step-header">
                    <div class="step-circle">3</div>
                    <h2 class="step-title">Select Court</h2>
                </div>
                <div class="court-selection">
                    <div class="court-btn active" id="court1" onclick="selectCourt(1)">Court 1</div>
                    <div class="court-btn" id="court2" onclick="selectCourt(2)">Court 2</div>
                    <div class="court-btn" id="court3" onclick="selectCourt(3)">Court 3</div>
                </div>
            </div>

            <!-- STEP 4: Duration -->
            <div class="step-panel">
                <div class="step-header">
                    <div class="step-circle">4</div>
                    <div style="display: flex; flex-direction: column;">
                        <h2 class="step-title">Play Duration</h2>
                        <span class="duration-sub" id="durationSubText">₱230.00 / hr</span>
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
            <!-- STEP 5: Rental Items -->
            <div class="step-panel" id="rentalSection" style="margin-bottom: 0;">
                <div class="rental-header">
                    <div style="display: flex; align-items: center;">
                        <div class="step-circle" style="margin-right: 12px;">5</div>
                        <h2 class="step-title" style="font-size: 16px;">Rental Items <span style="color: #9ca3af; font-weight: normal; font-size: 12px;">(Optional)</span></h2>
                    </div>
                </div>
                
                <div class="rental-item" id="rentalRacket">
                    <div class="item-info">
                        <h4>Racket</h4>
                        <p>₱50.00 / pc</p>
                    </div>
                    <div class="counter">
                        <button type="button" class="counter-btn" onclick="updateRental('racket', -1)">-</button>
                        <input type="text" name="rackets" id="racketCount" class="counter-input" value="0" readonly>
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
                        <input type="text" name="shuttlecocks" id="shuttlecockCount" class="counter-input" value="0" readonly>
                        <button type="button" class="counter-btn" onclick="updateRental('shuttlecock', 1)">+</button>
                    </div>
                </div>
            </div>

            <!-- Summary Box -->
            <div class="summary-box">
                <h2 class="summary-title">Summary</h2>
                <div class="summary-sport">
                    <div id="summarySportIcon"></div>
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
                    <span id="summaryTime" style="color: #0f2b6e;">Not selected</span>
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

    // Defines the exact closing hour based on the day
    function getClosingHour(dateString) {
        const d = new Date(dateString);
        const day = d.getDay();
        if (day === 0) return 14; // Sunday closes at 2:00 PM (14:00)
        return 21;                // Monday-Saturday closes at 9:00 PM (21:00)
    }

    // Draws the clickable buttons up until 1 hour before closing
    function generateTimeSlots(dateString) {
        const d = new Date(dateString);
        const day = d.getDay();
        
        // Start times based on weekday/weekend
        let startHour = (day === 6 || day === 0) ? 7 : 8; 
        
        // The last button we want to draw is 1 hour before closing
        let endHour = (day === 0) ? 14 : 21; 

        const timeGrid = document.getElementById('timeSlots');
        timeGrid.innerHTML = '';

        for (let i = startHour; i < endHour; i++) {
            let displayHour = i > 12 ? i - 12 : (i === 0 ? 12 : i);
            let ampm = i >= 12 ? 'PM' : 'AM';
            let timeText = `${displayHour}:00 ${ampm}`;

            let div = document.createElement('div');
            div.className = 'time-slot';
            div.innerText = timeText;
            div.setAttribute('data-hour', i);
            div.onclick = function() { selectTime(this); };
            timeGrid.appendChild(div);
        }
    }

    document.getElementById('resDate').addEventListener('change', function() {
        const dateObj = new Date(this.value);
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('summaryDate').innerText = dateObj.toLocaleDateString(undefined, options);
        
        // Reset selections upon date change
        selectedTimeString = null;
        document.getElementById('summaryTime').innerText = "Not selected";
        document.getElementById('hidden_start_time').value = "";
        document.getElementById('hidden_end_time').value = "";
        document.getElementById('durationSelect').innerHTML = '<option value="1">1 Hour</option><option value="2">2 Hours</option><option value="3">3 Hours</option>';

        generateTimeSlots(this.value);
        checkAvailability();
        calculateTotal();
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
            document.getElementById('durationSubText').innerText = '₱250.00 / hr';
        } else {
            document.getElementById('rentalRacket').style.display = 'flex';
            document.getElementById('rentalShuttlecock').style.display = 'flex';
            document.getElementById('durationSubText').innerText = '₱230.00 / hr';
        }

        document.getElementById('summarySportText').innerText = sport;
        if (sport === 'Badminton') {
            const badmIcon = `<svg width="32" height="32" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><g transform="translate(6, 6) rotate(-25 26 26)"><path d="M12 10C10 18 12 28 18 36L34 36C40 28 42 18 40 10C35 12 26 12 12 10Z" fill="#e0e7ff" fill-opacity="0.35" stroke="#0033cc" stroke-width="2.5" stroke-linejoin="round"/><path d="M16 11C20 18 22 28 24 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/><path d="M36 11C32 18 30 28 28 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/><path d="M26 11L26 36" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/><path d="M14 20C20 23 32 23 38 20" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/><path d="M16 28C21 31 31 31 36 28" stroke="#0033cc" stroke-width="2" stroke-linecap="round"/><rect x="18" y="36" width="16" height="3" rx="1" fill="#0033cc"/><path d="M18 39C18 44.5 21.5 48 26 48C30.5 48 34 44.5 34 39H18Z" fill="#0033cc"/></g></svg>`;
            document.getElementById('summarySportIcon').innerHTML = badmIcon;
        } else {
            const pickIcon = `<svg width="32" height="32" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="32" cy="32" r="24" fill="#f97316" stroke="#ea580c" stroke-width="2"/><circle cx="32" cy="18" r="3.2" fill="#ffffff"/><circle cx="21" cy="23" r="3.2" fill="#ffffff"/><circle cx="43" cy="23" r="3.2" fill="#ffffff"/><circle cx="16" cy="32" r="3.2" fill="#ffffff"/><circle cx="32" cy="32" r="3.5" fill="#ffffff"/><circle cx="48" cy="32" r="3.2" fill="#ffffff"/><circle cx="21" cy="41" r="3.2" fill="#ffffff"/><circle cx="43" cy="41" r="3.2" fill="#ffffff"/><circle cx="32" cy="46" r="3.2" fill="#ffffff"/></svg>`;
            document.getElementById('summarySportIcon').innerHTML = pickIcon;
        }

        document.getElementById('court1').classList.remove('disabled');
        document.getElementById('court3').classList.remove('disabled');
       
        updateCourtDisplay();
    }

    function selectCourt(courtNum) {
        currentCourtNum = courtNum;
        updateCourtDisplay();
    }

    function updateCourtDisplay() {
        document.querySelectorAll('.court-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('court' + currentCourtNum).classList.add('active');
       
        document.getElementById('summaryCourt').innerText = 'Court ' + currentCourtNum;
        document.getElementById('hidden_court_id').value = currentCourtNum;

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
        let durationSelect = document.getElementById('durationSelect');
        let durationStr = durationSelect ? durationSelect.value : 1;
        let durationHours = parseInt(durationStr) || 1;
        
        let courtPrice = currentSport === 'Pickleball' ? 250 : 230;
       
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
        
        let startHour = parseInt(element.getAttribute('data-hour'));
        let closingHour = getClosingHour(document.getElementById('resDate').value);
        
        // Limits the dropdown to the exact hours remaining before close
        let maxAvailableHours = closingHour - startHour;

        let durationSelect = document.getElementById('durationSelect');
        let currentSelectedDuration = parseInt(durationSelect.value) || 1;
        durationSelect.innerHTML = '';
        
        let optionLimit = Math.min(3, maxAvailableHours);
        for(let i = 1; i <= optionLimit; i++) {
            let option = document.createElement('option');
            option.value = i;
            option.text = i + (i === 1 ? ' Hour' : ' Hours');
            if(i === currentSelectedDuration && i <= optionLimit) {
                option.selected = true;
            }
            durationSelect.appendChild(option);
        }

        let duration = parseInt(durationSelect.value) || 1;
        let endHour = startHour + duration;
       
        let endSuffix = endHour >= 12 && endHour < 24 ? 'PM' : 'AM';
        let endDisplayHour = endHour > 12 ? endHour - 12 : (endHour === 0 ? 12 : endHour);
       
        document.getElementById('summaryTime').innerText = timeText + " - " + endDisplayHour + ":00 " + endSuffix;

        let startStr = startHour.toString().padStart(2, '0') + ':00:00';
        let endStr = endHour.toString().padStart(2, '0') + ':00:00';

        document.getElementById('hidden_start_time').value = startStr;
        document.getElementById('hidden_end_time').value = endStr;
        
        calculateTotal();
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
                let bookedSlots = data.booked_slots || [];

                document.querySelectorAll('.time-slot').forEach(slot => {
                    let timeText = slot.innerText.trim();
                    slot.classList.remove('booked');
                   
                    if(bookedSlots.includes(timeText)) {
                        slot.classList.add('booked');
                        slot.classList.remove('selected');
                       
                        if(selectedTimeString === timeText) {
                            selectedTimeString = null;
                            document.getElementById('summaryTime').innerText = "Not selected";
                            document.getElementById('hidden_start_time').value = "";
                            document.getElementById('hidden_end_time').value = "";
                        }
                    }
                });
                markPastSlots();
            });
    }

    document.getElementById('durationSelect').addEventListener('change', function() {
        calculateTotal();
        if(selectedTimeString) {
            let selectedEl = document.querySelector('.time-slot.selected');
            if(selectedEl) {
                let startHour = parseInt(selectedEl.getAttribute('data-hour'));
                let duration = parseInt(this.value);
                let endHour = startHour + duration;

                let endSuffix = endHour >= 12 && endHour < 24 ? 'PM' : 'AM';
                let endDisplayHour = endHour > 12 ? endHour - 12 : (endHour === 0 ? 12 : endHour);

                document.getElementById('summaryTime').innerText = selectedTimeString + " - " + endDisplayHour + ":00 " + endSuffix;

                let startStr = startHour.toString().padStart(2, '0') + ':00:00';
                let endStr = endHour.toString().padStart(2, '0') + ':00:00';

                document.getElementById('hidden_start_time').value = startStr;
                document.getElementById('hidden_end_time').value = endStr;
            }
        }
    });

    function markPastSlots() {
        let selectedDate = document.getElementById('resDate').value;
        let now = new Date();
        let manilaTime = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
        let todayStr = manilaTime.getFullYear() + '-' + String(manilaTime.getMonth() + 1).padStart(2, '0') + '-' + String(manilaTime.getDate()).padStart(2, '0');
       
        if (selectedDate !== todayStr) return;
       
        let currentHour = manilaTime.getHours();
       
        document.querySelectorAll('.time-slot').forEach(slot => {
            let slotHour = parseInt(slot.getAttribute('data-hour'));
           
            if (slotHour <= currentHour) {
                slot.classList.add('booked');
                slot.classList.remove('selected');
               
                let timeText = slot.innerText.trim();
                if (selectedTimeString === timeText) {
                    selectedTimeString = null;
                    document.getElementById('summaryTime').innerText = "Not selected";
                    document.getElementById('hidden_start_time').value = "";
                    document.getElementById('hidden_end_time').value = "";
                }
            }
        });
    }
   
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const sportParam = urlParams.get('sport');
        
        if (sportParam && (sportParam === 'Badminton' || sportParam === 'Pickleball')) {
            selectSport(sportParam);
        } else {
            selectSport('Badminton');
        }
        
        const initialDate = document.getElementById('resDate').value;
        if(initialDate) {
            generateTimeSlots(initialDate);
            checkAvailability();
        }
    });
</script>
@endsection