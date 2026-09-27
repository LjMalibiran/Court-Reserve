<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Batangas Badminton</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { 
            --primary-blue: #1557c0;
            --dark-blue: #002277;
            --bg-color: #f4f6f9;
            --card-bg: #ffffff;
            --text-main: #333333;
            --text-muted: #777777;
            --success-bg: #dcedc8;
            --success-text: #2e7d32;
            --purple-border: #8e24aa;
        }
        
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: var(--bg-color); display: flex; height: 100vh; overflow: hidden; }

        /* --- MAIN CONTENT --- */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 30px; }
        
        /* Header */
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .top-header h1 { margin: 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; }
        .header-right { display: flex; align-items: center; gap: 20px; color: var(--dark-blue); font-weight: 500; font-size: 14px; }
        .header-right i { font-size: 20px; cursor: pointer; }

        /* Main Grid Layout */
        .dashboard-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
        @media (max-width: 1100px) { .dashboard-grid { grid-template-columns: 1fr; } }

        .section-title { font-size: 15px; color: var(--text-muted); font-weight: 700; margin-bottom: 15px; margin-top: 0; }

        /* --- LEFT COLUMN --- */
        .left-column { display: flex; flex-direction: column; gap: 30px; }

        /* Top Stat Cards */
        .stats-container { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; }
        .stat-card { background: var(--card-bg); padding: 15px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 12px; }
        .stat-icon { width: 45px; height: 45px; border-radius: 10px; display: flex; justify-content: center; align-items: center; font-size: 20px; flex-shrink: 0; }
        .icon-blue { background: #e3f2fd; color: #1976d2; }
        .icon-teal { background: #e0f2f1; color: #00796b; }
        .icon-indigo { background: #e8eaf6; color: #3949ab; }
        .stat-details { overflow: hidden; }
        .stat-details h3 { margin: 0; font-size: 12px; color: var(--text-muted); font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .stat-details .number { margin: 2px 0 0 0; font-size: 26px; font-weight: 700; color: var(--dark-blue); line-height: 1; }
        .stat-details .trend { font-size: 9px; color: var(--text-muted); margin-top: 5px; display: block; white-space: nowrap; }

        /* Court Status */
        .courts-container { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; }
        .court-card { background: var(--card-bg); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 20px 15px; text-align: center; border: 2px solid transparent; display: flex; flex-direction: column; align-items: center; }
        .court-card h3 { margin: 0 0 12px 0; color: var(--dark-blue); font-size: 20px; font-weight: 700; }
        
        .status-badge { padding: 6px 20px; border-radius: 20px; font-size: 14px; font-weight: 600; margin-bottom: 20px; display: inline-block; width: fit-content; }
        .status-vacant { background: #e3f2fd; color: #1557c0; } 
        .status-play { background: var(--success-bg); color: var(--success-text); }
        
        /* Enhanced Time Info Layout */
        .court-card .time-info { width: 100%; display: flex; flex-direction: column; gap: 8px; margin-top: auto; }
        .time-row { display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: var(--text-muted); border-bottom: 1px dashed #f0f0f0; padding-bottom: 4px; }
        .time-row:last-child { border-bottom: none; padding-bottom: 0; }
        .time-row strong { color: var(--dark-blue); font-weight: 600; }
        
        .court-card.active-border { border-color: var(--primary-blue); box-shadow: 0 0 15px rgba(21, 87, 192, 0.1); }

        /* Today's Reservations */
        .today-reservations { display: flex; flex-direction: column; gap: 12px; }
        .reservation-row { background: var(--card-bg); border-radius: 12px; padding: 15px 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); display: flex; align-items: center; justify-content: space-between; }
        
        /* Empty State Styling */
        .empty-state { text-align: center; padding: 30px; background: var(--card-bg); border-radius: 12px; color: var(--text-muted); box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .empty-state i { font-size: 32px; margin-bottom: 10px; color: #ccc; }
        .empty-state p { margin: 0; font-size: 14px; }

        /* --- RIGHT COLUMN --- */
        .right-column { background: var(--card-bg); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 25px; display: flex; flex-direction: column; }
        
        .calendar-header { color: var(--dark-blue); font-size: 22px; font-weight: 700; margin: 0 0 20px 0; }
        
        /* Dynamic Calendar Grid */
        .calendar-grid { display: flex; justify-content: space-between; margin-bottom: 20px; text-align: center; border-bottom: 2px solid var(--primary-blue); padding-bottom: 15px; }
        .cal-day { display: flex; flex-direction: column; gap: 10px; font-size: 13px; color: var(--text-muted); font-weight: 500; }
        .cal-date { font-size: 12px; }
        .cal-day.active .cal-date { background: var(--primary-blue); color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto; font-weight: bold; }
        .cal-day.active { color: var(--primary-blue); }

        .upcoming-header { color: var(--dark-blue); font-size: 20px; font-weight: 600; margin: 20px 0 15px 0; }
        
        /* Upcoming Table */
        .upcoming-table { width: 100%; border-collapse: collapse; }
        .upcoming-table th { text-align: left; padding: 10px 5px; font-size: 11px; color: var(--dark-blue); text-transform: capitalize; font-weight: 500; border-bottom: 2px solid #f0f0f0; }
        .upcoming-table td { padding: 12px 5px; font-size: 13px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0; }
        .upcoming-table tr:last-child td { border-bottom: none; }
    </style>
</head>
<body>

    @include('admin.sidebar')

    <main class="main-content">
        <header class="top-header">
            <h1>Dashboard</h1>
            <div class="header-right">
                <span>{{ date('l, F j, Y') }}</span>
                @include('partials.notif-bell')
                    </div>
                </div>
            </div>
        </header>

        <div class="dashboard-grid">
            
            <div class="left-column">
                
                <div class="stats-container">
                    <div class="stat-card" onclick="window.location.href='{{ url('/admin/reservations') }}'" style="cursor: pointer; transition: 0.2s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 15px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='none'; this.style.boxShadow='none';">
                        <div class="stat-icon icon-blue"><i class="fa-regular fa-calendar"></i></div>
                        <div class="stat-details">
                            <h3>Total Reserved</h3>
                            <p class="number" id="realtime-reserved">{{ $totalReserved ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="stat-card" onclick="window.location.href='{{ url('/admin/walk-in') }}'" style="cursor: pointer; transition: 0.2s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 15px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='none'; this.style.boxShadow='none';">
                        <div class="stat-icon icon-teal"><i class="fa-solid fa-shoe-prints"></i></div>
                        <div class="stat-details">
                            <h3>Total Walk - In</h3>
                            <p class="number">0</p>
                        </div>
                    </div>
                    <div class="stat-card" onclick="document.getElementById('usersModal').style.display='flex'" style="cursor: pointer; transition: 0.2s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 15px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='none'; this.style.boxShadow='none';">
                        <div class="stat-icon icon-green" style="background: #e8f5e9; color: #2e7d32;"><i class="fa-solid fa-users"></i></div>
                        <div class="stat-details">
                            <h3>Total Users</h3>
                            <p class="number" id="realtime-users">{{ $totalUsers ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="stat-card" onclick="window.location.href='{{ url('/admin/reservations?tab=pending') }}'" style="cursor: pointer; transition: 0.2s;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 6px 15px rgba(0,0,0,0.1)';" onmouseout="this.style.transform='none'; this.style.boxShadow='none';">
                        <div class="stat-icon icon-orange"><i class="fa-solid fa-clock-rotate-left"></i></div>
                        <div class="stat-details">
                            <h3>Pending</h3>
                            <p class="number" id="realtime-pending">{{ $pendingReservations ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div style="background: var(--card-bg); padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 class="section-title" style="margin: 0; display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-chart-line" style="color: #2e7d32;"></i> Total Sales</h2>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input type="date" id="adminSalesStart" style="padding: 8px; border-radius: 6px; border: 1px solid #ddd; font-size: 13px; color: var(--text-main); outline: none;">
                            <span style="font-size: 13px; color: var(--text-muted);">to</span>
                            <input type="date" id="adminSalesEnd" style="padding: 8px; border-radius: 6px; border: 1px solid #ddd; font-size: 13px; color: var(--text-main); outline: none;">
                            <button onclick="fetchAdminSales()" style="background: var(--primary-blue); color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500; transition: 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">Filter</button>
                        </div>
                    </div>
                    <div style="text-align: center; padding: 20px 0;">
                        <p style="margin: 0; font-size: 14px; color: var(--text-muted); font-weight: 600;">Revenue</p>
                        <h3 id="adminTotalSales" style="margin: 5px 0 15px 0; font-size: 42px; color: var(--dark-blue); font-weight: bold;">Loading...</h3>
                        <div style="position: relative; height: 200px; width: 100%;">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>

            <div class="right-column">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h2 class="calendar-header" id="calendarMonthYear" style="margin: 0;">{{ date('F Y') }}</h2>
                    <div style="display: flex; gap: 5px;">
                        <button onclick="changeMonth(-1)" style="background: white; border: 1px solid #ddd; border-radius: 4px; padding: 5px 10px; cursor: pointer; color: var(--dark-blue); transition: 0.2s;" onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='white'"><i class="fa-solid fa-chevron-left"></i></button>
                        <button onclick="changeMonth(1)" style="background: white; border: 1px solid #ddd; border-radius: 4px; padding: 5px 10px; cursor: pointer; color: var(--dark-blue); transition: 0.2s;" onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='white'"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
                
                <div class="calendar-grid" id="calendarGrid">
                    <!-- Populated by JS -->
                </div>

                <h2 class="upcoming-header">Confirmed Reservations</h2>
                
                <table class="upcoming-table">
                    <thead>
                        <tr>
                            <th>Names</th>
                            <th style="text-align: center;">Court</th>
                            <th style="text-align: center;">Time</th>
                            <th style="text-align: right;">Date</th>
                            <th style="text-align: right; width: 40px;"></th>
                        </tr>
                    </thead>
                    <tbody id="upcomingTableBody">
                        <tr>
                            <td colspan="4" style="text-align: center; color: #9ca3af; padding: 20px;">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </main>
    <script>
    // Real-Time Dashboard Metrics (Updates every 3 seconds)
    setInterval(function() {
        let currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('t', new Date().getTime()); // Cache buster
        
        fetch(currentUrl.toString())
            .then(response => response.text())
            .then(html => {
                let parser = new DOMParser();
                let doc = parser.parseFromString(html, 'text/html');
                
                // The exact IDs we assigned to the numbers
                const metrics = ['realtime-reserved', 'realtime-users', 'realtime-pending'];
                
                metrics.forEach(id => {
                    let newElement = doc.getElementById(id);
                    let currentElement = document.getElementById(id);
                    
                    // If the number changed in the database, smoothly update the screen
                    if (newElement && currentElement && currentElement.innerHTML !== newElement.innerHTML) {
                        currentElement.innerHTML = newElement.innerHTML;
                    }
                });
            })
            .catch(error => console.log('Polling error, waiting for next cycle...'));
    }, 3000); 

    // Initial load
    fetchAdminSales();

    let salesChartInstance = null;

    function fetchAdminSales() {
        let start = document.getElementById('adminSalesStart').value;
        let end = document.getElementById('adminSalesEnd').value;
        let url = '/admin/sales/filter';
        if (start && end) {
            url += `?start_date=${start}&end_date=${end}`;
        }
        
        fetch(url)
            .then(res => res.json())
            .then(data => {
                document.getElementById('adminTotalSales').innerText = data.total;
                
                // Render Chart
                const ctx = document.getElementById('salesChart').getContext('2d');
                if (salesChartInstance) {
                    salesChartInstance.destroy();
                }
                
                salesChartInstance = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Sales (₱)',
                            data: data.data,
                            borderColor: '#1557c0',
                            backgroundColor: 'rgba(21, 87, 192, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: '#1557c0'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true,
                                grid: { color: '#f0f0f0' }
                            },
                            x: {
                                grid: { display: false }
                            }
                        }
                    }
                });
            })
            .catch(err => {
                console.error(err);
                document.getElementById('adminTotalSales').innerText = 'Error';
            });
    }

    // Call fetch for today on initial load
    let currentMonthOffset = 0;
    
    function changeMonth(offset) {
        currentMonthOffset += offset;
        renderCalendarMonth();
    }

    function renderCalendarMonth() {
        let today = new Date();
        let targetMonth = new Date(today.getFullYear(), today.getMonth() + currentMonthOffset, 1);
        
        let monthYearStr = targetMonth.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        document.getElementById('calendarMonthYear').innerText = monthYearStr;
        
        let html = '';
        let weekDays = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
        
        // Add header row for days of the week
        for (let w of weekDays) {
            html += `<div style="font-size: 13px; color: var(--text-muted); font-weight: bold; margin-bottom: 10px; text-align: center;">${w}</div>`;
        }
        
        let firstDayIndex = targetMonth.getDay();
        let daysInMonth = new Date(targetMonth.getFullYear(), targetMonth.getMonth() + 1, 0).getDate();
        
        let realTodayDate = new Date();
        let realTodayStr = `${realTodayDate.getFullYear()}-${String(realTodayDate.getMonth()+1).padStart(2,'0')}-${String(realTodayDate.getDate()).padStart(2,'0')}`;
        
        let firstDayToFetch = null;

        // Pad empty days
        for (let i = 0; i < firstDayIndex; i++) {
            html += `<div></div>`;
        }

        for (let i = 1; i <= daysInMonth; i++) {
            let currentDay = new Date(targetMonth.getFullYear(), targetMonth.getMonth(), i);
            
            let m = currentDay.getMonth() + 1;
            let d = currentDay.getDate();
            let y = currentDay.getFullYear();
            let formattedDate = `${y}-${m < 10 ? '0'+m : m}-${d < 10 ? '0'+d : d}`;
            
            if (i === 1) firstDayToFetch = formattedDate;

            let isToday = (formattedDate === realTodayStr) ? 'active' : '';
            if (currentMonthOffset !== 0 && i === 1) {
                isToday = 'active'; 
            } else if (currentMonthOffset !== 0 && isToday === 'active') {
                isToday = ''; // Should not happen since we're not in current month, but just in case
            }
            if (currentMonthOffset !== 0 && formattedDate !== firstDayToFetch) {
                 isToday = '';
            }
            
            html += `<div class="cal-day ${isToday}" onclick="fetchReservationsByDate('${formattedDate}', this)" style="cursor: pointer; transition: 0.2s; align-items: center;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='none'">
                        <span class="cal-date">${d}</span>
                     </div>`;
        }
        
        let grid = document.getElementById('calendarGrid');
        grid.innerHTML = html;
        grid.style.display = 'grid';
        grid.style.gridTemplateColumns = 'repeat(7, 1fr)';
        grid.style.rowGap = '15px';
        grid.style.borderBottom = 'none';
        grid.style.paddingBottom = '0';
        
        let dayToFetch = (currentMonthOffset === 0) ? realTodayStr : firstDayToFetch;
        fetchReservationsByDate(dayToFetch, null);
    }
    
    // Initial Render
    renderCalendarMonth();

    function fetchReservationsByDate(date, element) {
        // Handle active state
        if (element) {
            document.querySelectorAll('.cal-day').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
        }

        let tbody = document.getElementById('upcomingTableBody');
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: #9ca3af; padding: 20px;">Loading...</td></tr>';

        fetch(`/api/reservations/by-date?date=${date}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: #9ca3af; padding: 20px;">No reservations found for this date.</td></tr>';
                    return;
                }

                let html = '';
                data.forEach(res => {
                    html += `
                        <tr>
                            <td style="font-weight: 500; color: var(--dark-blue);">${res.name}</td>
                            <td style="text-align: center;"><span style="background: var(--primary-blue); color: #ffffff; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; white-space: nowrap; display: inline-block;">${res.court}</span></td>
                            <td style="text-align: center;">${res.time}</td>
                            <td style="text-align: right;">${res.date}</td>
                            <td style="text-align: right;">
                                <button onclick="sendReminder(${res.id}, this)" title="Send Reminder Email" style="background: none; border: none; cursor: pointer; color: #f59e0b; font-size: 16px; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.2)'" onmouseout="this.style.transform='none'">
                                    <i class="fa-solid fa-bell"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: #e53935; padding: 20px;">Error loading data.</td></tr>';
            });
    }

    function sendReminder(id, btn) {
        let originalIcon = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
        btn.disabled = true;

        fetch(`/admin/reservations/${id}/remind`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                btn.innerHTML = '<i class="fa-solid fa-check" style="color: #10b981;"></i>';
                setTimeout(() => {
                    btn.innerHTML = originalIcon;
                    btn.disabled = false;
                }, 2000);
            } else {
                alert(data.message || 'Error sending reminder.');
                btn.innerHTML = originalIcon;
                btn.disabled = false;
            }
        })
        .catch(err => {
            console.error(err);
            alert('Server error.');
            btn.innerHTML = originalIcon;
            btn.disabled = false;
        });
    }
</script>
    <!-- USERS MODAL -->
    <div id="usersModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
        <div style="background: white; width: 90%; max-width: 800px; max-height: 85vh; border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 10px 30px rgba(0,0,0,0.2); animation: modalFadeIn 0.3s ease;">
            <!-- Modal Header -->
            <div style="padding: 20px 25px; border-bottom: 1px solid #eef0f4; display: flex; justify-content: space-between; align-items: center; background: #fafbfc;">
                <h2 style="margin: 0; color: var(--dark-blue); font-size: 20px;"><i class="fa-solid fa-users" style="margin-right: 10px; color: #2e7d32;"></i>Registered Users</h2>
                <button onclick="document.getElementById('usersModal').style.display='none'" style="background: none; border: none; font-size: 24px; color: #999; cursor: pointer; transition: 0.2s;" onmouseover="this.style.color='#f44336'" onmouseout="this.style.color='#999'">&times;</button>
            </div>
            
            <!-- Modal Body (Table) -->
            <div style="padding: 0; overflow-y: auto; flex-grow: 1;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead style="position: sticky; top: 0; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                        <tr>
                            <th style="padding: 15px 25px; font-size: 13px; color: #6b7280; border-bottom: 1px solid #eef0f4;">Name</th>
                            <th style="padding: 15px 25px; font-size: 13px; color: #6b7280; border-bottom: 1px solid #eef0f4;">Email / Contact</th>
                            <th style="padding: 15px 25px; font-size: 13px; color: #6b7280; border-bottom: 1px solid #eef0f4;">Joined Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registeredUsers ?? [] as $user)
                        <tr style="border-bottom: 1px solid #f9f9f9; transition: 0.2s;" onmouseover="this.style.backgroundColor='#f4f6f9'" onmouseout="this.style.backgroundColor='transparent'">
                            <td style="padding: 15px 25px;">
                                <div style="font-weight: 600; color: #374151; font-size: 14px;">{{ $user->name }}</div>
                            </td>
                            <td style="padding: 15px 25px;">
                                <div style="font-size: 13px; color: #4b5563;"><i class="fa-regular fa-envelope" style="margin-right: 6px; color: #9ca3af;"></i>{{ $user->email }}</div>
                                <div style="font-size: 13px; color: #6b7280; margin-top: 4px;"><i class="fa-solid fa-phone" style="margin-right: 6px; color: #9ca3af;"></i>{{ $user->contact }}</div>
                            </td>
                            <td style="padding: 15px 25px; font-size: 13px; color: #6b7280;">
                                {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 40px; color: #9ca3af;">No registered users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <style>
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-20px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>
@include('partials.notif-script')
</body>
</html>



