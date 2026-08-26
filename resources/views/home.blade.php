<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Court Reserve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #0033cc;
            --light-blue: #e6edff;
            --text-dark: #333;
            --text-gray: #777;
            --bg-gray: #f4f7f6;
            --success-green: #28a745;
            --success-light: #d4edda;
            --warning-orange: #ffc107;
            --warning-light: #fff3cd;
            --danger-red: #dc3545;
            --danger-light: #f8d7da;
        }

        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; background-color: var(--bg-gray); display: flex; height: 100vh; overflow: hidden; }

        /* Sidebar Setup */
        .sidebar { width: 250px; background-color: #f8f9fa; border-right: 1px solid #ddd; display: flex; flex-direction: column; }
        .logo-container { padding: 20px; text-align: center; border-bottom: 1px solid #ddd; }
        .logo-container h2 { color: var(--primary-blue); margin: 0; font-size: 20px; }
        
        .nav-menu { list-style: none; padding: 0; margin: 20px 0; flex-grow: 1; }
        .nav-menu li { margin-bottom: 5px; }
        .nav-menu a { display: flex; align-items: center; padding: 15px 30px; color: var(--primary-blue); text-decoration: none; font-size: 16px; font-weight: 500; transition: 0.2s; }
        .nav-menu a i { margin-right: 15px; font-size: 20px; width: 20px; text-align: center; }
        .nav-menu a:hover, .nav-menu a.active { background-color: var(--light-blue); border-left: 4px solid var(--primary-blue); }

        .logout-container { padding: 20px 30px; }
        .btn-logout { background: none; border: none; color: var(--primary-blue); font-size: 16px; font-weight: 500; cursor: pointer; display: flex; align-items: center; }
        .btn-logout i { margin-right: 15px; font-size: 20px; }

        /* Main Content Setup */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .top-header { padding: 20px 40px; display: flex; justify-content: space-between; align-items: center; background-color: transparent; }
        .top-header h1 { color: var(--primary-blue); margin: 0; font-size: 28px; }
        .bell-icon { color: var(--primary-blue); font-size: 24px; cursor: pointer; }

        .dashboard-container { padding: 0 40px 40px 40px; width: 100%; box-sizing: border-box; }

        /* Welcome Card */
        .welcome-card { background: white; border-radius: 12px; padding: 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 25px; border: 1px solid #eaeaea; }
        .welcome-text h2 { margin: 0; font-weight: normal; color: var(--primary-blue); font-size: 20px;}
        .welcome-text h2 strong { font-size: 32px; display: block; margin-top: 5px; }
        .welcome-text p { color: var(--text-gray); margin: 5px 0 0 0; }
        
        .sport-buttons { display: flex; gap: 15px; }
        .sport-btn { background: white; border: 1px solid #ddd; padding: 15px 30px; border-radius: 10px; font-size: 18px; font-weight: bold; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.05); display: flex; flex-direction: column; align-items: center; gap: 10px; transition: 0.2s; text-decoration: none; }
        .sport-btn i { font-size: 30px; }
        .sport-btn.badminton { color: var(--primary-blue); }
        .sport-btn.pickleball { color: #f39c12; }
        .sport-btn:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.1); }

        /* Grid Layout */
        .reservations-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; }
        .panel { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #eaeaea; min-height: 300px; display: flex; flex-direction: column; }
        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
        .panel-header h3 { margin: 0; color: var(--primary-blue); font-size: 18px; }
        
        /* Empty State */
        .empty-state { flex-grow: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 20px; }
        .empty-state i { font-size: 50px; color: #e0e0e0; margin-bottom: 15px; }
        .empty-state h4 { color: var(--text-dark); margin: 0 0 8px 0; font-size: 18px; }
        .empty-state p { color: var(--text-gray); margin: 0 0 20px 0; font-size: 14px; }

        /* Buttons */
        .btn-primary-solid { background: var(--primary-blue); color: white; border: none; padding: 12px 25px; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 15px; transition: 0.2s; }
        .btn-primary-solid:hover { background-color: #002299; }

        /* Mobile App Navigation Override */
        @media (max-width: 768px) {
            body { 
                flex-direction: column; 
                overflow-x: hidden;
                overflow-y: auto;
            }

            .welcome-card { 
                flex-direction: column !important;
                align-items: center !important;
                text-align: center !important;
                gap: 20px !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            .sport-buttons {
                width: 100%;
                justify-content: center;
                gap: 15px;
            }
            .sport-btn {
                flex: 1; 
                padding: 15px 10px;
            }

            .reservations-grid { 
                display: flex !important;
                flex-direction: column !important;
                gap: 20px !important;
            }
            
            .panel { 
                width: 100% !important; 
                box-sizing: border-box !important; 
            }

            .dashboard-container {
                padding: 0 !important;
                width: 100% !important;
            }
            
            .top-header {
                padding: 10px 0px 20px 0px !important; 
            }

            .sidebar {
                position: fixed !important; 
                bottom: 0 !important; 
                left: 0 !important; 
                width: 100% !important; 
                height: 70px !important;
                flex-direction: row !important; 
                border-right: none !important; 
                border-top: 1px solid #ddd !important;
                z-index: 1000 !important; 
                padding: 0 !important;
                box-shadow: 0 -2px 10px rgba(0,0,0,0.05) !important;
                background-color: white !important;
            }
            
            .logo-container, .logout-container { 
                display: none !important; 
            }
            
            .nav-menu { 
                display: flex !important; 
                flex-direction: row !important; 
                margin: 0 !important; 
                width: 100% !important; 
                justify-content: space-around !important; 
                align-items: center !important; 
            }
            
            .nav-menu a { 
                padding: 10px !important; 
                flex-direction: column !important; 
                font-size: 11px !important; 
                border-left: none !important; 
                color: #777 !important;
            }
            
            .nav-menu a i { 
                margin-right: 0 !important; 
                margin-bottom: 4px !important; 
                font-size: 20px !important; 
            }
            
            .nav-menu a:hover, .nav-menu a.active { 
                border-left: none !important; 
                background: transparent !important; 
                color: var(--primary-blue) !important; 
            }

            .main-content { 
                padding: 15px !important;
                padding-bottom: 120px !important; 
            }
        }
    </style>
</head>
<body>
    
    <aside class="sidebar">
        <div class="logo-container">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="max-width: 150px;">
        </div>
        
        <ul class="nav-menu">
            <li><a href="#" class="active"><i class="fa-solid fa-house"></i> Home</a></li>
            <li><a href="{{ route('reservation.index') }}"><i class="fa-regular fa-calendar-plus"></i> Reservation</a></li>
            <li><a href="{{ route('history.index') }}"><i class="fa-solid fa-clock-rotate-left"></i> History</a></li>
            <li><a href="{{ route('profile.index') }}"><i class="fa-regular fa-user"></i> Profile</a></li>
        </ul>
        <div class="logout-container">
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="btn-logout"><i class="fa-solid fa-arrow-right-from-bracket"></i> Logout</button>
            </form>
        </div>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <h1>Home</h1>
            <i class="fa-regular fa-bell bell-icon"></i>
        </header>

        <div class="dashboard-container">
            
            <div class="welcome-card">
                <div class="welcome-text">
                    <h2>Welcome back, <strong>{{ Auth::user()->name ?? 'Player' }}!</strong></h2>
                    <p>What sport are you playing today?</p>
                </div>
                <div class="sport-buttons">
                    <a href="{{ route('reservation.index') }}?sport=Badminton" class="sport-btn badminton">
                        <img src="{{ asset('images/shuttlecock.png') }}" alt="Badminton Icon" width="60" style="margin-bottom: 5px;">
                        Badminton
                    </a>
                    <a href="{{ route('reservation.index') }}?sport=Pickleball" class="sport-btn pickleball">
                        <i class="fa-solid fa-table-tennis-paddle-ball" style="font-size: 45px; margin-bottom: 5px;"></i>
                        Pickleball
                    </a>
                </div>
            </div>

            <div class="reservations-grid">
                
                <!-- Today's Reservation Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3>Today's Reservation</h3>
                    </div>
                    
                    @forelse($todayReservations as $res)
                        <div style="border: 1px solid #eee; border-radius: 8px; padding: 15px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h4 style="margin: 0; color: var(--primary-blue); display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-shuttlecock"></i> Court {{ $res->court_id }}
                                </h4>
                                <span style="font-size: 13px; color: var(--text-gray);">
                                    {{ \Carbon\Carbon::parse($res->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('h:i A') }}
                                </span>
                            </div>
                            <span style="padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; 
                                background-color: {{ $res->status == 'confirmed' ? '#d1fae5' : ($res->status == 'cancelled' ? '#fee2e2' : '#fef3c7') }}; 
                                color: {{ $res->status == 'confirmed' ? '#059669' : ($res->status == 'cancelled' ? '#dc2626' : '#d97706') }};">
                                {{ ucfirst($res->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fa-regular fa-calendar-xmark"></i>
                            <h4>No Reservations Today</h4>
                            <p>You don't have any courts booked for today.</p>
                            <a href="{{ route('reservation.index') }}" style="text-decoration: none;">
                                <button class="btn-primary-solid">Book a Court</button>
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Upcoming Reservation Panel -->
                <div class="panel">
                    <div class="panel-header">
                        <h3>Upcoming Reservation</h3>
                    </div>
                    
                    @forelse($upcomingReservations as $res)
                        <div style="border: 1px solid #eee; border-radius: 8px; padding: 15px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h4 style="margin: 0; color: var(--primary-blue); display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-shuttlecock"></i> Court {{ $res->court_id }}
                                </h4>
                                <span style="font-size: 13px; color: var(--text-gray);">
                                    {{ \Carbon\Carbon::parse($res->start_time)->format('M d, Y | h:i A') }}
                                </span>
                            </div>
                            <span style="padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; 
                                background-color: {{ $res->status == 'confirmed' ? '#d1fae5' : ($res->status == 'cancelled' ? '#fee2e2' : '#fef3c7') }}; 
                                color: {{ $res->status == 'confirmed' ? '#059669' : ($res->status == 'cancelled' ? '#dc2626' : '#d97706') }};">
                                {{ ucfirst($res->status) }}
                            </span>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fa-regular fa-calendar"></i>
                            <h4>No Upcoming Games</h4>
                            <p>Your future court reservations will appear here.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </main>

</body>
</html>