<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Reservation | Court Reserve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #0033cc;
            --light-blue: #e6edff;
            --text-dark: #333;
            --text-gray: #777;
            --bg-gray: #f4f7f6;
            --danger-red: #dc3545;
        }

        body { font-family: 'Segoe UI', sans-serif; margin: 0; background-color: var(--bg-gray); display: flex; height: 100vh; overflow: hidden; }

        /* Sidebar */
        .sidebar { width: 250px; background-color: #f8f9fa; border-right: 1px solid #ddd; display: flex; flex-direction: column; flex-shrink: 0; }
        .logo-container { padding: 20px; text-align: center; border-bottom: 1px solid #ddd; }
        .nav-menu { list-style: none; padding: 0; margin: 20px 0; flex-grow: 1; }
        .nav-menu li { margin-bottom: 5px; }
        .nav-menu a { display: flex; align-items: center; padding: 15px 30px; color: var(--primary-blue); text-decoration: none; font-size: 16px; font-weight: 500; transition: 0.2s; }
        .nav-menu a i { margin-right: 15px; font-size: 20px; width: 20px; text-align: center; }
        .nav-menu a:hover, .nav-menu a.active { background-color: var(--light-blue); border-left: 4px solid var(--primary-blue); }

        /* Main Content */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 0 40px 40px 40px; box-sizing: border-box; }
        .top-header { padding: 20px 0; display: flex; justify-content: space-between; align-items: center; }
        .top-header h1 { color: var(--primary-blue); margin: 0; font-size: 28px; }

        /* Error Banner */
        .alert-error { background-color: #f8d7da; color: var(--danger-red); padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c6cb; }

        /* Reservation Layout */
        .booking-grid { display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; }
        
        .step-panel { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eaeaea; margin-bottom: 20px; }
        .step-header { display: flex; align-items: center; margin-bottom: 20px; }
        .step-circle { background-color: var(--primary-blue); color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; font-size: 14px; margin-right: 15px; flex-shrink: 0; }
        .step-title { color: var(--primary-blue); font-size: 18px; font-weight: 600; margin: 0; }
        
        /* Form Elements */
        .form-control { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; box-sizing: border-box; color: var(--text-dark); margin-bottom: 15px; }
        
        /* Sport Toggle Buttons */
        .sport-toggle { display: flex; gap: 15px; margin-bottom: 25px; }
        .sport-btn { flex: 1; padding: 15px; border: 2px solid #ddd; border-radius: 10px; background: white; color: var(--text-gray); font-weight: bold; font-size: 16px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; transition: 0.2s; }
        .sport-btn.active { border-color: var(--primary-blue); color: var(--primary-blue); background: var(--light-blue); }

        .time-slots { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
        .time-slot { padding: 10px; border: 1px solid #ddd; border-radius: 6px; background: white; color: var(--primary-blue); cursor: pointer; font-size: 13px; font-weight: 500; text-align: center; }
        .time-slot.active { background: var(--primary-blue); color: white; border-color: var(--primary-blue); }
        .time-slot.booked { background: #f8f9fa; color: #ccc; border-color: #eee; cursor: not-allowed; text-decoration: line-through; pointer-events: none; }
        
        .courts-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .court-btn { padding: 12px; border: 2px solid #ddd; border-radius: 8px; background: white; color: var(--primary-blue); cursor: pointer; font-weight: 600; font-size: 14px; }
        .court-btn.active { border-color: var(--primary-blue); color: var(--primary-blue); background: var(--light-blue); }
        .court-btn:disabled { background: #f8f9fa; color: #ccc; border-color: #eee; cursor: not-allowed; text-decoration: line-through; } 

        /* Rentals */
        .rental-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .rental-info h4 { margin: 0; color: var(--primary-blue); font-size: 16px; }
        .rental-info p { margin: 2px 0 0 0; color: #999; font-size: 12px; }
        .counter { display: flex; align-items: center; border: 1px solid #ddd; border-radius: 6px; }
        .counter button { border: none; background: white; width: 30px; height: 30px; cursor: pointer; color: var(--primary-blue); font-weight: bold; border-radius: 6px; }
        .counter input { width: 40px; text-align: center; border: none; border-left: 1px solid #ddd; border-right: 1px solid #ddd; pointer-events: none; }

        /* Summary Card */
        .summary-card { background: white; border-radius: 12px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eaeaea; }
        .summary-card h2 { text-align: center; color: var(--primary-blue); margin-top: 0; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 14px; }
        .summary-label { color: var(--text-gray); }
        .summary-value { color: var(--primary-blue); font-weight: 600; text-align: right; }
        
        .total-row { border-top: 2px dashed #eee; padding-top: 20px; margin-top: 20px; display: flex; justify-content: space-between; align-items: center; }
        .total-amount { font-size: 24px; color: var(--primary-blue); font-weight: bold; }
        
        .btn-next { background: var(--primary-blue); color: white; border: none; padding: 15px; border-radius: 8px; width: 100%; font-size: 18px; font-weight: bold; margin-top: 25px; cursor: pointer; display: block; text-align: center; box-sizing: border-box; }
        .btn-next:hover { background-color: #002299; }

        /* Responsive Media Queries */
        @media (max-width: 1100px) { .booking-grid { grid-template-columns: 1fr; } }
        @media (max-width: 768px) {
            body { flex-direction: column; overflow-x: hidden; overflow-y: auto; }
            .sport-buttons { width: 100%; justify-content: center; gap: 15px; }
            .sport-btn { flex: 1; padding: 15px 10px; }
            .reservations-grid { display: flex !important; flex-direction: column !important; gap: 20px !important; }
            .panel, .card { width: 100% !important; box-sizing: border-box !important; }
            .sidebar { position: fixed !important; bottom: 0 !important; left: 0 !important; width: 100% !important; height: 70px !important; flex-direction: row !important; border-right: none !important; border-top: 1px solid #ddd !important; z-index: 1000 !important; padding: 0 !important; box-shadow: 0 -2px 10px rgba(0,0,0,0.05) !important; background-color: white !important; }
            .logo-container, .logout-container { display: none !important; }
            .nav-menu { display: flex !important; flex-direction: row !important; margin: 0 !important; width: 100% !important; justify-content: space-around !important; align-items: center !important; }
            .nav-menu a { padding: 10px !important; flex-direction: column !important; font-size: 11px !important; border-left: none !important; color: #777 !important; }
            .nav-menu a i { margin-right: 0 !important; margin-bottom: 4px !important; font-size: 20px !important; }
            .nav-menu a:hover, .nav-menu a.active { border-left: none !important; background: transparent !important; color: var(--primary-blue) !important; }
            .main-content { padding: 20px !important; padding-bottom: 90px !important; }
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-width: 150px;">
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
            <h1>New Reservation</h1>
            <i class="fa-regular fa-bell" style="font-size: 24px; color: var(--primary-blue);"></i>
        </header>

        @if($errors->any())
            <div class="alert-error">
                <strong><i class="fa-solid fa-circle-exclamation"></i> Booking Failed:</strong> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('reservations.store') }}" method="POST" id="reservationForm" onsubmit="return validateForm()">
            @csrf

            <!-- Hidden Inputs -->
            <input type="hidden" name="court_id" id="hidden_court_id" value="1">
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
                        
                        <input type="date" name="reservation_date" class="form-control" id="resDate" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required>
                        
                        <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 10px;">Available Time Slot</p>
                        
                        <!-- FIXED: Hourly time slots from 8 AM to 9 PM -->
                        <div class="time-slots" id="timeSlots">
                            <div class="time-slot" onclick="selectTime(this, '8:00 AM')">8:00 AM</div>
                            <div class="time-slot" onclick="selectTime(this, '9:00 AM')">9:00 AM</div>
                            <div class="time-slot" onclick="selectTime(this, '10:00 AM')">10:00 AM</div>
                            <div class="time-slot" onclick="selectTime(this, '11:00 AM')">11:00 AM</div>
                            <div class="time-slot" onclick="selectTime(this, '12:00 PM')">12:00 PM</div>
                            <div class="time-slot" onclick="selectTime(this, '1:00 PM')">1:00 PM</div>
                            <div class="time-slot" onclick="selectTime(this, '2:00 PM')">2:00 PM</div>
                            <div class="time-slot" onclick="selectTime(this, '3:00 PM')">3:00 PM</div>
                            <div class="time-slot" onclick="selectTime(this, '4:00 PM')">4:00 PM</div>
                            <div class="time-slot" onclick="selectTime(this, '5:00 PM')">5:00 PM</div>
                            <div class="time-slot" onclick="selectTime(this, '6:00 PM')">6:00 PM</div>
                            <div class="time-slot" onclick="selectTime(this, '7:00 PM')">7:00 PM</div>
                            <div class="time-slot" onclick="selectTime(this, '8:00 PM')">8:00 PM</div>
                            <div class="time-slot" onclick="selectTime(this, '9:00 PM')">9:00 PM</div>
                        </div>
                    </div>

                    <div class="step-panel">
                        <div class="step-header">
                            <div class="step-circle">2</div>
                            <h2 class="step-title">Select Court</h2>
                        </div>
                        
                        <!-- FIXED: Court 1 is set to active by default -->
                        <div class="courts-grid">
                            <button type="button" class="court-btn active" id="court-1" onclick="selectCourt(1)">Court 1</button>
                            <button type="button" class="court-btn" id="court-2" onclick="selectCourt(2)">Court 2</button>
                            <button type="button" class="court-btn" id="court-3" onclick="selectCourt(3)">Court 3</button>
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
                        <select class="form-control" name="duration" id="durationSelect" style="margin-bottom: 0;">
                            <option value="1">1 Hour</option>
                            <option value="2">2 Hours</option>
                            <option value="3">3 Hours</option>
                        </select>
                    </div>
                </div>

                <div>
                    <div class="step-panel">
                        <div class="step-header">
                            <div class="step-circle">4</div>
                            <h2 class="step-title">Rental Items <span style="color: var(--text-gray); font-size: 13px; font-weight: normal;">(Optional)</span></h2>
                        </div>
                        
                        <div class="rental-item">
                            <div class="rental-info">
                                <h4>Racket</h4>
                                <p>₱50.00 / pc</p>
                            </div>
                            <div class="counter">
                                <button type="button" onclick="updateRental('racket', -1)">-</button>
                                <input type="text" name="rackets" id="racketCount" value="0" readonly>
                                <button type="button" onclick="updateRental('racket', 1)">+</button>
                            </div>
                        </div>

                        <div class="rental-item">
                            <div class="rental-info">
                                <h4>Shuttlecock</h4>
                                <p>₱50.00 / pc</p>
                            </div>
                            <div class="counter">
                                <button type="button" onclick="updateRental('shuttlecock', -1)">-</button>
                                <input type="text" name="shuttlecocks" id="shuttlecockCount" value="0" readonly>
                                <button type="button" onclick="updateRental('shuttlecock', 1)">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="summary-card">
                        <h2>Summary</h2>
                        
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px; border-bottom: 1px solid #eee; padding-bottom: 15px;">
                            <div id="summarySportIcon">
                                <img src="{{ asset('images/shuttlecock.png') }}" alt="Badminton Icon" width="30">
                            </div>
                            <h3 id="summarySportText" style="color: var(--primary-blue); margin: 0; font-size: 22px;">Badminton</h3>
                        </div>

                        <div class="summary-row">
                            <span class="summary-label">Court</span>
                            <span class="summary-value" id="summaryCourt" style="color: var(--primary-blue);">Court 1</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Date</span>
                            <span class="summary-value" id="summaryDate">{{ date('F j, Y') }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Time</span>
                            <span class="summary-value" id="summaryTime" style="color: var(--danger-red);">Not selected</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Duration</span>
                            <span class="summary-value" id="summaryDuration">1 Hour</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Rental</span>
                            <span class="summary-value" id="summaryRental">None</span>
                        </div>

                        <div class="total-row">
                            <span style="color: var(--primary-blue); font-weight: bold; font-size: 16px;">Total Amount</span>
                            <span class="total-amount" id="summaryTotal">₱ 230.00</span>
                        </div>

                        <button type="submit" class="btn-next">Request Reservation</button>
                    </div>
                </div>

            </div>
        </form>
    </main>

    <script>
        let currentSport = 'Badminton';
        let currentCourtNum = 1; // Default to Court 1
        let selectedTimeString = null;

        document.getElementById('resDate').addEventListener('change', function() {
            const dateObj = new Date(this.value);
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('summaryDate').innerText = dateObj.toLocaleDateString(undefined, options);
            checkAvailability(); 
        });

        function selectSport(sport) {
            currentSport = sport;
            
            document.getElementById('btn-badminton').classList.remove('active');
            document.getElementById('btn-pickleball').classList.remove('active');
            document.getElementById('btn-' + sport.toLowerCase()).classList.add('active');

            document.getElementById('summarySportText').innerText = sport;
            
            if(sport === 'Badminton') {
                document.getElementById('summarySportIcon').innerHTML = `<img src="{{ asset('images/shuttlecock.png') }}" alt="Badminton Icon" width="30">`;
            } else {
                document.getElementById('summarySportIcon').innerHTML = `<i class="fa-solid fa-table-tennis-paddle-ball" style="font-size: 30px; color: #f39c12;"></i>`;
            }

            updateCourtDisplay();
        }

        function updateCourtDisplay() {
            if(currentCourtNum) {
                document.getElementById('summaryCourt').innerText = currentSport + " Court " + currentCourtNum;
                document.getElementById('summaryCourt').style.color = "var(--primary-blue)";
                document.getElementById('hidden_court_id').value = currentCourtNum;
            }
        }

        function selectTime(element, timeString) {
            document.querySelectorAll('.time-slot').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            
            selectedTimeString = timeString;
            document.getElementById('summaryTime').innerText = timeString;
            document.getElementById('summaryTime').style.color = "var(--primary-blue)";
            
            calculateTotal();
        }

        function selectCourt(courtNum) {
            // FIXED: Safely grab the button that was clicked to prevent Javascript errors
            let target = event ? event.currentTarget : document.getElementById('court-' + courtNum);
            
            if (target && !target.disabled) {
                document.querySelectorAll('.court-btn').forEach(el => el.classList.remove('active'));
                target.classList.add('active');
                currentCourtNum = courtNum;
                updateCourtDisplay();
                checkAvailability(); 
            }
        }

        const courtRate = 230;
        const itemRate = 50;

        function updateRental(item, change) {
            let input = document.getElementById(item + 'Count');
            let newValue = parseInt(input.value) + change;
            if (newValue >= 0) {
                input.value = newValue;
                calculateTotal();
            }
        }

        function formatToMilitaryTime(timeStr, durationHours) {
            let [time, modifier] = timeStr.split(' ');
            let [hours, minutes] = time.split(':');
            hours = parseInt(hours, 10);
            
            if (hours === 12 && modifier === 'AM') hours = 0;
            if (hours < 12 && modifier === 'PM') hours += 12;
            
            let startFormatted = `${hours.toString().padStart(2, '0')}:${minutes}`;
            let endHours = hours + parseInt(durationHours, 10);
            let endFormatted = `${endHours.toString().padStart(2, '0')}:${minutes}`;
            
            document.getElementById('hidden_start_time').value = startFormatted;
            document.getElementById('hidden_end_time').value = endFormatted;
        }

        function calculateTotal() {
            let hours = parseInt(document.getElementById('durationSelect').value);
            let rackets = parseInt(document.getElementById('racketCount').value);
            let shuttlecocks = parseInt(document.getElementById('shuttlecockCount').value);
            
            let grandTotal = (hours * courtRate) + ((rackets + shuttlecocks) * itemRate);

            document.getElementById('summaryDuration').innerText = hours + (hours > 1 ? " Hours" : " Hour");
            
            let rentalText = [];
            if(rackets > 0) rentalText.push(rackets + (rackets > 1 ? " Rackets" : " Racket"));
            if(shuttlecocks > 0) rentalText.push(shuttlecocks + " Shuttlecock");
            document.getElementById('summaryRental').innerText = rentalText.length > 0 ? rentalText.join(", ") : "None";
            
            document.getElementById('summaryTotal').innerText = "₱ " + grandTotal.toFixed(2);
            
            if(selectedTimeString) {
                formatToMilitaryTime(selectedTimeString, hours);
            }
        }

        function validateForm() {
            if (!selectedTimeString) {
                alert("Please select a Time Slot before submitting.");
                return false;
            }
            if (!document.getElementById('hidden_court_id').value) {
                alert("Please select a Court before submitting.");
                return false;
            }
            return true;
        }

        function checkAvailability() {
            let date = document.getElementById('resDate').value;
            let courtId = document.getElementById('hidden_court_id').value || currentCourtNum;

            if(!date || !courtId) return;

            fetch(`/api/check-availability?date=${date}&court_id=${courtId}`)
                .then(response => response.json())
                .then(data => {
                    let bookedSlots = data.booked_slots;
                    
                    document.querySelectorAll('.time-slot').forEach(slot => {
                        let timeText = slot.innerText.trim();
                        slot.classList.remove('booked');
                        
                        if (bookedSlots.includes(timeText)) {
                            slot.classList.add('booked');
                            slot.classList.remove('active'); 
                            
                            if(selectedTimeString === timeText) {
                                selectedTimeString = null;
                                document.getElementById('summaryTime').innerText = "Not selected";
                                document.getElementById('summaryTime').style.color = "var(--danger-red)";
                                document.getElementById('hidden_start_time').value = "";
                                document.getElementById('hidden_end_time').value = "";
                            }
                        }
                    });
                });
        }

        document.getElementById('durationSelect').addEventListener('change', calculateTotal);
        
        // Initialize everything on load
        updateCourtDisplay(); // THIS forces the Summary box to show Court 1 immediately
        calculateTotal();
        checkAvailability();
    </script>
</body>
</html>