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
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --success-bg: #dcfce7;
            --success-text: #166534;
            --neutral-bg: #f3f4f6;
            --neutral-text: #374151;
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
        .user-profile-section { margin-top: auto; padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .profile-info { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; text-decoration: none; color: white; }
        .profile-avatar { width: 40px; height: 40px; background-color: white; color: var(--primary-blue); border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 18px; font-weight: bold; }
        .profile-name { font-size: 14px; font-weight: bold; }
        .profile-role { font-size: 11px; color: rgba(255,255,255,0.7); }
        .btn-logout { width: 100%; display: flex; align-items: center; gap: 10px; background: transparent; border: none; color: white; padding: 10px 0; cursor: pointer; font-size: 14px; font-weight: 500; }
        
        /* --- MAIN CONTENT --- */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; padding: 30px; }
        .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .top-header h1 { margin: 0; font-size: 32px; color: var(--dark-blue); font-weight: 700; }
        .header-right { display: flex; align-items: center; gap: 20px; color: var(--dark-blue); font-weight: 500; font-size: 15px; }
        
        /* --- DASHBOARD GRID --- */
        .dashboard-grid { display: flex; flex-direction: column; gap: 25px; }
        .card { background: var(--card-bg); border-radius: 16px; border: 1px solid var(--border-color); padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .section-title { font-size: 16px; font-weight: 600; color: var(--text-muted); margin-bottom: 15px; }

        /* KPI Row */
        .kpi-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .kpi-card { display: flex; align-items: center; gap: 20px; justify-content: center; padding: 25px 20px; }
        .kpi-icon { font-size: 35px; color: #93c5fd; }
        .kpi-data { text-align: center; }
        .kpi-label { font-size: 14px; color: var(--text-muted); font-weight: 500; }
        .kpi-value { font-size: 36px; font-weight: 700; color: var(--dark-blue); line-height: 1.2; }
        
        /* Middle Row (Courts + Calendar) */
        .middle-row { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; }
        
        .courts-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .court-card { text-align: center; padding: 30px 20px; border: 1px solid var(--border-color); border-radius: 12px; }
        .court-name { font-size: 22px; font-weight: 700; color: var(--dark-blue); margin-bottom: 15px; }
        .court-status { display: inline-block; padding: 8px 30px; border-radius: 8px; font-size: 18px; font-weight: 600; margin-bottom: 25px; }
        .status-vacant { background-color: var(--neutral-bg); color: var(--primary-blue); }
        .status-play { background-color: var(--success-bg); color: var(--success-text); }
        .court-time { font-size: 13px; color: var(--text-muted); line-height: 1.6; }

        .calendar-header { color: var(--dark-blue); font-size: 22px; font-weight: 700; margin: 0 0 20px 0; }
        
        /* Dynamic Calendar Grid */
        .calendar-grid { display: flex; justify-content: space-between; margin-bottom: 20px; text-align: center; border-bottom: 2px solid var(--primary-blue); padding-bottom: 15px; }
        .cal-day { display: flex; flex-direction: column; gap: 10px; font-size: 13px; color: var(--text-muted); font-weight: 500; }
        .cal-date { font-size: 12px; }
        .cal-day.active .cal-date { background: var(--primary-blue); color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto; font-weight: bold; }
        .cal-day.active { color: var(--primary-blue); }

        .upcoming-header { color: var(--dark-blue); font-size: 20px; font-weight: 600; margin: 20px 0 15px 0; }
        
        .upcoming-table { width: 100%; border-collapse: collapse; }
        .upcoming-table th { text-align: left; padding: 10px 5px; font-size: 11px; color: var(--dark-blue); text-transform: capitalize; font-weight: 500; border-bottom: 2px solid #f0f0f0; }
        .upcoming-table td { padding: 12px 5px; font-size: 13px; color: var(--text-muted); border-bottom: 1px solid #f0f0f0; }
        .upcoming-table tr:last-child td { border-bottom: none; }

        .empty-state { text-align: center; padding: 40px 20px; color: var(--text-muted); font-style: italic; background: #f8fafc; border-radius: 12px; font-size: 14px; }
        
    </style>
</head>
<body>

    @include('cashier.sidebar')
        

    <!-- Main Content -->
    <main class="main-content">
        
        <header class="top-header">
            <h1>Dashboard</h1>
            <div class="header-right">
                <span>{{ now()->timezone('Asia/Manila')->format('l, F j, Y') }}</span>
                @include('partials.notif-bell')
                    </div>
                </div>
            </div>
        </header>

        <div class="dashboard-grid">
            
                                    <!-- KPI Row -->
            <div class="kpi-row">
                <a href="{{ url('/cashier/reservations') }}" style="text-decoration: none; color: inherit; display: block;">
                    <div class="card kpi-card" onclick="window.location.href='{{ url('/cashier/reservations') }}'" style="cursor: pointer; transition: 0.2s;" onmouseover="this.style.transform='translateY(-3px)';" onmouseout="this.style.transform='none';">
                        <i class="fa-solid fa-calendar-days kpi-icon"></i>
                        <div class="kpi-data">
                            <div class="kpi-label">Total Reserved</div>
                            <div class="kpi-value" id="realtime-reserved">{{ $totalReserved ?? 0 }}</div>
                        </div>
                    </div>
                </a>
                <div class="card kpi-card" onclick="window.location.href='{{ url('/cashier/walk-in') }}'" style="cursor: pointer; transition: 0.2s;" onmouseover="this.style.transform='translateY(-3px)';" onmouseout="this.style.transform='none';">
                    <i class="fa-solid fa-shoe-prints kpi-icon" style="color: #67e8f9;"></i>
                    <div class="kpi-data">
                        <div class="kpi-label">Total Walk - In</div>
                        <div class="kpi-value">0</div>
                    </div>
                </div>
                <div class="card kpi-card" onclick="document.getElementById('usersModal').style.display='flex'" style="cursor: pointer; transition: 0.2s;" onmouseover="this.style.transform='translateY(-3px)';" onmouseout="this.style.transform='none';">
                    <i class="fa-solid fa-users kpi-icon"></i>
                    <div class="kpi-data">
                        <div class="kpi-label">Total Users</div>
                        <div class="kpi-value" id="realtime-users">{{ $totalUsers ?? 0 }}</div>
                    </div>
                </div>
                <div class="card kpi-card" onclick="window.location.href='{{ url('/cashier/reservations?tab=pending') }}'" style="cursor: pointer; transition: 0.2s;" onmouseover="this.style.transform='translateY(-3px)';" onmouseout="this.style.transform='none';">
                    <i class="fa-solid fa-clock-rotate-left kpi-icon"></i>
                    <div class="kpi-data">
                        <div class="kpi-label">Pending</div>
                        <div class="kpi-value" id="realtime-pending">{{ $pendingReservations ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <!-- Middle Row -->
            <div class="middle-row">
                <div class="card" style="display: flex; flex-direction: column;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2 class="section-title" style="margin: 0; display: flex; align-items: center; gap: 8px;"><i class="fa-solid fa-calendar-day" style="color: var(--primary-blue);"></i> Today's Reservation</h2>
                    </div>
                    <div style="flex-grow: 1; overflow-y: auto; max-height: 350px;">
                        @if(isset($todayReservations) && $todayReservations->count() > 0)
                        <table class="upcoming-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th style="text-align: center;">Court</th>
                                    <th style="text-align: right;">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($todayReservations as $res)
                                <tr>
                                    <td style="font-weight: 500; color: var(--dark-blue);">{{ $res->walk_in_name ?? ($res->user ? $res->user->name : 'Walk-In') }}</td>
                                    <td style="text-align: center;"><span style="background: var(--primary-blue); color: #ffffff; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; white-space: nowrap; display: inline-block;">Court {{ $res->court_id }}</span></td>
                                    <td style="text-align: right;">{{ \Carbon\Carbon::parse($res->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('h:i A') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="empty-state">
                            No reservations for today.
                        </div>
                        @endif
                    </div>
                </div>

                <div class="right-column" style="background: var(--card-bg); border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); padding: 25px; display: flex; flex-direction: column;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h2 class="calendar-header" id="calendarMonthYear" style="color: var(--dark-blue); font-size: 22px; font-weight: 700; margin: 0;">{{ date('F Y') }}</h2>
                        <div style="display: flex; gap: 5px;">
                            <button onclick="changeMonth(-1)" style="background: white; border: 1px solid #ddd; border-radius: 4px; padding: 5px 10px; cursor: pointer; color: var(--dark-blue); transition: 0.2s;" onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='white'"><i class="fa-solid fa-chevron-left"></i></button>
                            <button onclick="changeMonth(1)" style="background: white; border: 1px solid #ddd; border-radius: 4px; padding: 5px 10px; cursor: pointer; color: var(--dark-blue); transition: 0.2s;" onmouseover="this.style.background='#f0f0f0'" onmouseout="this.style.background='white'"><i class="fa-solid fa-chevron-right"></i></button>
                        </div>
                    </div>
                    
                    <div class="calendar-grid" id="calendarGrid" style="display: flex; justify-content: space-between; margin-bottom: 20px; text-align: center; border-bottom: 2px solid var(--primary-blue); padding-bottom: 15px;">
                        <!-- Populated by JS -->
                    </div>

                    <h2 class="upcoming-header" style="color: var(--dark-blue); font-size: 20px; font-weight: 600; margin: 20px 0 15px 0;">Confirmed Reservations</h2>
                    
                    <table class="upcoming-table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="text-align: left; padding: 10px 5px; font-size: 11px; color: var(--dark-blue); text-transform: capitalize; font-weight: 500; border-bottom: 2px solid #f0f0f0;">Names</th>
                                <th style="text-align: center; padding: 10px 5px; font-size: 11px; color: var(--dark-blue); text-transform: capitalize; font-weight: 500; border-bottom: 2px solid #f0f0f0;">Court</th>
                                <th style="text-align: center; padding: 10px 5px; font-size: 11px; color: var(--dark-blue); text-transform: capitalize; font-weight: 500; border-bottom: 2px solid #f0f0f0;">Time</th>
                                <th style="text-align: right; padding: 10px 5px; font-size: 11px; color: var(--dark-blue); text-transform: capitalize; font-weight: 500; border-bottom: 2px solid #f0f0f0;">Date</th>
                                <th style="text-align: right; width: 40px; border-bottom: 2px solid #f0f0f0;"></th>
                            </tr>
                        </thead>
                        <tbody id="upcomingTableBody">
                            <tr>
                                <td colspan="4" style="text-align: center; color: #9ca3af; padding: 20px; border-bottom: 1px solid #f0f0f0;">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
        tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #9ca3af; padding: 20px;">Loading...</td></tr>';

        fetch(`/api/reservations/by-date?date=${date}`)
            .then(res => res.json())
            .then(data => {
                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #9ca3af; padding: 20px;">No reservations found for this date.</td></tr>';
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
                        </tr>
                    `;
                });
                tbody.innerHTML = html;
            })
            .catch(err => {
                console.error(err);
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #e53935; padding: 20px;">Error loading data.</td></tr>';
            });
    }

</script>

    <!-- USERS MODAL -->
    <div id="usersModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; backdrop-filter: blur(4px);">
        <div style="background: white; width: 90%; max-width: 800px; max-height: 85vh; border-radius: 16px; overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 10px 30px rgba(0,0,0,0.2); animation: modalFadeIn 0.3s ease;">
            <!-- Modal Header -->
            <div style="padding: 20px 25px; border-bottom: 1px solid #eef0f4; display: flex; justify-content: space-between; align-items: center; background: #fafbfc;">
                <h2 style="margin: 0; color: var(--dark-blue); font-size: 20px;"><i class="fa-solid fa-users" style="margin-right: 10px; color: #93c5fd;"></i>Registered Users</h2>
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


















