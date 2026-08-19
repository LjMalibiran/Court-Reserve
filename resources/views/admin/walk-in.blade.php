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
        
        /* Header */
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .top-header h1 { margin: 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; display: flex; align-items: center; gap: 15px; }
        .subtitle { font-size: 14px; color: var(--text-muted); font-weight: 400; margin-top: 5px; }
        .header-right { display: flex; align-items: center; gap: 20px; color: var(--dark-blue); font-weight: 500; font-size: 14px; }
        
        /* Controls */
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

        /* Tables & Dropdowns */
        .table-container { background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border-color); flex-grow: 1; display: flex; flex-direction: column; overflow: visible; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 16px 20px; text-align: left; font-size: 14px; }
        th { background-color: #f8fafc; color: var(--dark-blue); font-weight: 600; border-bottom: 2px solid var(--border-color); }
        td { border-bottom: 1px solid #f0f0f0; color: #4b5563; }
        
        /* ID Styling */
        .td-id { color: var(--primary-blue); font-weight: 500; }

        /* Action Menu */
        .dropdown-container { position: relative; display: inline-block; text-align: center; width: 100%;}
        .btn-dots { background: none; border: none; font-size: 18px; color: var(--text-muted); cursor: pointer; padding: 5px 10px; }
        .action-menu { display: none; position: absolute; right: 20px; top: 30px; background: white; min-width: 140px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 8px; z-index: 100; border: 1px solid var(--border-color); overflow: hidden; }
        .action-menu.show { display: block; }
        .action-menu a, .action-menu button { color: #333; padding: 10px 15px; text-decoration: none; display: flex; align-items: center; gap: 10px; font-size: 13px; width: 100%; border: none; background: none; text-align: left; cursor: pointer; }
        .action-menu a:hover, .action-menu button:hover { background-color: #f8fafc; }

        .empty-state { text-align: center !important; padding: 40px !important; color: var(--text-muted) !important; font-style: italic; }
        .pagination { display: flex; justify-content: flex-end; padding: 20px; gap: 10px; margin-top: auto; }
        
        /* Form View */
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
        .time-slot { border: 1px solid var(--border-color); background: #fff; padding: 10px; text-align: center; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; color: var(--text-main); transition: 0.2s; }
        .time-slot:hover { border-color: var(--focus-blue); }
        .time-slot.active { background: #0033ff; color: white; border-color: #0033ff; }

        .rental-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .rental-info h4 { margin: 0; font-size: 14px; color: var(--text-main); }
        .rental-info p { margin: 0; font-size: 11px; color: var(--text-muted); }
        .counter { display: flex; align-items: center; border: 1px solid var(--border-color); border-radius: 6px; overflow: hidden; }
        .counter button { background: #f8fafc; border: none; padding: 5px 12px; cursor: pointer; color: var(--text-main); }
        .counter button:hover { background: #e5e7eb; }
        .counter input { width: 40px; text-align: center; border: none; border-left: 1px solid var(--border-color); border-right: 1px solid var(--border-color); font-size: 13px; padding: 5px 0; }
        .counter input:focus { outline: none; }

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
        .btn-cancel:hover { background: #f8fafc; }
        .btn-save { background: #0033ff; color: white; border: none; padding: 12px 40px; border-radius: 6px; font-weight: 600; cursor: pointer; }
        .btn-save:hover { background: #0022cc; }
        .d-none { display: none !important; }
    </style>
</head>
<body>

    @include('admin.sidebar')

    <main class="main-content">
        
        <header class="top-header">
            <div>
                <h1>Walk - In</h1>
                <div id="formSubtitle" class="subtitle d-none">New Walk - In Reservation</div>
            </div>
            <div class="header-right">
                <span>{{ now()->format('l, F j, Y') }}</span>
                <i class="fa-regular fa-bell"></i>
            </div>
        </header>

        <!-- LIST VIEW -->
        <div id="listView">
            <div class="controls-bar">
                <div class="filter-tabs">
                    <button class="tab-btn active">All <span>99</span></button>
                    <button class="tab-btn">In Play <span>2</span></button>
                    <button class="tab-btn">Completed <span>30</span></button>
                    <button class="tab-btn">Cancelled <span>9</span></button>
                </div>
                
                <div class="search-box">
                    <button class="btn-primary" onclick="toggleView('form')">+ Add New</button>
                    <div class="search-input-wrapper">
                        <input type="text" placeholder="Search">
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
                        <!-- Populated to match mockup -->
                        <tr>
                            <td class="td-id">W-BC26-01</td><td>Ven Matira</td><td>Badminton</td><td>Court 1</td><td>Jun 1, 2026</td><td>4:00-5:00 PM</td><td>P 230.00</td><td>Gcash</td>
                            <td>
                                <div class="dropdown-container">
                                    <button class="btn-dots" onclick="toggleMenu('menu-1')"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    <div id="menu-1" class="action-menu">
                                        <a href="#"><i class="fa-regular fa-eye"></i> View Details</a>
                                        <a href="#"><i class="fa-solid fa-pen"></i> Edit</a>
                                        <button style="color: #dc2626;"><i class="fa-regular fa-trash-can"></i> Delete</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-id">W-PC26-01</td><td>Ven Matira</td><td>Pickleball</td><td>Court 1</td><td>Jun 1, 2026</td><td>4:00-5:00 PM</td><td>P 230.00</td><td>Cash</td>
                            <td>
                                <div class="dropdown-container">
                                    <button class="btn-dots" onclick="toggleMenu('menu-2')"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    <div id="menu-2" class="action-menu">
                                        <a href="#"><i class="fa-regular fa-eye"></i> View Details</a>
                                        <a href="#"><i class="fa-solid fa-pen"></i> Edit</a>
                                        <button style="color: #dc2626;"><i class="fa-regular fa-trash-can"></i> Delete</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="td-id">W-PC26-02</td><td>Ven Matira</td><td>Pickleball</td><td>Court 1</td><td>Jun 1, 2026</td><td>4:00-5:00 PM</td><td>P 230.00</td><td>Cash</td>
                            <td>
                                <div class="dropdown-container">
                                    <button class="btn-dots" onclick="toggleMenu('menu-3')"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                    <div id="menu-3" class="action-menu">
                                        <a href="#"><i class="fa-regular fa-eye"></i> View Details</a>
                                        <a href="#"><i class="fa-solid fa-pen"></i> Edit</a>
                                        <button style="color: #dc2626;"><i class="fa-regular fa-trash-can"></i> Delete</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
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
            <form id="addWalkInForm" onsubmit="handleFormSubmit(event)">
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
                                    <select name="sport" class="input-control" onchange="syncSummary()">
                                        <option value="Badminton">🏸 Badminton</option>
                                        <option value="Pickleball">🏓 Pickleball</option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <label>Court<span>*</span></label>
                                    <select name="court" class="input-control" onchange="syncSummary()">
                                        <option>Court 1</option>
                                        <option>Court 2</option>
                                        <!-- Added Court 3 -->
                                        <option>Court 3</option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <label>Date<span>*</span></label>
                                    <input type="date" name="date" class="input-control" value="{{ date('Y-m-d') }}" required onchange="syncSummary()">
                                </div>
                            </div>

                            <div class="input-group">
                                <label>Time<span>*</span> <span style="font-size:11px; color:var(--text-muted); font-weight:400; margin-left:5px;">Available Time Slot</span></label>
                                <div class="time-slots">
                                    <div class="time-slot" onclick="selectTime(this)">8:00 AM</div>
                                    <div class="time-slot" onclick="selectTime(this)">10:00 AM</div>
                                    <div class="time-slot" onclick="selectTime(this)">11:00 AM</div>
                                    <div class="time-slot" onclick="selectTime(this)">12:00 PM</div>
                                    <div class="time-slot" onclick="selectTime(this)">1:00 PM</div>
                                    <div class="time-slot" onclick="selectTime(this)">2:00 PM</div>
                                    <div class="time-slot" onclick="selectTime(this)">3:00 PM</div>
                                    <div class="time-slot active" onclick="selectTime(this)">4:00 PM</div>
                                    <div class="time-slot" onclick="selectTime(this)">6:00 PM</div>
                                    <div class="time-slot" onclick="selectTime(this)">8:00 PM</div>
                                </div>
                                <input type="hidden" name="time" id="selectedTime" value="4:00 PM">
                            </div>

                            <div class="row-2">
                                <div class="input-group">
                                    <label>Duration<span>*</span></label>
                                    <select name="duration" class="input-control" onchange="syncSummary()">
                                        <option value="1">1 Hour</option>
                                        <option value="2">2 Hours</option>
                                        <option value="3">3 Hours</option>
                                    </select>
                                </div>
                                <div class="input-group">
                                    <label>Rental Items <span style="color:var(--text-muted); font-weight:400;">(Optional)</span></label>
                                    
                                    <div class="rental-item">
                                        <div class="rental-info">
                                            <h4>Racket</h4>
                                            <p>P50.00 / pc</p>
                                        </div>
                                        <div class="counter">
                                            <button type="button" onclick="updateCount('racket', -1)">-</button>
                                            <input type="text" id="racketCount" name="racket_qty" value="1" readonly>
                                            <button type="button" onclick="updateCount('racket', 1)">+</button>
                                        </div>
                                    </div>

                                    <div class="rental-item">
                                        <div class="rental-info">
                                            <h4>Shuttlecock</h4>
                                            <p>P50.00 / pc</p>
                                        </div>
                                        <div class="counter">
                                            <button type="button" onclick="updateCount('shuttle', -1)">-</button>
                                            <input type="text" id="shuttleCount" name="shuttle_qty" value="1" readonly>
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
                                <tr><td>Date</td><td id="sumDate">{{ now()->format('F j, Y') }}</td></tr>
                                <tr><td>Time</td><td id="sumTime">4:00 PM - 5:00 PM</td></tr>
                                <tr><td>Duration</td><td id="sumDuration">1 Hour</td></tr>
                                <tr><td>Rental</td><td id="sumRental">1 Racket, 1 Shuttlecock</td></tr>
                            </table>
                            <div class="total-row">
                                <span>Total Amount</span>
                                <h3 id="sumTotalText">₱ 280.00</h3>
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
                                    <input type="text" id="inputPayable" class="input-control" value="₱ 280.00" readonly style="background:#f8fafc;">
                                    <input type="hidden" id="rawTotalAmount" value="280">
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
                                    <input type="text" name="gcash_number" class="input-control" placeholder="09123456789">
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn-cancel" onclick="toggleView('list')">Cancel</button>
                                <button type="submit" class="btn-save">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </main>

    <script>
        const courtRatePerHour = 230;
        const rentalItemRate = 50;
        
        // Counter for dynamic ID generation in prototype
        let newRecordCount = 1;

        // Dropdown toggles
        function toggleMenu(menuId) {
            document.querySelectorAll('.action-menu').forEach(menu => {
                if(menu.id !== menuId) menu.classList.remove('show');
            });
            document.getElementById(menuId).classList.toggle('show');
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
                // clear GCash required fields
                document.querySelector('input[name="gcash_name"]').removeAttribute('required');
                document.querySelector('input[name="gcash_number"]').removeAttribute('required');
            } else {
                cashFields.classList.add('d-none');
                gcashFields.classList.remove('d-none');
                // add GCash required fields
                document.querySelector('input[name="gcash_name"]').setAttribute('required', 'true');
                document.querySelector('input[name="gcash_number"]').setAttribute('required', 'true');
            }
        }

        function selectTime(element) {
            document.querySelectorAll('.time-slot').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            document.getElementById('selectedTime').value = element.innerText;
            syncSummary();
        }

        function updateCount(item, change) {
            const input = document.getElementById(item + 'Count');
            let currentVal = parseInt(input.value) || 0;
            let newVal = currentVal + change;
            if(newVal < 0) newVal = 0;
            input.value = newVal;
            syncSummary();
        }

        function formatTimeRange(startTime, durationHrs) {
            if(!startTime) return '-';
            const match = startTime.match(/(\d+):(\d+)\s*(AM|PM)/i);
            if(!match) return startTime;
            
            let hours = parseInt(match[1]);
            let mins = match[2];
            let ampm = match[3].toUpperCase();

            if (ampm === "PM" && hours !== 12) hours += 12;
            if (ampm === "AM" && hours === 12) hours = 0;

            let date = new Date();
            date.setHours(hours, parseInt(mins), 0);
            date.setHours(date.getHours() + parseInt(durationHrs));

            let endHours = date.getHours();
            let endAmpm = endHours >= 12 ? 'PM' : 'AM';
            if (endHours > 12) endHours -= 12;
            if (endHours === 0) endHours = 12;

            let endMins = date.getMinutes().toString().padStart(2, '0');
            return `${startTime} - ${endHours}:${endMins} ${endAmpm}`;
        }

        function syncSummary() {
            document.getElementById('sumName').innerText = document.querySelector('input[name="name"]').value || '-';
            document.getElementById('sumPhone').innerText = document.querySelector('input[name="phone"]').value || '-';
            
            const sportSelect = document.querySelector('select[name="sport"]');
            document.getElementById('sumSportTitle').innerText = sportSelect.options[sportSelect.selectedIndex].text;
            
            document.getElementById('sumCourt').innerText = document.querySelector('select[name="court"]').value;
            
            const rawDate = document.querySelector('input[name="date"]').value;
            const dateObj = new Date(rawDate);
            document.getElementById('sumDate').innerText = !isNaN(dateObj) ? dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : rawDate;

            const durationVal = parseInt(document.querySelector('select[name="duration"]').value) || 1;
            document.getElementById('sumDuration').innerText = durationVal > 1 ? `${durationVal} Hours` : `1 Hour`;
            
            const startTime = document.getElementById('selectedTime').value;
            document.getElementById('sumTime').innerText = formatTimeRange(startTime, durationVal);

            const racketQty = parseInt(document.getElementById('racketCount').value) || 0;
            const shuttleQty = parseInt(document.getElementById('shuttleCount').value) || 0;

            let rentalArr = [];
            if (racketQty > 0) rentalArr.push(`${racketQty} Racket`);
            if (shuttleQty > 0) rentalArr.push(`${shuttleQty} Shuttlecock`);
            document.getElementById('sumRental').innerText = rentalArr.length > 0 ? rentalArr.join(', ') : 'None';

            const total = (courtRatePerHour * durationVal) + (racketQty * rentalItemRate) + (shuttleQty * rentalItemRate);
            
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

        // Handle the Form Submit to dynamically append to the table
        function handleFormSubmit(event) {
            event.preventDefault(); // Stop actual page reload for the prototype

            // Gather Data
            const name = document.querySelector('input[name="name"]').value;
            const sportRaw = document.querySelector('select[name="sport"]').value; // "Badminton" or "Pickleball"
            const court = document.querySelector('select[name="court"]').value;
            const date = document.getElementById('sumDate').innerText;
            const time = document.getElementById('sumTime').innerText;
            const amount = document.getElementById('sumTotalText').innerText.replace('₱', 'P');
            const paymentMethod = document.getElementById('paymentMethod').value;

            // Generate Mock ID
            const prefix = sportRaw === 'Badminton' ? 'W-BC' : 'W-PC';
            const mockId = `${prefix}26-NEW${newRecordCount++}`;
            const menuId = `menu-new-${newRecordCount}`;

            // Create New HTML Row
            const newRowHtml = `
                <tr>
                    <td class="td-id">${mockId}</td>
                    <td>${name}</td>
                    <td>${sportRaw}</td>
                    <td>${court}</td>
                    <td>${date}</td>
                    <td>${time}</td>
                    <td>${amount}</td>
                    <td>${paymentMethod}</td>
                    <td>
                        <div class="dropdown-container">
                            <button type="button" class="btn-dots" onclick="toggleMenu('${menuId}')"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                            <div id="${menuId}" class="action-menu">
                                <a href="#"><i class="fa-regular fa-eye"></i> View Details</a>
                                <a href="#"><i class="fa-solid fa-pen"></i> Edit</a>
                                <button type="button" style="color: #dc2626;"><i class="fa-regular fa-trash-can"></i> Delete</button>
                            </div>
                        </div>
                    </td>
                </tr>
            `;

            // Prepend row to table
            const tbody = document.getElementById('tableBody');
            tbody.insertAdjacentHTML('afterbegin', newRowHtml);

            // Reset Form and View
            document.getElementById('addWalkInForm').reset();
            document.getElementById('racketCount').value = 1;
            document.getElementById('shuttleCount').value = 1;
            syncSummary();
            toggleView('list');
        }
    </script>
</body>
</html>