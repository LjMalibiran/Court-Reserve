@extends('layouts.app')

@section('title', 'New Reservation | Court Reserve')
@section('header_title', 'New Reservation')

@section('styles')
<style>
    /* Step Panels */
    .booking-grid { display: grid; grid-template-columns: 2fr 1.2fr; gap: 25px; margin-top: 10px; }
    .step-panel { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eaeaea; margin-bottom: 25px; }
    .step-header { display: flex; align-items: center; margin-bottom: 20px; }
    .step-circle { background: var(--primary-blue); color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; margin-right: 15px; }
    .step-title { margin: 0; color: var(--primary-blue); font-size: 18px; }

    /* Sport Selection */
    .sport-toggle { display: flex; gap: 15px; margin-bottom: 25px; }
    .sport-btn { flex: 1; padding: 15px; background: white; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; font-weight: bold; color: var(--text-gray); cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; transition: 0.2s; }
    .sport-btn.active { background: var(--light-blue); border-color: var(--primary-blue); color: var(--primary-blue); }

    /* Court Selection (Badminton uses dots, Pickleball uses squares) */
    .court-selection { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 20px; }
    .court-btn { padding: 15px 5px; border: 1px solid #ddd; border-radius: 8px; text-align: center; cursor: pointer; transition: 0.2s; background: white; display: flex; flex-direction: column; align-items: center; gap: 10px; }
    
    .court-btn .court-icon-badminton { width: 15px; height: 15px; background: #ddd; border-radius: 50%; transition: 0.2s; }
    .court-btn .court-icon-pickleball { width: 15px; height: 15px; background: #ddd; border-radius: 3px; transition: 0.2s; display: none; }
    
    .court-btn.active { border-color: var(--primary-blue); background: var(--light-blue); }
    .court-btn.active .court-icon-badminton { background: var(--primary-blue); }
    .court-btn.active .court-icon-pickleball { background: #f39c12; }
    .court-name { font-size: 13px; font-weight: bold; color: var(--text-dark); }
    
    /* Time Selection */
    .date-input { width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-family: inherit; margin-bottom: 20px; outline: none; }
    .time-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; }
    .time-slot { padding: 10px; border: 1px solid #ddd; border-radius: 6px; text-align: center; font-size: 13px; cursor: pointer; transition: 0.2s; color: var(--text-dark); }
    .time-slot.selected { background: var(--primary-blue); color: white; border-color: var(--primary-blue); }
    
    /* BOOKED TIME SLOT STYLING */
    .time-slot.booked { background: #f0f0f0; color: #aaa; text-decoration: line-through; cursor: not-allowed; border-color: #eee; }
    .time-slot.booked:hover { transform: none; }

    /* Right Panel: Rentals & Summary */
    .rental-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid #eee; }
    .rental-item:last-child { border-bottom: none; }
    .item-info h4 { margin: 0 0 5px 0; color: var(--text-dark); font-size: 15px; }
    .item-info p { margin: 0; color: var(--text-gray); font-size: 12px; }
    
    .counter { display: flex; align-items: center; gap: 15px; }
    .counter-btn { width: 30px; height: 30px; border-radius: 50%; border: 1px solid #ddd; background: white; display: flex; justify-content: center; align-items: center; cursor: pointer; color: var(--primary-blue); font-weight: bold; }
    .counter-btn:hover { background: var(--light-blue); }
    
    /* Summary Box */
    .summary-box { background: var(--primary-blue); border-radius: 12px; padding: 25px; color: white; margin-top: 25px; }
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 15px; }
    .summary-total { display: flex; justify-content: space-between; margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.2); font-size: 18px; font-weight: bold; }
    .btn-proceed { background: white; color: var(--primary-blue); border: none; width: 100%; padding: 15px; border-radius: 8px; font-size: 16px; font-weight: bold; margin-top: 20px; cursor: pointer; }
    .btn-proceed:hover { background: #f0f0f0; }

    .alert-error { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }

    @media (max-width: 768px) {
        .booking-grid { grid-template-columns: 1fr; }
        .court-selection { grid-template-columns: repeat(2, 1fr); }
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
        <div>
            <div class="sport-toggle">
                <button type="button" class="sport-btn active" id="btn-badminton" onclick="selectSport('Badminton')">
                    <img src="{{ asset('images/shuttlecock.png') }}" alt="Badminton Icon" width="24" style="margin-right: 8px;"> Badminton
                </button>
                <button type="button" class="sport-btn" id="btn-pickleball" onclick="selectSport('Pickleball')">
                    <i class="fa-solid fa-table-tennis-paddle-ball" style="font-size: 24px; color: #f39c12;"></i> Pickleball
                </button>
            </div>

            <div class="step-panel">
                <div class="step-header">
                    <div class="step-circle">1</div>
                    <h2 class="step-title">Select Date & Time</h2>
                </div>
                
                <input type="date" name="reservation_date" class="date-input" id="resDate" min="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}" value="{{ \Carbon\Carbon::now('Asia/Manila')->format('Y-m-d') }}" required>
                
                <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 10px;">Available Time Slot</p>
                
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
                    <div class="court-btn active" id="court1" onclick="selectCourt(1)">
                        <div class="court-icon-badminton"></div>
                        <div class="court-icon-pickleball"></div>
                        <span class="court-name">Court 1</span>
                    </div>
                    <div class="court-btn" id="court2" onclick="selectCourt(2)">
                        <div class="court-icon-badminton"></div>
                        <div class="court-icon-pickleball"></div>
                        <span class="court-name">Court 2</span>
                    </div>
                    <div class="court-btn" id="court3" onclick="selectCourt(3)">
                        <div class="court-icon-badminton"></div>
                        <div class="court-icon-pickleball"></div>
                        <span class="court-name">Court 3</span>
                    </div>
                </div>
            </div>

            <div class="step-panel">
                <div class="step-header">
                    <div class="step-circle">3</div>
                    <div style="display: flex; flex-direction: column;">
                        <h2 class="step-title">Play Duration</h2>
                        <span style="font-size: 11px; color: var(--text-gray);">₱230.00 / hr</span>
                    </div>
                </div>
                <select class="date-input" name="duration" id="durationSelect" style="margin-bottom: 0;">
                    <option value="1">1 Hour</option>
                    <option value="2">2 Hours</option>
                    <option value="3">3 Hours</option>
                </select>
            </div>
        </div>

        <div>
            <div class="step-panel" id="rentalSection">
                <div class="step-header">
                    <div class="step-circle">4</div>
                    <h2 class="step-title">Rental Items <span style="color: var(--text-gray); font-size: 13px; font-weight: normal;">(Optional)</span></h2>
                </div>
                
                <div class="rental-item" id="rentalRacket">
                    <div class="item-info">
                        <h4>Racket</h4>
                        <p>₱50.00 / pc</p>
                    </div>
                    <div class="counter">
                        <button type="button" class="counter-btn" onclick="updateRental('racket', -1)">-</button>
                        <input type="text" name="rackets" id="racketCount" value="0" readonly style="width: 20px; text-align: center; border: none; background: transparent;">
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
                        <input type="text" name="shuttlecocks" id="shuttlecockCount" value="0" readonly style="width: 20px; text-align: center; border: none; background: transparent;">
                        <button type="button" class="counter-btn" onclick="updateRental('shuttlecock', 1)">+</button>
                    </div>
                </div>
            </div>

            <div class="summary-box">
                <h2 style="margin: 0 0 20px 0; border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 15px; text-align: center;">Summary</h2>
                
                <div style="display: flex; align-items: center; justify-content: center; gap: 15px; margin-bottom: 25px;">
                    <div id="summarySportIcon">
                        <img src="{{ asset('images/shuttlecock.png') }}" alt="Badminton" width="30" style="filter: brightness(0) invert(1);">
                    </div>
                    <h3 id="summarySportText" style="margin: 0; font-size: 22px;">Badminton</h3>
                </div>

                <div class="summary-row">
                    <span>Court</span>
                    <span id="summaryCourt" style="font-weight: bold;">Court 1</span>
                </div>
                <div class="summary-row">
                    <span>Date</span>
                    <span id="summaryDate">{{ date('F j, Y') }}</span>
                </div>
                <div class="summary-row">
                    <span>Time</span>
                    <span id="summaryTime" style="font-weight: bold; color: #ffcccc;">Not selected</span>
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

                <button type="submit" class="btn-proceed">Request Reservation</button>
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
            document.getElementById('summarySportIcon').innerHTML = `<img src="{{ asset('images/shuttlecock.png') }}" width="30" style="filter: brightness(0) invert(1);">`;
        } else {
            document.getElementById('summarySportIcon').innerHTML = `<i class="fa-solid fa-table-tennis-paddle-ball" style="font-size: 26px;"></i>`;
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