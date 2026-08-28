<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Batangas Badminton</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .calendar-title { font-size: 20px; font-weight: 700; color: var(--dark-blue); }
        .calendar-days { display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; gap: 5px; margin-bottom: 10px; }
        .cal-day-name { font-size: 13px; font-weight: 500; color: var(--text-muted); margin-bottom: 5px; }
        .cal-date { width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; margin: auto; border-radius: 50%; font-size: 13px; color: #d1d5db; }
        .cal-date.active { background-color: var(--primary-blue); color: white; font-weight: bold; }
        .cal-date.current-month { color: var(--text-main); }

        /* Bottom Row (Today Reservation + Upcoming) */
        .bottom-row { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; }
        
        .reservation-list { display: flex; flex-direction: column; gap: 15px; }
        .empty-state { text-align: center; padding: 40px 20px; color: var(--text-muted); font-style: italic; background: #f8fafc; border-radius: 12px; font-size: 14px; }

        .upcoming-table { width: 100%; border-collapse: collapse; }
        .upcoming-table th { text-align: left; font-size: 12px; color: var(--text-muted); font-weight: 500; padding-bottom: 15px; border-bottom: 1px solid var(--border-color); }
        .upcoming-table td { padding: 15px 0; font-size: 14px; color: var(--text-main); border-bottom: 1px solid #f1f5f9; }
        
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
                <i class="fa-regular fa-bell"></i>
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
                <div class="card kpi-card">
                    <i class="fa-solid fa-shoe-prints kpi-icon" style="color: #67e8f9;"></i>
                    <div class="kpi-data">
                        <div class="kpi-label">Total Walk - In</div>
                        <div class="kpi-value">0</div>
                    </div>
                </div>
                <div class="card kpi-card">
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
                <div>
                    <div class="section-title">Court Status</div>
                    <div class="courts-grid">
                        <div class="card court-card">
                            <div class="court-name">Court 1</div>
                            <div class="court-status status-vacant">Vacant</div>
                            <div class="court-time">Start Time: --<br>End Time: --</div>
                        </div>
                        <div class="card court-card">
                            <div class="court-name">Court 2</div>
                            <div class="court-status status-vacant">Vacant</div>
                            <div class="court-time">Start Time: --<br>End Time: --</div>
                        </div>
                        <div class="card court-card">
                            <div class="court-name">Court 3</div>
                            <div class="court-status status-vacant">Vacant</div>
                            <div class="court-time">Start Time: --<br>End Time: --</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="calendar-header">
                        <div class="calendar-title">February 2026</div>
                    </div>
                    <div class="calendar-days">
                        <div class="cal-day-name">Mo</div><div class="cal-day-name">Tu</div><div class="cal-day-name">We</div><div class="cal-day-name">Th</div><div class="cal-day-name">Fr</div><div class="cal-day-name">Sa</div><div class="cal-day-name">Su</div>
                        <div class="cal-date">23</div><div class="cal-date">24</div><div class="cal-date active">25</div><div class="cal-date current-month">26</div><div class="cal-date current-month">27</div><div class="cal-date current-month">28</div><div class="cal-date current-month">1</div>
                    </div>
                    <hr style="border: 0; border-top: 2px solid var(--dark-blue); margin-top: 20px;">
                </div>
            </div>

            <!-- Bottom Row -->
            <div class="bottom-row">
                <div>
                    <div class="section-title">Today Reservation</div>
                    <div class="card reservation-list">
                        <div class="empty-state">No reservations for today.</div>
                    </div>
                </div>

                <div>
                    <div class="section-title">Upcoming</div>
                    <div class="card">
                        <table class="upcoming-table">
                            <thead>
                                <tr>
                                    <th>Names</th>
                                    <th>Court</th>
                                    <th>Time</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" style="text-align: center; color: var(--text-muted); font-style: italic;">No upcoming reservations.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
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
</script>

</body>
</html>



