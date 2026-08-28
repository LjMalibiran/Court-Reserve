<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Court Reserve')</title>
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-blue: #0033cc;
            --light-blue: #e6f0ff;
            --text-dark: #333;
            --text-gray: #666;
            --bg-light: #f5f8ff;
            --danger-red: #e63946;
            --danger-light: #ffe6e6;
        }
        body { margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #ffffff; display: flex; height: 100vh; overflow: hidden; }
        
        /* Sidebar Styling */
        .sidebar { width: 260px; background-color: #f8fafc; border-right: 1px solid #f1f5f9; display: flex; flex-direction: column; padding: 25px 0; z-index: 10; justify-content: space-between; }
        .logo-container { padding: 0 25px 20px; text-align: left; }
        .logo-container img { max-width: 160px; }
        .nav-menu { list-style: none; padding: 0; margin: 15px 0 0 0; flex-grow: 1; }
        .nav-menu li { margin-bottom: 6px; padding: 0 15px; }
        .nav-menu a { display: flex; align-items: center; padding: 14px 20px; color: #0f2b6e; text-decoration: none; font-weight: 500; transition: 0.2s; font-size: 17px; border-radius: 10px; }
        .nav-menu a:hover { background-color: #e0e7ff; color: #0033cc; }
        .nav-menu a.active { background-color: #dbeafe; color: #0033cc; font-weight: 600; }
        .nav-menu a i { margin-right: 16px; font-size: 22px; width: 26px; text-align: center; }
        
        .logout-container { padding: 0 35px 15px; }
        .btn-logout { background: none; border: none; color: #0033cc; cursor: pointer; transition: 0.2s; padding: 10px 0; font-size: 24px; display: flex; align-items: center; justify-content: flex-start; }
        .btn-logout:hover { color: var(--danger-red); transform: scale(1.1); }

        /* Main Content */
        .main-content { flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; position: relative; background-color: #ffffff; }
        .main-content::after {
            content: "";
            position: absolute;
            bottom: -60px;
            right: -60px;
            width: 420px;
            height: 420px;
            background-image: url('{{ asset('images/shuttlecock.png') }}');
            background-size: contain;
            background-repeat: no-repeat;
            opacity: 0.03;
            pointer-events: none;
            z-index: 0;
        }
        
        /* Header */
        .top-header { display: flex; justify-content: space-between; align-items: center; padding: 30px 45px 15px 45px; background-color: transparent; position: relative; z-index: 1; }
        .top-header h1 { margin: 0; font-size: 32px; color: #0f2b6e; font-weight: 700; }
        .bell-icon { font-size: 22px; color: #0f2b6e; cursor: pointer; transition: 0.2s; position: relative; }
        .bell-icon:hover { color: #0033cc; }

        /* Notifications & Modals CSS */
        .notification-wrapper { position: relative; }
        .notification-badge { position: absolute; top: -5px; right: -5px; background: var(--danger-red); color: white; border-radius: 50%; padding: 2px 6px; font-size: 10px; font-weight: bold; }
        .notification-dropdown { position: absolute; top: 40px; right: 0; width: 300px; background: white; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 1px solid #eee; display: none; z-index: 100; max-height: 400px; overflow-y: auto; }
        .notification-header { padding: 15px; border-bottom: 1px solid #eee; font-weight: bold; color: var(--text-dark); display: flex; justify-content: space-between; }
        .notification-item { padding: 15px; border-bottom: 1px solid #eee; transition: 0.2s; cursor: pointer; }
        .notification-item:hover { background: #f9f9f9; }
        .notification-item.unread { background: #f0f4ff; }
        .notification-title { font-weight: bold; color: var(--primary-blue); font-size: 14px; margin-bottom: 5px; }
        .notification-msg { font-size: 12px; color: var(--text-gray); margin: 0; line-height: 1.4; }

        .btn-primary-solid { background: var(--primary-blue); color: white; border: none; padding: 12px 25px; border-radius: 5px; cursor: pointer; font-weight: bold; font-size: 15px; transition: 0.2s; margin-top: 10px; margin-bottom: 10px; }
        .btn-primary-solid:hover { background-color: #002299; }
        .btn-outline-blue { background: white; border: 1px solid var(--primary-blue); color: var(--primary-blue); padding: 12px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.2s; width: 100%; text-align: center; display: inline-block; box-sizing: border-box; margin-top: 10px; margin-bottom: 10px; }
        .btn-outline-red { background: white; border: 1px solid var(--danger-red); color: var(--danger-red); padding: 12px 20px; border-radius: 6px; font-weight: bold; cursor: pointer; transition: 0.2s; width: 100%; text-align: center; display: inline-block; box-sizing: border-box; margin-top: 10px; margin-bottom: 10px; }
        .btn-outline-blue:hover { background: var(--primary-blue); color: white; }
        .btn-outline-red:hover { background: var(--danger-red); color: white; }

        /* Modal Styles */
        .modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 1000; }
        .modal-content { background: white; padding: 30px; border-radius: 16px; width: 90%; max-width: 400px; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2); max-height: 90vh; overflow-y: auto; margin: 20px; }
        .modal-close { position: absolute; top: 15px; right: 15px; font-size: 20px; color: #777; cursor: pointer; background: none; border: none; padding: 5px; margin: 0; }
        .modal-title { margin: 0 0 20px 0; font-size: 20px; color: var(--primary-blue); text-align: center; }

        /* Mobile App Navigation Override */
        @media (max-width: 768px) {
            body { flex-direction: column; overflow-x: hidden; overflow-y: auto; }
            .sidebar { position: fixed; bottom: 0; left: 0; width: 100%; height: 70px; flex-direction: row; border-right: none; border-top: 1px solid #ddd; z-index: 1000; padding: 0; box-shadow: 0 -2px 10px rgba(0,0,0,0.05); background-color: white; }
            .logo-container, .logout-container { display: none !important; }
            .nav-menu { display: flex; flex-direction: row; margin: 0; width: 100%; justify-content: space-around; align-items: center; padding: 0; }
            .nav-menu li { flex: 1; padding: 0; margin: 0; display: flex; justify-content: center; }
            .nav-menu a { padding: 10px; flex-direction: column; font-size: 11px; border-left: none; color: #777; width: 100%; align-items: center; justify-content: center; border-radius: 0; }
            .nav-menu a i { margin-right: 0; margin-bottom: 4px; font-size: 20px; }
            .nav-menu a span { display: block; }
            .nav-menu a:hover, .nav-menu a.active { border-left: none; background: transparent; color: var(--primary-blue); }
            .mobile-logout { display: flex !important; align-items: center; justify-content: center; flex: 1; }
            
            .main-content { padding-bottom: 120px; }
            
            /* Responsive Header & Containers */
            .top-header { padding: 20px 20px 10px 20px !important; }
            .top-header h1 { font-size: 24px !important; }
            .content-area { padding: 0 20px !important; }
            
            /* Modals */
            .modal-content { padding: 20px !important; margin: 15px !important; width: 100% !important; max-width: calc(100% - 30px) !important; }
            .modal-title { font-size: 18px !important; }
            
            /* Notifications Dropdown */
            .notification-dropdown { width: calc(100vw - 40px) !important; right: -20px !important; }
        }
        
        @yield('styles')
    </style>
</head>
<body>
    
    <aside class="sidebar">
        <div class="logo-container">
            
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
        
        <ul class="nav-menu">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fa-solid fa-house"></i> <span>Home</span></a></li>
            <li><a href="{{ route('reservation.index') }}" class="{{ request()->routeIs('reservation.*') ? 'active' : '' }}"><i class="fa-regular fa-calendar-plus"></i> <span>Reservation</span></a></li>
            <li><a href="{{ route('history.index') }}" class="{{ request()->routeIs('history.*') ? 'active' : '' }}"><i class="fa-solid fa-clock-rotate-left"></i> <span>History</span></a></li>
            <li><a href="{{ route('profile.index') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}"><i class="fa-regular fa-user"></i> <span>Profile</span></a></li>
        </ul>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <h1>@yield('header_title', 'Dashboard')</h1>
            
            @php
                // Fetch notifications globally for any page using the layout
                $notifications = Auth::check() ? Auth::user()->customNotifications()->with('reservation')->orderBy('created_at', 'desc')->take(10)->get() : collect();
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
                            <span style="color: var(--primary-blue); font-size: 12px; cursor: pointer; margin-left: 10px;" onclick="markAllRead()">Mark all as read</span>
                        @endif
                    </div>
                    @forelse($notifications as $notif)
                        <div class="notification-item {{ $notif->is_read ? '' : 'unread' }}" onclick="openNotificationDetails('{{ $notif->reservation_id ?? '' }}', '{{ addslashes($notif->reservation ? ($notif->reservation->sport ?? 'Badminton') . ' Court ' . $notif->reservation->court_id : $notif->title) }}', '{{ $notif->reservation ? \Carbon\Carbon::parse($notif->reservation->start_time)->format('D, M j, Y') : '' }}', '{{ $notif->reservation ? \Carbon\Carbon::parse($notif->reservation->start_time)->format('g:i A') . ' - ' . \Carbon\Carbon::parse($notif->reservation->end_time)->format('g:i A') : '' }}', '{{ $notif->reservation ? $notif->reservation->reservation_code : '' }}', '{{ $notif->reservation ? ucfirst($notif->reservation->status) : '' }}', '{{ addslashes($notif->message) }}')">
                            <div class="notification-title">{{ $notif->title }}</div>
                            <p class="notification-msg">{{ $notif->message }}</p>
                            <span style="font-size: 10px; color: #aaa; margin-top: 5px; display: block;">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="notification-item">
                            <p class="notification-msg" style="text-align: center; margin: 10px 0;">No new notifications</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </header>

        <div style="flex-grow: 1; overflow-y: auto; padding: 0 30px 30px 30px;" class="scrollable-area">
            @yield('content')
        </div>
    </main>

    <!-- Global Modals -->
    <!-- Notification/Reservation Details Pop-up Modal -->
    <div class="modal-overlay" id="notifDetailsModal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeGlobalModal('notifDetailsModal')">&times;</button>
            <h2 class="modal-title">Notification Details</h2>
            
            <div style="margin-bottom: 20px; text-align: center;">
                <h3 id="nd-title" style="margin: 0; color: var(--primary-blue);"></h3>
                <span id="nd-badge" style="padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; display: inline-block; margin-top: 5px;"></span>
            </div>

            <p id="nd-fallback-msg" style="text-align: center; color: var(--text-gray); display: none; margin: 20px 0; line-height: 1.5;"></p>

            <div id="nd-info-grid">
                <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <div style="display: flex; gap: 10px; margin-bottom: 10px; font-size: 14px; color: var(--text-gray);">
                        <i class="fa-regular fa-calendar" style="width: 20px; text-align: center;"></i>
                        <span id="nd-date"></span>
                    </div>
                    <div style="display: flex; gap: 10px; font-size: 14px; color: var(--text-gray);">
                        <i class="fa-regular fa-clock" style="width: 20px; text-align: center;"></i>
                        <span id="nd-time"></span>
                    </div>
                </div>

                <div style="text-align: center; margin-bottom: 20px;">
                    <img id="nd-qr" src="" alt="QR" width="100" height="100" style="border-radius: 8px; border: 1px solid #eee; margin-bottom: 10px;">
                    <span style="display: block; font-size: 11px; color: var(--text-gray); margin-top: 5px;">Reservation Code: <strong id="nd-code"></strong></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional modals specific to the page -->
    @yield('modals')

    <script>
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

        function closeGlobalModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        function openNotificationDetails(reservationId, title, date, time, code, status, fallbackMsg) {
            document.getElementById('notifDetailsModal').style.display = 'flex';
            document.getElementById('nd-title').innerText = title;

            if (reservationId && reservationId !== 'null' && reservationId !== '') {
                document.getElementById('nd-fallback-msg').style.display = 'none';
                document.getElementById('nd-info-grid').style.display = 'block';
                document.getElementById('nd-badge').style.display = 'inline-block';
                
                document.getElementById('nd-date').innerText = date;
                document.getElementById('nd-time').innerText = time;
                document.getElementById('nd-code').innerText = code;
                
                const qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=" + encodeURIComponent(code);
                document.getElementById('nd-qr').src = qrUrl;

                const badge = document.getElementById('nd-badge');
                badge.innerText = status;
                if(status === 'Confirmed') {
                    badge.style.backgroundColor = '#d1fae5'; badge.style.color = '#059669';
                } else if(status === 'Cancelled') {
                    badge.style.backgroundColor = '#fee2e2'; badge.style.color = '#dc2626';
                } else {
                    badge.style.backgroundColor = '#fef3c7'; badge.style.color = '#d97706';
                }
            } else {
                document.getElementById('nd-info-grid').style.display = 'none';
                document.getElementById('nd-badge').style.display = 'none';
                document.getElementById('nd-fallback-msg').innerText = fallbackMsg || "This notification has no detailed reservation data attached.";
                document.getElementById('nd-fallback-msg').style.display = 'block';
            }
        }
    </script>
    
    @yield('scripts')
</body>
</html>
