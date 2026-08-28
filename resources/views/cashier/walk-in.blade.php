<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Walk-In | Batangas Badminton</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --primary-blue: #1557c0;
            --dark-blue: #002277;
            --bg-color: #f4f6f9;
            --card-bg: #ffffff;
            --text-main: #333333;
            --text-muted: #777777;
            --border-color: #e5e7eb;
            --focus-blue: #3b82f6;
        }
        
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); display: flex; height: 100vh; overflow: hidden; }
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 30px; }
        
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .top-header h1 { margin: 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; display: flex; align-items: center; gap: 15px; }
        .subtitle { font-size: 14px; color: var(--text-muted); font-weight: 400; margin-top: 5px; }
        .header-right { display: flex; align-items: center; gap: 20px; color: var(--dark-blue); font-weight: 500; font-size: 14px; }
        
        .controls-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .filter-tabs { display: flex; gap: 10px; }
        .tab-btn { background-color: #f0f2f5; color: var(--text-main); border: none; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 500; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.2s; }
        .tab-btn:hover { background-color: #e4e6e9; }
        .tab-btn.active { background-color: var(--dark-blue); color: white; }
        
        .search-box { display: flex; align-items: center; gap: 10px; }
        .btn-primary { background: #0033ff; color: white; border: none; padding: 10px 20px; border-radius: 6px; font-size: 14px; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .btn-primary:hover { background: #0022cc; }
        .search-input-wrapper { position: relative; }
        .search-input-wrapper i { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; }
        .search-input-wrapper input { padding: 10px 35px 10px 15px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 14px; width: 220px; }
        .btn-export { background: #fff; border: 1px solid var(--border-color); padding: 9px 12px; border-radius: 6px; color: var(--dark-blue); cursor: pointer; }

        .table-container { background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color); flex-grow: 1; display: flex; flex-direction: column; overflow: visible; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 16px 20px; text-align: left; font-size: 14px; }
        th { background-color: #f8fafc; color: var(--dark-blue); font-weight: 600; border-bottom: 2px solid var(--border-color); }
        td { border-bottom: 1px solid #f0f0f0; color: #4b5563; }
        .td-id { color: var(--primary-blue); font-weight: 500; }

        .dropdown-container { position: relative; display: inline-block; text-align: center; width: 100%;}
        .btn-dots { background: none; border: none; font-size: 18px; color: var(--text-muted); cursor: pointer; padding: 5px 10px; }
        .action-menu { display: none; position: absolute; right: 20px; top: 30px; background: white; min-width: 140px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 8px; z-index: 100; border: 1px solid var(--border-color); overflow: hidden; }
        .action-menu.show { display: block; }
        .action-menu button { color: #333; padding: 10px 15px; text-decoration: none; display: flex; align-items: center; gap: 10px; font-size: 13px; width: 100%; border: none; background: none; text-align: left; cursor: pointer; }
        .action-menu button:hover { background-color: #f8fafc; }

        .empty-state { text-align: center !important; padding: 40px !important; color: var(--text-muted) !important; font-style: italic; }
        .pagination { display: flex; justify-content: flex-end; padding: 20px; gap: 10px; margin-top: auto; }
        
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 6px; font-weight: bold; background-color: #d1fae5; color: #059669; border: 1px solid #34d399; }

        #formView { display: none; }
        .form-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: start; }
        .form-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 20px; }
        .form-card h3 { margin: 0 0 5px 0; color: var(--dark-blue); font-size: 18px; font-weight: 600; }
        .form-card p.desc { margin: 0 0 20px 0; font-size: 12px; color: var(--text-muted); }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-main); margin-bottom: 8px; }
        .input-group label span { color: #dc2626; }
        .input-control { width: 100%; padding: 10px 15px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 14px; color: #333; box-sizing: border-box; }
        .input-control:focus { outline: 1px solid var(--focus-blue); border-color: var(--focus-blue); }
        .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; }

        .time-slots { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin-bottom: 20px; }
        .time-slot { border: 1px solid var(--border-color); background: #fff; padding: 12px 10px; text-align: center; border-radius: 6px; font-size: 13px; font-weight: 600; cursor: pointer; color: var(--primary-blue); transition: 0.2s; user-select: none; }
        .time-slot:hover:not(.booked) { border-color: var(--focus-blue); }
        .time-slot.selected { background: #0033ff !important; color: white !important; border-color: #0033ff !important; }
        
        .time-slot.booked { background-color: #f8fafc !important; color: #cbd5e1 !important; border-color: #f1f5f9 !important; cursor: not-allowed !important; text-decoration: line-through !important; pointer-events: none !important; }

        .rental-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .rental-info h4 { margin: 0; font-size: 14px; color: var(--text-main); }
        .rental-info p { margin: 0; font-size: 11px; color: var(--text-muted); }
        .counter { display: flex; align-items: center; border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden; }
        .counter button { background: #f8fafc; border: none; padding: 5px 12px; cursor: pointer; color: var(--text-main); }
        .counter input { width: 40px; text-align: center; border: none; border-left: 1px solid var(--border-color); border-right: 1px solid var(--border-color); font-size: 13px; padding: 5px 0; }

        .summary-header { display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 15px; }
        .summary-header h2 { margin: 0; font-size: 18px; color: var(--dark-blue); }
        .summary-table { width: 100%; font-size: 13px; }
        .summary-table td { padding: 8px 0; border: none; }
        .summary-table td:first-child { color: var(--text-muted); width: 40%; }
        .summary-table td:last-child { font-weight: 500; color: var(--text-main); }
        .total-row { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); margin-top: 15px; padding-top: 15px; }
        .total-row span { font-weight: 600; color: var(--text-main); }
        .total-row h3 { margin: 0; font-size: 20px; color: var(--dark-blue); }

        .form-actions { display: flex; justify-content: flex-end; gap: 15px; margin-top: 10px; }
        .btn-cancel { background: #fff; border: 1px solid var(--border-color); color: var(--text-main); padding: 12px 30px; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .btn-save { background: #0033ff; color: white; border: none; padding: 12px 40px; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .d-none { display: none !important; }
    </style>
</head>
<body>

    @if(Request::is('cashier*'))
        @include('cashier.sidebar')
    @else
        @include('admin.sidebar')
    @endif

    <main class="main-content">
        
        <header class="top-header">
            <div>
                <h1>Walk - In</h1>
                <div id="formSubtitle" class="subtitle d-none">New Walk - In Reservation</div>
            </div>
            <div class="header-right">
                <span>{{ now()->timezone('Asia/Manila')->format('l, F j, Y') }}</span>
                <i class="fa-regular fa-bell"></i>
            </div>
        </header>

        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <!-- LIST VIEW -->
        <div id="listView">
            <div class="controls-bar">
                <div class="filter-tabs">
                    <button class="tab-btn active" data-filter="all" onclick="filterWalkIns('all', this)">All <span id="count-all">0</span></button>
                    <button class="tab-btn" data-filter="in-play" onclick="filterWalkIns('in-play', this)">In Play <span id="count-in-play">0</span></button>
                    <button class="tab-btn" data-filter="completed" onclick="filterWalkIns('completed', this)">Completed <span id="count-completed">0</span></button>
                </div>
                
                <div class="search-box">
                    <button class="btn-primary" onclick="toggleView('form')">+ Add New</button>
                    <div class="search-input-wrapper">
                        <input type="text" id="walkInSearch" placeholder="Search..." oninput="handleSearch()">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <button class="btn-export"><i class="fa-solid fa-file-export"></i></button>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Name</th><th>Sport</th><th>Court</th><th>Date</th><th>Time</th><th>Amount</th><th>Payment</th><th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @forelse($walkIns as $res)
                            <!-- Real Database Data rendered by Blade -->
                            <tr class="walkin-row" data-status="{{ $res->status == 'confirmed' ? 'in-play' : $res->status }}">
                                <td class="td-id">{{ $res->reservation_code }}</td>
                                <td>{{ $res->user->name ?? 'Walk-In' }}</td>
                                <td>{{ $res->sport }}</td>
                                <td>Court {{ $res->court_id }}</td>
                                <td>{{ \Carbon\Carbon::parse($res->start_time)->format('M j, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}</td>
                                <td>₱{{ number_format($res->total_price, 2) }}</td>
                                <td>{{ $res->payment_type }}</td>
                                <td>
                                    <div class="dropdown-container">
                                        <button type="button" class="btn-dots" onclick="toggleMenu('menu-{{ $res->id }}')"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                        <div id="menu-{{ $res->id }}" class="action-menu">
                                            
                                            <!-- Dynamic Action Buttons based on Status -->
                                            @if($res->status == 'confirmed')
                                                <form action="{{ url(Request::segment(1).'/walk-in/'.$res->id.'/completed') }}" method="POST" style="margin:0;">
                                                    @csrf <button type="submit"><i class="fa-solid fa-circle-check" style="color: #059669;"></i> Mark Completed</button>
                                                </form>
                                            @else
                                                <form action="{{ url(Request::segment(1).'/walk-in/'.$res->id.'/confirmed') }}" method="POST" style="margin:0;">
                                                    @csrf <button type="submit"><i class="fa-solid fa-rotate-left" style="color: #2563eb;"></i> Mark In Play</button>
                                                </form>
                                            @endif
                                            
                                            <form action="{{ url(Request::segment(1).'/walk-in/'.$res->id.'/delete') }}" method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to delete this walk-in?');">
                                                @csrf <button type="submit" style="color: #dc2626;"><i class="fa-regular fa-trash-can"></i> Delete</button>
                                            </form>

                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="9" class="empty-state">No walk-in records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="pagination">
                    <a href="#" style="color:var(--text-main); text-decoration:none;"><i class="fa-solid fa-chevron-left"></i></a>
                    <a href="#" style="background:var(--primary-blue); color:white; padding:2px 8px; border-radius:4px; text-decoration:none;">1</a>
                    <a href="#" style="color:var(--text-main); text-decoration:none;"><i class="fa-solid fa-chevron-right"></i></a>
                </div>
            </div>
        </div>

        <!-- FORM VIEW -->
        <div id="formView">
            
            <!-- Real Form Submission directly to database -->
            <form id="addWalkInForm" method="POST" action="{{ url(Request::segment(1).'/walk-in/store') }}" onsubmit="return validateWalkInForm()">
                @csrf
                <div class="form-grid">
                    <!-- LEFT COLUMN -->
                    <div class="left-col">
                        <div class="form-card">
                            <h3>Customer Information</h3>
                            <p class="desc">Enter customer details for walk - in reservation</p>
                            
                            <div class="row-2">
                                <div class="input-group">
                                    <label>Full Name<span>*</span></label>
                                    <input type="text" name="name" class="input-control" required oninput="syncSummary()">
                                </div>
                                <div class="input-group">
                                    <label>Phone Number<span>*</span></label>
                                    <input type="text" name="phone" class="input-control" required oninput="syncSummary()">
                                </div>
                            </div>
                            <div class="input-group" style="margin-bottom:0;">
                                <label>Email</label>
                                <input type="email" name="email" class="input-control">
                            </div>
                        </div>

                        <div class="form-card">
                            <h3>Reserve Information</h3>
                            <p class="desc">Enter reserve details</p>
                            
                            <div class="row-3">
                                <div class="input-group">
                                    <label>Sport<span>*</span></label>
                                    <select name="sport" id="sportSelect" class="input-control" onchange="handleSportChange()">
                                        <option value="Badminton">🏸 Badminton (₱230/hr)</option>
                                        <option value="Pickleball">🏓 Pickleball (₱250/hr)</option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <label>Court<span>*</span></label>
                                    <select name="court" id="courtSelect" class="input-control" onchange="handleCourtChange()">
                                        <option value="1">Court 1</option>
                                        <option value="2">Court 2</option>
                                        <option value="3">Court 3</option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <label>Date<span>*</span></label>
                                    <input type="date" name="date" id="resDate" class="input-control" value="{{ now()->timezone('Asia/Manila')->format('Y-m-d') }}" min="{{ now()->timezone('Asia/Manila')->format('Y-m-d') }}" required onchange="handleDateChange()">
                                </div>
                            </div>

                            <div class="input-group">
                                <label>Time<span>*</span> <span style="font-size:11px; color:var(--text-muted); font-weight:400; margin-left:5px;">Available Time Slot</span></label>
                                <div class="time-slots" id="timeSlots">
                                    <!-- Populated dynamically -->
                                </div>
                                <input type="hidden" name="time" id="selectedTime" value="">
                                <input type="hidden" name="start_time" id="hidden_start_time" value="">
                                <input type="hidden" name="end_time" id="hidden_end_time" value="">
                            </div>

                            <div class="row-2">
                                <div class="input-group">
                                    <label>Duration<span>*</span></label>
                                    <select name="duration" id="durationSelect" class="input-control" onchange="handleDurationChange()">
                                        <option value="1">1 Hour</option>
                                        <option value="2">2 Hours</option>
                                        <option value="3">3 Hours</option>
                                    </select>
                                </div>
                                
                                <div id="rentalSection" class="input-group">
                                    <label>Rental Items <span style="color:var(--text-muted); font-weight:400;">(Optional)</span></label>
                                    
                                    <div class="rental-item" id="rentalRacket">
                                        <div class="rental-info">
                                            <h4>Racket</h4>
                                            <p>₱50.00 / pc</p>
                                        </div>
                                        <div class="counter">
                                            <button type="button" onclick="updateCount('racket', -1)">-</button>
                                            <input type="text" id="racketCount" name="racket_qty" value="0" readonly>
                                            <button type="button" onclick="updateCount('racket', 1)">+</button>
                                        </div>
                                    </div>

                                    <div class="rental-item" id="rentalShuttlecock">
                                        <div class="rental-info">
                                            <h4>Shuttlecock</h4>
                                            <p>₱50.00 / pc</p>
                                        </div>
                                        <div class="counter">
                                            <button type="button" onclick="updateCount('shuttle', -1)">-</button>
                                            <input type="text" id="shuttleCount" name="shuttle_qty" value="0" readonly>
                                            <button type="button" onclick="updateCount('shuttle', 1)">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="right-col">
                        <div class="form-card" style="padding-bottom: 15px;">
                            <div class="summary-header">
                                <h2 id="sumSportTitle">🏸 Badminton</h2>
                            </div>
                            <table class="summary-table">
                                <tr><td>Name</td><td id="sumName">-</td></tr>
                                <tr><td>Number</td><td id="sumPhone">-</td></tr>
                                <tr><td>Court</td><td id="sumCourt">Court 1</td></tr>
                                <tr><td>Date</td><td id="sumDate">{{ now()->timezone('Asia/Manila')->format('F j, Y') }}</td></tr>
                                <tr><td>Time</td><td id="sumTime">Not selected</td></tr>
                                <tr><td>Duration</td><td id="sumDuration">1 Hour</td></tr>
                                <tr><td>Rental</td><td id="sumRental">None</td></tr>
                            </table>
                            <div class="total-row">
                                <span>Total Amount</span>
                                <h3 id="sumTotalText">₱ 230.00</h3>
                            </div>
                        </div>

                        <div class="form-card">
                            <h3>Payment Information</h3>
                            <p class="desc">Enter payment details</p>
                            
                            <div class="row-2">
                                <div class="input-group">
                                    <label>Payment Method<span>*</span></label>
                                    <select name="payment_method" id="paymentMethod" class="input-control" onchange="togglePaymentFields()">
                                        <option value="Cash">Cash</option>
                                        <option value="GCash">GCash</option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <label>Amount Payable</label>
                                    <input type="text" id="inputPayable" class="input-control" value="₱ 230.00" readonly style="background:#f8fafc;">
                                    
                                    <!-- CRITICAL: Captures Total Amount for backend submission -->
                                    <input type="hidden" name="total_amount" id="rawTotalAmount" value="230">
                                </div>
                            </div>

                            <div class="row-2" id="cashFields">
                                <div class="input-group">
                                    <label>Amount Received<span>*</span></label>
                                    <input type="number" id="inputReceived" name="amount_received" class="input-control" placeholder="0.00" oninput="calculateChange()">
                                </div>
                                <div class="input-group">
                                    <label>Change</label>
                                    <input type="text" id="inputChange" class="input-control" placeholder="₱ 0.00" readonly style="background:#f8fafc;">
                                </div>
                            </div>

                            <div class="row-2 d-none" id="gcashFields">
                                <div class="input-group">
                                    <label>Account Name<span>*</span></label>
                                    <input type="text" name="gcash_name" class="input-control" placeholder="Sender Name">
                                </div>
                                <div class="input-group">
                                    <label>Gcash Number<span>*</span></label>
                                    <input type="text" name="gcash_number" class="input-control" placeholder="09XXXXXXXXX">
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn-cancel" onclick="toggleView('list')">Cancel</button>
                                <button type="submit" class="btn-save">Save Walk-In</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </main>

    <script>
        const badmintonRatePerHour = 230;
        const pickleballRatePerHour = 250;
        const rentalItemRate = 50;
        let selectedTimeString = null;
        let currentFilter = 'all';

        function validateWalkInForm() {
            if (!selectedTimeString || !document.getElementById('hidden_start_time').value) {
                alert('Please select an available time slot before saving!');
                return false; // Stops form submission
            }
            return true; // Let Laravel route handle the database save
        }

        function getClosingHour(dateString) {
            const d = new Date(dateString + "T00:00:00");
            const day = d.getDay();
            if (day === 0) return 14; 
            return 21;                
        }

        function generateTimeSlots(dateString) {
            const d = new Date(dateString + "T00:00:00");
            const day = d.getDay();
            
            let startHour = (day === 6 || day === 0) ? 7 : 8; 
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

        function checkAvailability() {
            let date = document.getElementById('resDate').value;
            let courtId = document.getElementById('courtSelect').value;

            if(!date || !courtId) return;

            fetch(`{{ url('/api/check-availability') }}?date=${date}&court_id=${courtId}`)
                .then(response => response.json())
                .then(data => {
                    renderTimeSlotsWithAvailability(data.booked_slots || []);
                })
                .catch(err => {
                    console.error('Availability check failed:', err);
                    renderTimeSlotsWithAvailability([]);
                });
        }

        function renderTimeSlotsWithAvailability(bookedSlots) {
            const selectedDate = document.getElementById('resDate').value;
            const selectedDuration = parseInt(document.getElementById('durationSelect').value) || 1;
            const closingHour = getClosingHour(selectedDate);
            
            const now = new Date();
            const manilaTime = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
            const todayStr = manilaTime.getFullYear() + '-' + String(manilaTime.getMonth() + 1).padStart(2, '0') + '-' + String(manilaTime.getDate()).padStart(2, '0');
            const currentHour = manilaTime.getHours();

            document.querySelectorAll('#timeSlots .time-slot').forEach(slot => {
                let timeText12 = slot.innerText.trim();
                let slotHour = parseInt(slot.getAttribute('data-hour'));
                
                slot.classList.remove('booked');
                
                let isAvailable = true;

                if (selectedDate === todayStr && slotHour <= currentHour) isAvailable = false;
                if (slotHour + selectedDuration > closingHour) isAvailable = false;

                if (isAvailable) {
                    for (let d = 0; d < selectedDuration; d++) {
                        let checkHour = slotHour + d;
                        let checkSuffix = checkHour >= 12 ? 'PM' : 'AM';
                        let checkHour12 = checkHour > 12 ? checkHour - 12 : (checkHour === 0 ? 12 : checkHour);
                        let checkTimeString12 = `${checkHour12}:00 ${checkSuffix}`;

                        if (bookedSlots.includes(checkTimeString12)) {
                            isAvailable = false;
                            break;
                        }
                    }
                }

                if (!isAvailable) {
                    slot.classList.add('booked');
                    slot.classList.remove('selected');
                    if(selectedTimeString === timeText12) {
                        selectedTimeString = null;
                        document.getElementById('selectedTime').value = "";
                        document.getElementById('hidden_start_time').value = "";
                        document.getElementById('hidden_end_time').value = "";
                        syncSummary();
                    }
                }
            });
        }

        function selectTime(element) {
            if(element.classList.contains('booked')) return;

            document.querySelectorAll('#timeSlots .time-slot').forEach(slot => slot.classList.remove('selected'));
            element.classList.add('selected');
           
            let timeText = element.innerText;
            selectedTimeString = timeText;
            
            let startHour = parseInt(element.getAttribute('data-hour'));
            let closingHour = getClosingHour(document.getElementById('resDate').value);
            let maxAvailableHours = closingHour - startHour;

            let durationSelect = document.getElementById('durationSelect');
            let currentSelectedDuration = parseInt(durationSelect.value) || 1;
            
            if (currentSelectedDuration > maxAvailableHours) {
                durationSelect.value = 1;
            }

            let duration = parseInt(durationSelect.value) || 1;
            let endHour = startHour + duration;
           
            document.getElementById('selectedTime').value = timeText;
            document.getElementById('hidden_start_time').value = startHour.toString().padStart(2, '0') + ':00:00';
            document.getElementById('hidden_end_time').value = endHour.toString().padStart(2, '0') + ':00:00';
            
            syncSummary();
        }

        function handleSportChange() {
            const sport = document.getElementById('sportSelect').value;
            const rentalSection = document.getElementById('rentalSection');
            
            if (sport === 'Pickleball') {
                rentalSection.style.display = 'none';
                document.getElementById('racketCount').value = 0;
                document.getElementById('shuttleCount').value = 0;
            } else {
                rentalSection.style.display = 'block';
            }
            checkAvailability();
            syncSummary();
        }

        function handleCourtChange() {
            checkAvailability();
            syncSummary();
        }

        function handleDateChange() {
            const dateVal = document.getElementById('resDate').value;
            selectedTimeString = null;
            document.getElementById('selectedTime').value = "";
            document.getElementById('hidden_start_time').value = "";
            document.getElementById('hidden_end_time').value = "";
            document.getElementById('durationSelect').value = "1";

            generateTimeSlots(dateVal);
            checkAvailability();
            syncSummary();
        }

        function handleDurationChange() {
            checkAvailability();
            if(selectedTimeString) {
                let selectedEl = document.querySelector('#timeSlots .time-slot.selected');
                if(selectedEl && !selectedEl.classList.contains('booked')) {
                    let startHour = parseInt(selectedEl.getAttribute('data-hour'));
                    let duration = parseInt(document.getElementById('durationSelect').value) || 1;
                    let endHour = startHour + duration;

                    document.getElementById('hidden_start_time').value = startHour.toString().padStart(2, '0') + ':00:00';
                    document.getElementById('hidden_end_time').value = endHour.toString().padStart(2, '0') + ':00:00';
                }
            }
            syncSummary();
        }

        function toggleMenu(menuId) {
            document.querySelectorAll('.action-menu').forEach(menu => {
                if(menu.id !== menuId) menu.classList.remove('show');
            });
            const targetMenu = document.getElementById(menuId);
            if(targetMenu) targetMenu.classList.toggle('show');
        }

        window.onclick = function(event) {
            if (!event.target.matches('.btn-dots') && !event.target.matches('.fa-ellipsis-vertical')) {
                document.querySelectorAll('.action-menu').forEach(menu => {
                    menu.classList.remove('show');
                });
            }
        }

        function toggleView(view) {
            const listView = document.getElementById('listView');
            const formView = document.getElementById('formView');
            const subtitle = document.getElementById('formSubtitle');

            if(view === 'form') {
                listView.style.display = 'none';
                formView.style.display = 'block';
                subtitle.classList.remove('d-none');
                
                const currentDate = document.getElementById('resDate').value || new Date().toISOString().split('T')[0];
                generateTimeSlots(currentDate);
                checkAvailability(); 
                syncSummary(); 
            } else {
                listView.style.display = 'block';
                formView.style.display = 'none';
                subtitle.classList.add('d-none');
            }
        }

        function togglePaymentFields() {
            const method = document.getElementById('paymentMethod').value;
            const cashFields = document.getElementById('cashFields');
            const gcashFields = document.getElementById('gcashFields');

            if(method === 'Cash') {
                cashFields.classList.remove('d-none');
                gcashFields.classList.add('d-none');
                document.querySelector('input[name="gcash_name"]').removeAttribute('required');
                document.querySelector('input[name="gcash_number"]').removeAttribute('required');
            } else {
                cashFields.classList.add('d-none');
                gcashFields.classList.remove('d-none');
                document.querySelector('input[name="gcash_name"]').setAttribute('required', 'true');
                document.querySelector('input[name="gcash_number"]').setAttribute('required', 'true');
            }
        }

        function updateCount(item, change) {
            const input = document.getElementById(item + 'Count');
            let currentVal = parseInt(input.value) || 0;
            let newVal = currentVal + change;
            if(newVal < 0) newVal = 0;
            if(newVal > 5) newVal = 5;
            input.value = newVal;
            syncSummary();
        }

        function syncSummary() {
            document.getElementById('sumName').innerText = document.querySelector('input[name="name"]').value || '-';
            document.getElementById('sumPhone').innerText = document.querySelector('input[name="phone"]').value || '-';
            
            const sportSelect = document.getElementById('sportSelect');
            const sportVal = sportSelect.value;
            document.getElementById('sumSportTitle').innerText = sportVal === 'Badminton' ? '🏸 Badminton' : '🏓 Pickleball';
            
            document.getElementById('sumCourt').innerText = 'Court ' + document.getElementById('courtSelect').value;
            
            const rawDate = document.getElementById('resDate').value;
            const dateObj = new Date(rawDate + "T00:00:00");
            document.getElementById('sumDate').innerText = !isNaN(dateObj) ? dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : rawDate;

            const durationVal = parseInt(document.getElementById('durationSelect').value) || 1;
            document.getElementById('sumDuration').innerText = durationVal > 1 ? `${durationVal} Hours` : `1 Hour`;
            
            if (selectedTimeString) {
                let selectedEl = document.querySelector('#timeSlots .time-slot.selected');
                if(selectedEl) {
                    let startHour = parseInt(selectedEl.getAttribute('data-hour'));
                    let endHour = startHour + durationVal;
                    let endSuffix = endHour >= 12 && endHour < 24 ? 'PM' : 'AM';
                    let endDisplayHour = endHour > 12 ? endHour - 12 : (endHour === 0 ? 12 : endHour);
                    document.getElementById('sumTime').innerText = `${selectedTimeString} - ${endDisplayHour}:00 ${endSuffix}`;
                } else {
                    document.getElementById('sumTime').innerText = selectedTimeString;
                }
            } else {
                document.getElementById('sumTime').innerText = 'Not selected';
            }

            let courtRate = sportVal === 'Pickleball' ? pickleballRatePerHour : badmintonRatePerHour;
            let total = courtRate * durationVal;

            if (sportVal === 'Badminton') {
                const racketQty = parseInt(document.getElementById('racketCount').value) || 0;
                const shuttleQty = parseInt(document.getElementById('shuttleCount').value) || 0;
                total += (racketQty * rentalItemRate) + (shuttleQty * rentalItemRate);

                let rentalArr = [];
                if (racketQty > 0) rentalArr.push(`${racketQty}x Racket`);
                if (shuttleQty > 0) rentalArr.push(`${shuttleQty}x Shuttlecock`);
                document.getElementById('sumRental').innerText = rentalArr.length > 0 ? rentalArr.join(', ') : 'None';
            } else {
                document.getElementById('sumRental').innerText = 'Not applicable';
            }
            
            document.getElementById('sumTotalText').innerText = `₱ ${total.toFixed(2)}`;
            document.getElementById('inputPayable').value = `₱ ${total.toFixed(2)}`;
            document.getElementById('rawTotalAmount').value = total;

            calculateChange();
        }

        function calculateChange() {
            const total = parseFloat(document.getElementById('rawTotalAmount').value) || 0;
            const received = parseFloat(document.getElementById('inputReceived').value) || 0;
            
            let change = received - total;
            if(change < 0 || received === 0) {
                change = 0;
            }

            document.getElementById('inputChange').value = `₱ ${change.toFixed(2)}`;
        }

        function filterWalkIns(status, btn) {
            currentFilter = status;
            if (btn) {
                document.querySelectorAll('.filter-tabs .tab-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
            }
            applyFilters();
        }

        function handleSearch() {
            applyFilters();
        }

        function applyFilters() {
            const searchVal = (document.getElementById('walkInSearch') ? document.getElementById('walkInSearch').value : '').toLowerCase().trim();
            const rows = document.querySelectorAll('#tableBody .walkin-row');
            const emptyRow = document.getElementById('emptyRow');
            let visibleCount = 0;

            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-status') || 'in-play';
                const rowText = row.innerText.toLowerCase();

                const matchesFilter = (currentFilter === 'all') || (rowStatus === currentFilter);
                const matchesSearch = !searchVal || rowText.includes(searchVal);

                if (matchesFilter && matchesSearch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (emptyRow) {
                if (visibleCount === 0) {
                    emptyRow.style.display = '';
                } else {
                    emptyRow.style.display = 'none';
                }
            }
        }

        function updateTabCounts() {
            const rows = document.querySelectorAll('#tableBody .walkin-row');
            let allCount = rows.length;
            let inPlayCount = 0;
            let completedCount = 0;

            rows.forEach(row => {
                const status = row.getAttribute('data-status') || 'in-play';
                if (status === 'in-play') inPlayCount++;
                if (status === 'completed') completedCount++;
            });

            if (document.getElementById('count-all')) document.getElementById('count-all').innerText = allCount;
            if (document.getElementById('count-in-play')) document.getElementById('count-in-play').innerText = inPlayCount;
            if (document.getElementById('count-completed')) document.getElementById('count-completed').innerText = completedCount;
        }

        document.addEventListener('DOMContentLoaded', () => {
            const initialDate = document.getElementById('resDate') ? document.getElementById('resDate').value : null;
            if(initialDate) {
                generateTimeSlots(initialDate);
                checkAvailability(); 
            }
            updateTabCounts();
            applyFilters();
        });
    </script>
</body>
</html>