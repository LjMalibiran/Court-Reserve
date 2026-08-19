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
            --primary-blue-hover: #0d3d8a;
            --dark-blue: #002277;
            --bg-color: #f4f6f9;
            --card-bg: #ffffff;
            --text-main: #333333;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
        }
        
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); display: flex; height: 100vh; overflow: hidden; }
        
        /* --- SIDEBAR --- */
        .sidebar { width: 250px; background-color: var(--primary-blue); color: white; display: flex; flex-direction: column; flex-shrink: 0; overflow-y: auto; height: 100vh; }
        .logo-container { padding: 30px 20px 20px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .logo-container img { max-width: 150px; }
        .menu-group { margin-top: 20px; padding: 0 15px; }
        .menu-title { font-size: 11px; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; padding-left: 10px; }
        .nav-menu { list-style: none; padding: 0; margin: 0; }
        .nav-menu li { margin-bottom: 5px; }
        .nav-menu a { display: flex; align-items: center; padding: 12px 15px; color: white; text-decoration: none; font-size: 14px; font-weight: 500; border-radius: 8px; transition: 0.2s; gap: 10px; }
        .nav-menu a.active { background-color: white; color: var(--primary-blue); font-weight: bold; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .nav-menu a:hover:not(.active) { background-color: rgba(255,255,255,0.1); }
        
        .has-submenu { justify-content: space-between; }
        .submenu { list-style: none; padding-left: 35px; margin: 5px 0 10px 0; font-size: 13px; }
        .submenu li { margin-bottom: 8px; }
        .submenu a { padding: 0; color: rgba(255,255,255,0.9); display: inline-block; font-weight: 400; }
        .submenu a::before { content: "•"; margin-right: 8px; font-size: 16px; }
        .submenu a:hover { background: transparent; color: white; }

        .user-profile-section { margin-top: auto; padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .profile-info { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; text-decoration: none; color: white; }
        .profile-avatar { width: 40px; height: 40px; background-color: white; color: var(--primary-blue); border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 18px; font-weight: bold; }
        .profile-name { font-size: 14px; font-weight: bold; }
        .profile-role { font-size: 11px; color: rgba(255,255,255,0.7); }
        .btn-logout { width: 100%; display: flex; align-items: center; gap: 10px; background: transparent; border: none; color: white; padding: 10px 0; cursor: pointer; font-size: 14px; font-weight: 500; }
        
        /* --- MAIN CONTENT --- */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 30px; position: relative; }
        .top-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 25px; }
        .header-title-area h1 { margin: 0 0 5px 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; }
        .subtitle { font-size: 14px; color: var(--text-muted); margin: 0; }
        .header-right { display: flex; align-items: center; gap: 20px; color: var(--dark-blue); font-weight: 500; font-size: 15px; margin-bottom: 5px;}
        
        /* --- VIEW CONTAINERS --- */
        #list-view { display: block; }
        #form-view { display: none; }

        /* --- LIST VIEW STYLES --- */
        .controls-wrapper { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .tabs { display: flex; gap: 10px; background: #f1f5f9; padding: 6px; border-radius: 8px; }
        .tab-btn { background: transparent; border: none; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 500; color: var(--text-muted); cursor: pointer; transition: 0.2s; display: flex; align-items: center; gap: 8px; }
        .tab-btn:hover:not(.active) { background: #e2e8f0; }
        .tab-btn.active { background: var(--dark-blue); color: white; }
        
        .actions-right { display: flex; gap: 15px; align-items: center; }
        .btn-add { background: var(--primary-blue); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: 0.2s; display: flex; gap: 8px; align-items: center; }
        .btn-add:hover { background: var(--primary-blue-hover); }
        .search-box { position: relative; }
        .search-box input { padding: 10px 15px 10px 35px; border: 1px solid var(--border-color); border-radius: 8px; outline: none; width: 200px; font-size: 14px; }
        .search-box i { position: absolute; left: 12px; top: 12px; color: var(--text-muted); }
        .btn-export { padding: 10px 15px; border: 1px solid var(--border-color); background: white; border-radius: 8px; cursor: pointer; color: var(--text-main); }
        
        .table-card { background: white; border-radius: 12px; border: 1px solid var(--border-color); overflow: visible; }
        table { width: 100%; border-collapse: collapse; text-align: center; }
        th { padding: 15px; font-size: 14px; font-weight: 600; color: var(--text-muted); border-bottom: 1px solid var(--border-color); background: #f8fafc; }
        td { padding: 15px; font-size: 14px; color: var(--text-main); border-bottom: 1px solid var(--border-color); vertical-align: middle; }
        tr:last-child td { border-bottom: none; }
        
        .empty-row { padding: 50px 0 !important; color: var(--text-muted) !important; font-style: italic; }
        
        .pagination { display: flex; justify-content: flex-end; align-items: center; padding: 20px 0; gap: 10px; }
        .page-item { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 6px; font-size: 14px; cursor: pointer; color: var(--text-main); }
        .page-item.active { background: var(--primary-blue); color: white; }

        /* --- FORM VIEW STYLES --- */
        .form-grid { display: grid; grid-template-columns: 1.3fr 1fr; gap: 25px; }
        
        .form-section { background: white; border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 25px; }
        .section-header { margin-bottom: 20px; }
        .section-title { font-size: 18px; font-weight: 600; color: var(--dark-blue); margin-bottom: 5px; }
        .section-subtitle { font-size: 13px; color: var(--text-muted); }
        
        .input-group { display: flex; flex-direction: column; margin-bottom: 20px; }
        .input-group label { font-size: 14px; font-weight: 500; color: var(--text-main); margin-bottom: 8px; }
        .input-group label span { color: red; }
        .input-control { padding: 12px 15px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 14px; outline: none; transition: 0.2s; font-family: inherit; }
        .input-control:focus { border-color: var(--primary-blue); }
        
        .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }

        .time-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; margin-bottom: 20px; }
        .time-slot { border: 1px solid var(--border-color); background: #f8fafc; padding: 10px 0; text-align: center; border-radius: 8px; font-size: 13px; font-weight: 500; color: var(--text-main); cursor: pointer; user-select: none; }
        .time-slot.active { background: var(--primary-blue); color: white; border-color: var(--primary-blue); }
        
        .rental-item { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #f1f5f9; }
        .rental-info h4 { margin: 0 0 4px 0; font-size: 14px; color: var(--text-main); font-weight: 500; }
        .rental-info p { margin: 0; font-size: 11px; color: var(--text-muted); }
        .qty-control { display: flex; align-items: center; border: 1px solid var(--border-color); border-radius: 20px; overflow: hidden; }
        .qty-btn { background: transparent; border: none; width: 30px; height: 30px; display: flex; justify-content: center; align-items: center; cursor: pointer; color: var(--text-muted); }
        .qty-val { width: 30px; text-align: center; font-size: 14px; font-weight: 500; }

        .summary-card { background: #f8fafc; border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 25px; }
        .summary-header { display: flex; align-items: center; gap: 10px; justify-content: center; margin-bottom: 25px; font-size: 20px; font-weight: 700; color: var(--dark-blue); }
        .summary-list { display: grid; grid-template-columns: 80px 1fr; gap: 15px 10px; font-size: 14px; margin-bottom: 25px; }
        .sum-label { color: var(--text-muted); }
        .sum-val { font-weight: 500; color: var(--text-main); }
        .total-row { display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 1px dashed #cbd5e1; font-weight: 700; }
        .total-label { font-size: 16px; color: var(--dark-blue); }
        .total-amount { font-size: 24px; color: var(--dark-blue); }

        .form-actions { display: flex; justify-content: flex-end; gap: 15px; margin-top: 10px; }
        .btn-cancel { background: white; border: 1px solid var(--border-color); padding: 12px 30px; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; color: var(--text-main); }
        .btn-save { background: var(--primary-blue); border: none; padding: 12px 40px; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; color: white; }

        .payment-gcash, .payment-cash { display: none; }
        .payment-cash.active, .payment-gcash.active { display: block; }

    </style>
</head>
<body>

    @include('cashier.sidebar')

    <main class="main-content">
        
        <header class="top-header">
            <div class="header-title-area">
                <h1>Walk - In</h1>
                <p class="subtitle" id="page-subtitle"></p>
            </div>
            <div class="header-right">
                <i class="fa-regular fa-bell" style="font-size: 20px; cursor: pointer;"></i>
            </div>
        </header>

        <!-- LIST VIEW -->
        <div id="list-view">
            <div class="controls-wrapper">
                <div class="tabs">
                    <button class="tab-btn active">All <span>0</span></button>
                    <button class="tab-btn">In Play <span>0</span></button>
                    <button class="tab-btn">Completed <span>0</span></button>
                    <button class="tab-btn">Cancelled <span>0</span></button>
                </div>
                
                <div class="actions-right">
                    <button class="btn-add" onclick="showForm()"><i class="fa-solid fa-plus"></i> Add New</button>
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Search">
                    </div>
                    <button class="btn-export"><i class="fa-solid fa-upload"></i></button>
                </div>
            </div>

            <div class="table-card">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Sport</th>
                            <th>Court</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Amount</th>
                            <th>Payment</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="9" class="empty-row">No walk-in records found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <div class="page-item"><i class="fa-solid fa-angle-left"></i></div>
                <div class="page-item active">1</div>
                <div class="page-item"><i class="fa-solid fa-angle-right"></i></div>
            </div>
        </div>


        <!-- FORM VIEW -->
        <div id="form-view">
            <div class="form-grid">
                
                <!-- Left Column -->
                <div>
                    <!-- Customer Information -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-title">Customer Information</div>
                            <div class="section-subtitle">Enter customer details for walk - in reservation</div>
                        </div>
                        
                        <div class="row-2">
                            <div class="input-group">
                                <label>Full Name<span>*</span></label>
                                <input type="text" class="input-control" placeholder="e.g. John Doe">
                            </div>
                            <div class="input-group">
                                <label>Phone Number<span>*</span></label>
                                <input type="text" class="input-control" placeholder="09XXXXXXXXX">
                            </div>
                        </div>
                        <div class="row-2">
                            <div class="input-group">
                                <label>Email</label>
                                <input type="email" class="input-control" placeholder="Optional">
                            </div>
                        </div>
                    </div>

                    <!-- Reserve Information -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-title">Reserve Information</div>
                            <div class="section-subtitle">Enter reserve details</div>
                        </div>
                        
                        <div class="row-3">
                            <div class="input-group">
                                <label>Sport<span>*</span></label>
                                <select class="input-control">
                                    <option value="" disabled selected>Select Sport</option>
                                    <option>🏸 Badminton</option>
                                    <option>🏓 Pickleball</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <label>Court<span>*</span></label>
                                <select class="input-control">
                                    <option value="" disabled selected>Select Court</option>
                                    <option>Court 1</option>
                                    <option>Court 2</option>
                                    <option>Court 3</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <label>Date<span>*</span></label>
                                <input type="date" class="input-control">
                            </div>
                        </div>

                        <div class="input-group" style="margin-bottom: 5px;">
                            <label>Time<span>*</span></label>
                            <span style="font-size: 11px; color: var(--text-muted); margin-bottom: 10px;">Available Time Slot</span>
                            <div class="time-grid">
                                <div class="time-slot" onclick="selectTime(this)">8:00 AM</div>
                                <div class="time-slot" onclick="selectTime(this)">10:00 AM</div>
                                <div class="time-slot" onclick="selectTime(this)">11:00 AM</div>
                                <div class="time-slot" onclick="selectTime(this)">12:00 PM</div>
                                <div class="time-slot" onclick="selectTime(this)">1:00 PM</div>
                                <div class="time-slot" onclick="selectTime(this)">2:00 PM</div>
                                <div class="time-slot" onclick="selectTime(this)">3:00 PM</div>
                                <div class="time-slot" onclick="selectTime(this)">4:00 PM</div>
                                <div class="time-slot" onclick="selectTime(this)">6:00 PM</div>
                                <div class="time-slot" onclick="selectTime(this)">8:00 PM</div>
                            </div>
                        </div>

                        <div class="row-2" style="align-items: flex-start; margin-top:20px;">
                            <div class="input-group">
                                <label>Duration<span>*</span></label>
                                <select class="input-control">
                                    <option value="" disabled selected>Select Duration</option>
                                    <option>1 Hour</option>
                                    <option>2 Hours</option>
                                </select>
                            </div>
                            
                            <div>
                                <label style="font-size: 14px; font-weight: 500; color: var(--text-main); display:block; margin-bottom: 10px;">Rental Items <span style="color:var(--text-muted); font-weight:normal; font-size:12px;">(Optional)</span></label>
                                
                                <div class="rental-item">
                                    <div class="rental-info">
                                        <h4>Racket</h4>
                                        <p>P50.00 / pc</p>
                                    </div>
                                    <div class="qty-control">
                                        <button class="qty-btn"><i class="fa-solid fa-minus"></i></button>
                                        <div class="qty-val">0</div>
                                        <button class="qty-btn"><i class="fa-solid fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="rental-item" style="border:none;">
                                    <div class="rental-info">
                                        <h4>Shuttlecock</h4>
                                        <p>P50.00 / pc</p>
                                    </div>
                                    <div class="qty-control">
                                        <button class="qty-btn"><i class="fa-solid fa-minus"></i></button>
                                        <div class="qty-val">0</div>
                                        <button class="qty-btn"><i class="fa-solid fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div>
                    <!-- Summary -->
                    <div class="summary-card">
                        <div class="summary-header">
                            <i class="fa-solid fa-shuttlecock" style="color:var(--primary-blue);"></i> Summary
                        </div>
                        
                        <div class="summary-list">
                            <div class="sum-label">Name</div> <div class="sum-val">--</div>
                            <div class="sum-label">Number</div> <div class="sum-val">--</div>
                            <div class="sum-label">Court</div> <div class="sum-val">--</div>
                            <div class="sum-label">Date</div> <div class="sum-val">--</div>
                            <div class="sum-label">Time</div> <div class="sum-val">--</div>
                            <div class="sum-label">Duration</div> <div class="sum-val">--</div>
                            <div class="sum-label">Rental</div> <div class="sum-val">--</div>
                        </div>

                        <div class="total-row">
                            <div class="total-label">Total Amount</div>
                            <div class="total-amount">₱ 0.00</div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="form-section" style="margin-bottom: 10px;">
                        <div class="section-header">
                            <div class="section-title">Payment Information</div>
                            <div class="section-subtitle">Enter payment details</div>
                        </div>

                        <div class="row-2">
                            <div class="input-group">
                                <label>Payment Method<span>*</span></label>
                                <select class="input-control" id="paymentMethod" onchange="togglePaymentFields()">
                                    <option value="cash">Cash</option>
                                    <option value="gcash">GCash</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <label>Amount Payable</label>
                                <input type="text" class="input-control" value="₱ 0.00" readonly style="background:#f8fafc;">
                            </div>
                        </div>

                        <!-- Cash Fields (Default) -->
                        <div id="fields-cash" class="payment-cash active row-2">
                            <div class="input-group">
                                <label>Account Received<span>*</span></label>
                                <input type="text" class="input-control" placeholder="₱ 0.00">
                            </div>
                            <div class="input-group">
                                <label>Change</label>
                                <input type="text" class="input-control" value="₱ 0.00" readonly style="background:#f8fafc;">
                            </div>
                        </div>

                        <!-- GCash Fields (Hidden by default) -->
                        <div id="fields-gcash" class="payment-gcash row-2">
                            <div class="input-group">
                                <label>Account Name<span>*</span></label>
                                <input type="text" class="input-control" placeholder="Sender Name">
                            </div>
                            <div class="input-group">
                                <label>Gcash Number</label>
                                <input type="text" class="input-control" placeholder="09XXXXXXXXX">
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="btn-cancel" onclick="hideForm()">Cancel</button>
                        <button class="btn-save" onclick="hideForm()">Save</button>
                    </div>

                </div>
            </div>
        </div>

    </main>

    <script>
        function showForm() {
            document.getElementById('list-view').style.display = 'none';
            document.getElementById('form-view').style.display = 'block';
            document.getElementById('page-subtitle').innerText = 'New Walk - In Reservation';
        }

        function hideForm() {
            document.getElementById('list-view').style.display = 'block';
            document.getElementById('form-view').style.display = 'none';
            document.getElementById('page-subtitle').innerText = '';
        }

        function togglePaymentFields() {
            var method = document.getElementById('paymentMethod').value;
            
            if (method === 'gcash') {
                document.getElementById('fields-cash').classList.remove('active');
                document.getElementById('fields-gcash').classList.add('active');
            } else {
                document.getElementById('fields-gcash').classList.remove('active');
                document.getElementById('fields-cash').classList.add('active');
            }
        }

        function selectTime(element) {
            var slots = document.getElementsByClassName('time-slot');
            for(var i = 0; i < slots.length; i++) {
                slots[i].classList.remove('active');
            }
            element.classList.add('active');
        }
    </script>
</body>
</html>