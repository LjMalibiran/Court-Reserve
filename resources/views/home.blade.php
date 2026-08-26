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

        /* Notifications & Modals CSS */
        .notification-wrapper { position: relative; }
        .notification-badge { position: absolute; top: -5px; right: -5px; background: var(--danger-red); color: white; border-radius: 50%; padding: 2px 6px; font-size: 10px; font-weight: bold; }
        .notification-dropdown { position: absolute; top: 40px; right: 0; width: 300px; background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 1px solid #eee; display: none; z-index: 100; max-height: 400px; overflow-y: auto; }
        .notification-header { padding: 15px; border-bottom: 1px solid #eee; font-weight: bold; color: var(--text-dark); display: flex; justify-content: space-between; }
        .notification-item { padding: 15px; border-bottom: 1px solid #eee; transition: 0.2s; }
        .notification-item:hover { background: #f9f9f9; }
        .notification-item.unread { background: #f0f4ff; }
        .notification-title { font-weight: bold; color: var(--primary-blue); font-size: 14px; margin-bottom: 5px; }
        .notification-msg { font-size: 12px; color: var(--text-gray); margin: 0; line-height: 1.4; }

        /* Card Details & Buttons */
        .res-card { border: 1px solid #eee; border-radius: 12px; padding: 20px; margin-bottom: 15px; position: relative; }
        .res-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; }
        .res-title { display: flex; align-items: center; gap: 10px; color: var(--primary-blue); margin: 0; font-size: 18px; font-weight: bold; }
        .res-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .res-body { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .res-info { display: flex; flex-direction: column; gap: 10px; }
        .res-info-row { display: flex; align-items: center; gap: 10px; font-size: 13px; color: var(--text-gray); }
        .res-qr { text-align: center; }
        .res-qr img { border-radius: 8px; border: 1px solid #eee; }
        .res-qr span { display: block; font-size: 10px; color: var(--text-gray); margin-top: 5px; }
        .res-actions { display: flex; flex-direction: column; gap: 10px; }
        .btn-outline-blue { background: white; border: 1px solid var(--primary-blue); color: var(--primary-blue); padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.2s; width: 100%; text-align: center; }
        .btn-outline-red { background: white; border: 1px solid var(--danger-red); color: var(--danger-red); padding: 10px; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.2s; width: 100%; text-align: center; }
        .btn-outline-blue:hover { background: var(--primary-blue); color: white; }
        .btn-outline-red:hover { background: var(--danger-red); color: white; }

        /* Modal Styles */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: white; padding: 30px; border-radius: 16px; width: 90%; max-width: 400px; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .modal-close { position: absolute; top: 15px; right: 15px; font-size: 20px; color: #777; cursor: pointer; background: none; border: none; }
        .modal-title { margin: 0 0 20px 0; font-size: 20px; color: var(--primary-blue); text-align: center; }
        
        .time-slots { display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 10px; margin-top: 10px; }
        .time-slot { border: 1px solid #ddd; border-radius: 6px; padding: 8px; text-align: center; font-size: 12px; cursor: pointer; color: var(--primary-blue); transition: 0.2s; }
        .time-slot.selected { background: var(--primary-blue); color: white; border-color: var(--primary-blue); }
        .time-slot.booked { background: #f0f0f0; color: #aaa; text-decoration: line-through; cursor: not-allowed; border-color: #eee; }

        /* Success Cancel Modal */
        .cancel-circle { background: var(--danger-red); color: white; width: 70px; height: 70px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 35px; margin: 0 auto 20px auto; border: 5px solid var(--danger-light); }
        
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
            
            @php
                $notifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->take(5)->get();
                $unreadCount = $notifications->where('is_read', false)->count();
            @endphp
            <div class="notification-wrapper">
                <i class="fa-regular fa-bell bell-icon" onclick="toggleNotifications()"></i>
                @if($unreadCount > 0)
                    <span class="notification-badge" id="notif-badge">{{ $unreadCount }}</span>
                @endif
                
                <div class="notification-dropdown" id="notif-dropdown">
                    <div class="notification-header">
                        <span>Notifications</span>
                        @if($unreadCount > 0)
                            <span style="color: var(--primary-blue); font-size: 12px; cursor: pointer;" onclick="markAllRead()">Mark all as read</span>
                        @endif
                    </div>
                    @forelse($notifications as $notif)
                        <div class="notification-item {{ $notif->is_read ? '' : 'unread' }}">
                            <div class="notification-title">{{ $notif->title }}</div>
                            <p class="notification-msg">{{ $notif->message }}</p>
                            <span style="font-size: 10px; color: #aaa; margin-top: 5px; display: block;">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="notification-item">
                            <p class="notification-msg" style="text-align: center;">No new notifications</p>
                        </div>
                    @endforelse
                </div>
            </div>
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
                        <div class="res-card">
                            <div class="res-header">
                                <h4 class="res-title">
                                    @if($res->sport == 'Pickleball')
                                        <i class="fa-solid fa-table-tennis-paddle-ball"></i>
                                    @else
                                        <img src="{{ asset('images/shuttlecock.png') }}" width="20">
                                    @endif
                                    {{ $res->sport ?? 'Badminton' }} Court {{ $res->court_id }}
                                </h4>
                                <span class="res-badge" style="background-color: {{ $res->status == 'confirmed' ? '#d1fae5' : ($res->status == 'cancelled' ? '#fee2e2' : '#fef3c7') }}; color: {{ $res->status == 'confirmed' ? '#059669' : ($res->status == 'cancelled' ? '#dc2626' : '#d97706') }};">
                                    {{ ucfirst($res->status) }}
                                </span>
                            </div>
                            
                            <div class="res-body">
                                <div class="res-info">
                                    <div class="res-info-row">
                                        <i class="fa-regular fa-calendar" style="width: 15px; text-align: center;"></i>
                                        {{ \Carbon\Carbon::parse($res->start_time)->format('D, M j, Y') }}
                                    </div>
                                    <div class="res-info-row">
                                        <i class="fa-regular fa-clock" style="width: 15px; text-align: center;"></i>
                                        {{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }} | {{ \Carbon\Carbon::parse($res->start_time)->diffInHours(\Carbon\Carbon::parse($res->end_time)) }} hr
                                    </div>
                                </div>
                                <div class="res-qr">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ urlencode($res->reservation_code) }}" alt="QR" width="70" height="70">
                                    <span>Tap to view QR</span>
                                </div>
                            </div>

                            @if($res->status == 'confirmed')
                                <div class="res-actions">
                                    <button class="btn-outline-blue" onclick="openEditModal({{ $res->id }}, '{{ $res->court_id }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('Y-m-d') }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }}', {{ \Carbon\Carbon::parse($res->start_time)->diffInHours(\Carbon\Carbon::parse($res->end_time)) }}, '{{ $res->sport ?? 'Badminton' }}', '{{ $res->reservation_code }}')">Edit Reservation</button>
                                    <button class="btn-outline-red" onclick="openCancelModal({{ $res->id }}, '{{ $res->reservation_code }}', '{{ $res->sport ?? 'Badminton' }} Court {{ $res->court_id }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('D, M j, Y') }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('g:i') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}')">Cancel Reservation</button>
                                </div>
                            @endif
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
                        <div class="res-card">
                            <div class="res-header">
                                <h4 class="res-title">
                                    @if($res->sport == 'Pickleball')
                                        <i class="fa-solid fa-table-tennis-paddle-ball"></i>
                                    @else
                                        <img src="{{ asset('images/shuttlecock.png') }}" width="20">
                                    @endif
                                    {{ $res->sport ?? 'Badminton' }} Court {{ $res->court_id }}
                                </h4>
                                <span class="res-badge" style="background-color: {{ $res->status == 'confirmed' ? '#d1fae5' : ($res->status == 'cancelled' ? '#fee2e2' : '#fef3c7') }}; color: {{ $res->status == 'confirmed' ? '#059669' : ($res->status == 'cancelled' ? '#dc2626' : '#d97706') }};">
                                    {{ ucfirst($res->status) }}
                                </span>
                            </div>
                            
                            <div class="res-body">
                                <div class="res-info">
                                    <div class="res-info-row">
                                        <i class="fa-regular fa-calendar" style="width: 15px; text-align: center;"></i>
                                        {{ \Carbon\Carbon::parse($res->start_time)->format('D, M j, Y') }}
                                    </div>
                                    <div class="res-info-row">
                                        <i class="fa-regular fa-clock" style="width: 15px; text-align: center;"></i>
                                        {{ \Carbon\Carbon::parse($res->start_time)->format('g:i A') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }} | {{ \Carbon\Carbon::parse($res->start_time)->diffInHours(\Carbon\Carbon::parse($res->end_time)) }} hr
                                    </div>
                                </div>
                                <div class="res-qr">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ urlencode($res->reservation_code) }}" alt="QR" width="70" height="70">
                                    <span>Tap to view QR</span>
                                </div>
                            </div>

                            @if($res->status == 'confirmed')
                                <div class="res-actions">
                                    <button class="btn-outline-blue" onclick="openEditModal({{ $res->id }}, '{{ $res->court_id }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('Y-m-d') }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }}', {{ \Carbon\Carbon::parse($res->start_time)->diffInHours(\Carbon\Carbon::parse($res->end_time)) }}, '{{ $res->sport ?? 'Badminton' }}', '{{ $res->reservation_code }}')">Edit Reservation</button>
                                    <button class="btn-outline-red" onclick="openCancelModal({{ $res->id }}, '{{ $res->reservation_code }}', '{{ $res->sport ?? 'Badminton' }} Court {{ $res->court_id }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('D, M j, Y') }}', '{{ \Carbon\Carbon::parse($res->start_time)->format('g:i') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('g:i A') }}')">Cancel Reservation</button>
                                </div>
                            @endif
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

    <!-- Modals -->
    <!-- Cancel Reservation Modal -->
    <div class="modal-overlay" id="cancelModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal('cancelModal')">&times;</button>
            <h2 class="modal-title" style="color: var(--danger-red);">Cancel Reservation</h2>
            
            <div style="margin-bottom: 15px;">
                <span id="cancel-res-code" style="color: var(--primary-blue); font-weight: bold; font-size: 14px;"></span>
                <div class="res-title" id="cancel-res-title" style="font-size: 16px;"></div>
                <div class="res-info-row" style="margin-top: 5px;">
                    <i class="fa-regular fa-calendar" style="width: 15px;"></i> <span id="cancel-res-date"></span>
                </div>
                <div class="res-info-row" style="margin-top: 5px;">
                    <i class="fa-regular fa-clock" style="width: 15px;"></i> <span id="cancel-res-time"></span>
                </div>
            </div>

            <form id="cancelForm" method="POST" action="">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="font-size: 12px; color: var(--text-gray); display: block; margin-bottom: 5px;">Please select a reason for cancellation</label>
                    <select name="reason" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; outline: none; font-family: inherit;">
                        <option>Schedule Conflict</option>
                        <option>Weather Conditions</option>
                        <option>Personal Emergency</option>
                        <option>Other</option>
                    </select>
                </div>

                <div style="background: var(--danger-light); color: var(--danger-red); padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 13px;">
                    <strong>Refund Policy</strong><br>
                    Payment will only be refunded if you cancel at least 5 hours before your reservation.<br>
                    No refund for late cancellation.
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn-outline-blue" onclick="closeModal('cancelModal')">Keep Reservation</button>
                    <button type="button" class="btn-primary-solid" style="background: var(--danger-red); width: 100%;" onclick="submitCancel()">Confirm Cancellation</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Reservation Modal -->
    <div class="modal-overlay" id="editModal">
        <div class="modal-content" style="max-width: 500px;">
            <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
            <h2 class="modal-title">Edit Reservation</h2>
            
            <div style="margin-bottom: 20px;">
                <div class="res-title" id="edit-res-title" style="font-size: 16px;"></div>
                <span class="res-badge" style="background: #d1fae5; color: #059669; margin-top: 5px; display: inline-block;">Confirmed</span>
            </div>

            <form id="editForm" method="POST" action="">
                @csrf
                <div style="margin-bottom: 20px;">
                    <h4 style="margin: 0 0 10px 0; color: var(--primary-blue);">Details</h4>
                    <div style="display: flex; gap: 15px;">
                        <div style="flex: 1;">
                            <label style="font-size: 12px; color: var(--text-gray);">Date</label>
                            <input type="date" id="edit-date" name="reservation_date" required onchange="checkAvailability()" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-family: inherit; outline: none;">
                        </div>
                        <div style="flex: 1;">
                            <label style="font-size: 12px; color: var(--text-gray);">Duration</label>
                            <select id="edit-duration" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-family: inherit; outline: none; background: #f9f9f9; cursor: not-allowed;" disabled>
                                <option>1 Hour</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-size: 12px; color: var(--text-gray);">Available Time Slot</label>
                    <div class="time-slots" id="edit-time-slots">
                        <!-- Filled by JS -->
                    </div>
                </div>

                <input type="hidden" id="edit-start-time" name="start_time" required>
                <input type="hidden" id="edit-end-time" name="end_time" required>
                <input type="hidden" id="edit-court-id" name="court_id">

                <button type="submit" class="btn-primary-solid" style="width: 100%;">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Success Cancel Modal -->
    <div class="modal-overlay" id="successCancelModal">
        <div class="modal-content" style="text-align: center;">
            <div class="cancel-circle">
                <i class="fa-solid fa-xmark"></i>
            </div>
            <h2 class="modal-title" style="color: var(--danger-red);">Reservation Cancelled</h2>
            <p class="notification-msg" style="margin-bottom: 20px;">
                Your reservation for<br>
                <strong style="color: var(--primary-blue);" id="success-cancel-title"></strong><br>
                on <strong style="color: var(--primary-blue);" id="success-cancel-datetime"></strong><br>
                has been cancelled.
            </p>
            <p style="font-size: 12px; color: var(--text-gray); margin-bottom: 20px;">A cancellation receipt has been sent to your email.</p>
            <button class="btn-primary-solid" style="width: 100%;" onclick="closeAndReload()">Done</button>
        </div>
    </div>

    <script>
        // Notification Toggle
        function toggleNotifications() {
            const dropdown = document.getElementById('notif-dropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        function markAllRead() {
            fetch('{{ url("/notifications/mark-read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            }).then(response => response.json())
              .then(data => {
                  if(data.success) {
                      document.getElementById('notif-badge').style.display = 'none';
                      document.querySelectorAll('.notification-item.unread').forEach(el => el.classList.remove('unread'));
                  }
              });
        }

        // Modal Handling
        function openCancelModal(id, code, title, date, time) {
            document.getElementById('cancelForm').action = '/reservations/' + id + '/cancel-user';
            document.getElementById('cancel-res-code').innerText = code;
            document.getElementById('cancel-res-title').innerHTML = title;
            document.getElementById('cancel-res-date').innerText = date;
            document.getElementById('cancel-res-time').innerText = time;
            
            // Store for success modal
            document.getElementById('success-cancel-title').innerHTML = title;
            document.getElementById('success-cancel-datetime').innerHTML = date + ' at ' + time.split(' - ')[0];

            document.getElementById('cancelModal').style.display = 'flex';
        }

        function submitCancel() {
            document.getElementById('cancelModal').style.display = 'none';
            
            fetch(document.getElementById('cancelForm').action, {
                method: 'POST',
                body: new FormData(document.getElementById('cancelForm'))
            }).then(response => {
                if(response.ok) {
                    document.getElementById('successCancelModal').style.display = 'flex';
                } else {
                    alert('Error cancelling reservation.');
                    location.reload();
                }
            });
        }

        function closeAndReload() {
            location.reload();
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        // Edit Modal Handling
        let currentEditCourtId = null;
        let currentEditStartTime = null;

        function openEditModal(id, courtId, date, time, duration, sport, code) {
            document.getElementById('editForm').action = '/reservations/' + id + '/edit-user';
            document.getElementById('edit-res-title').innerHTML = sport + ' Court ' + courtId;
            document.getElementById('edit-date').value = date;
            document.getElementById('edit-date').min = new Date().toISOString().split('T')[0];
            document.getElementById('edit-court-id').value = courtId;
            
            currentEditCourtId = courtId;
            currentEditStartTime = time;

            checkAvailability();
            document.getElementById('editModal').style.display = 'flex';
        }

        function checkAvailability() {
            let date = document.getElementById('edit-date').value;
            let courtId = currentEditCourtId;

            if(!date || !courtId) return;

            fetch(`/api/check-availability?date=${date}&court_id=${courtId}`)
                .then(response => response.json())
                .then(data => {
                    renderTimeSlots(data.booked_slots);
                });
        }

        function renderTimeSlots(bookedSlots) {
            const container = document.getElementById('edit-time-slots');
            container.innerHTML = '';
            
            const startHour = 8;
            const endHour = 22;

            for(let hour = startHour; hour < endHour; hour++) {
                let timeString24 = (hour < 10 ? '0' + hour : hour) + ':00';
                let suffix = hour >= 12 ? 'PM' : 'AM';
                let hour12 = hour > 12 ? hour - 12 : (hour === 0 ? 12 : hour);
                let timeString12 = `${hour12}:00 ${suffix}`;

                let div = document.createElement('div');
                div.className = 'time-slot';
                div.innerText = timeString12;
                div.dataset.time24 = timeString24;

                if(bookedSlots.includes(timeString12) && timeString24 !== currentEditStartTime) {
                    div.classList.add('booked');
                } else {
                    div.onclick = function() {
                        document.querySelectorAll('#edit-time-slots .time-slot').forEach(el => el.classList.remove('selected'));
                        this.classList.add('selected');
                        
                        document.getElementById('edit-start-time').value = this.dataset.time24 + ':00';
                        
                        let endH = parseInt(this.dataset.time24.split(':')[0]) + 1;
                        let endString24 = (endH < 10 ? '0' + endH : endH) + ':00:00';
                        document.getElementById('edit-end-time').value = endString24;
                    };
                }

                if(timeString24 === currentEditStartTime) {
                    div.classList.add('selected');
                    document.getElementById('edit-start-time').value = timeString24 + ':00';
                    let endH = parseInt(timeString24.split(':')[0]) + 1;
                    document.getElementById('edit-end-time').value = (endH < 10 ? '0' + endH : endH) + ':00:00';
                }

                container.appendChild(div);
            }
        }
    </script>
</body>
</html>