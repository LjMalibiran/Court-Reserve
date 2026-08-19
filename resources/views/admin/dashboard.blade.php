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
                <i class="fa-regular fa-bell"></i>
            </div>
        </header>

        <div class="dashboard-grid">
            
            <div class="left-column">
                
                <div class="stats-container">
                    <div class="stat-card">
                        <div class="stat-icon icon-blue"><i class="fa-regular fa-calendar"></i></div>
                        <div class="stat-details">
                            <h3>Total Reserved</h3>
                            <p class="number">{{ $totalReserved ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-teal"><i class="fa-solid fa-shoe-prints"></i></div>
                        <div class="stat-details">
                            <h3>Total Walk - In</h3>
                            <p class="number">0</p>
                            <span class="trend"><i class="fa-solid fa-minus"></i> No data yet</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-blue"><i class="fa-solid fa-user-group"></i></div>
                        <div class="stat-details">
                            <h3>Total User</h3>
                            <p class="number">{{ $totalUsers ?? 0 }}</p>
                            <span class="trend"><i class="fa-solid fa-minus"></i> Registered</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon icon-indigo"><i class="fa-solid fa-file-invoice"></i></div>
                        <div class="stat-details">
                            <h3>Pending</h3>
                            <p class="number">{{ $pending ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="section-title">Court Status</h2>
                    <div class="courts-container">
                        <!-- Court 1 -->
                        <div class="court-card active-border">
                            <h3>Court 1</h3>
                            <div class="status-badge status-vacant">Vacant</div>
                            <div class="time-info">
                                <div class="time-row"><span>Start Time:</span> <strong>--</strong></div>
                                <div class="time-row"><span>End Time:</span> <strong>--</strong></div>
                            </div>
                        </div>
                        <!-- Court 2 -->
                        <div class="court-card">
                            <h3>Court 2</h3>
                            <div class="status-badge status-vacant">Vacant</div>
                            <div class="time-info">
                                <div class="time-row"><span>Start Time:</span> <strong>--</strong></div>
                                <div class="time-row"><span>End Time:</span> <strong>--</strong></div>
                            </div>
                        </div>
                        <!-- Court 3 -->
                        <div class="court-card">
                            <h3>Court 3</h3>
                            <div class="status-badge status-vacant">Vacant</div>
                            <div class="time-info">
                                <div class="time-row"><span>Start Time:</span> <strong>--</strong></div>
                                <div class="time-row"><span>End Time:</span> <strong>--</strong></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="section-title">Today's Confirmed Reservations</h2>
                    <div class="today-reservations">
                        
                        <!-- DYNAMIC LOOP FOR TODAY -->
                        @forelse($todayReservations ?? [] as $res)
                            <div class="reservation-row">
                                <div>
                                    <h4 style="margin: 0; color: var(--dark-blue); font-size: 15px; font-weight: 600;">{{ $res->user->name ?? 'Walk-In Customer' }}</h4>
                                    <p style="margin: 5px 0 0 0; font-size: 12px; color: var(--text-muted);"><i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }} &nbsp; • &nbsp; <i class="fa-solid fa-map-location-dot"></i> Court {{ $res->court_id }}</p>
                                </div>
                                <div class="status-badge status-play" style="margin: 0; padding: 6px 15px; font-size: 12px;">Confirmed</div>
                            </div>
                        @empty
                            <!-- Empty State Message -->
                            <div class="empty-state">
                                <i class="fa-regular fa-calendar-xmark"></i>
                                <p>No reservations scheduled for today.</p>
                            </div>
                        @endforelse

                    </div>
                </div>

            </div>

            <div class="right-column">
                <h2 class="calendar-header">{{ date('F Y') }}</h2>
                
                @php
                    $startOfWeek = now()->startOfWeek();
                @endphp
                <div class="calendar-grid">
                    @for ($i = 0; $i < 7; $i++)
                        @php
                            $day = $startOfWeek->copy()->addDays($i);
                            $isToday = $day->isToday() ? 'active' : '';
                        @endphp
                        <div class="cal-day {{ $isToday }}">
                            {{ substr($day->format('D'), 0, 2) }}
                            <span class="cal-date">{{ $day->format('d') }}</span>
                        </div>
                    @endfor
                </div>

                <h2 class="upcoming-header">Upcoming</h2>
                
                <table class="upcoming-table">
                    <thead>
                        <tr>
                            <th>Names</th>
                            <th style="text-align: center;">Court</th>
                            <th>Time</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- DYNAMIC LOOP FOR UPCOMING -->
                        @forelse($upcomingReservations ?? [] as $res)
                            <tr>
                                <td style="font-weight: 500; color: var(--dark-blue);">{{ $res->user->name ?? 'Walk-In Customer' }}</td>
                                <td style="text-align: center;"><span style="background: #e3f2fd; color: #1557c0; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">Court {{ $res->court_id }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }}</td>
                                <td>{{ \Carbon\Carbon::parse($res->start_time)->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <!-- Empty State Message -->
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 30px 10px; color: var(--text-muted);">
                                    No upcoming reservations found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </main>
</body>
</html>